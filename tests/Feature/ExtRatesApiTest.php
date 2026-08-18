<?php

namespace Tests\Feature;

use App\Models\Cash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExtRatesApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ext_rates_smoke_returns_expected_envelope(): void
    {
        // Sin forex configurado => cae al fallback interno (tabla `cashes`).
        config(['services.exchange_api.url' => '']);
        Cash::factory()->create(['status' => 1, 'name' => 'usd']);

        $this->getJson('/api/ext-rates')
            ->assertOk()
            ->assertJsonStructure(['data', 'source', 'disclaimer', 'cached_at'])
            ->assertJsonPath('source', 'internal');
    }

    /** @test */
    public function ext_rates_applies_scale_factor_and_makes_it_idempotent(): void
    {
        config(['services.exchange_api.url' => 'http://forex.test/api/rates']);

        // ARS cotizada por lote (scale_factor 1000); USD por unidad (scale_factor 1).
        Http::fake([
            '*/exchange-rates/primary/' => Http::response([
                'ARS' => ['scale_factor' => 1000, 'buy_rate' => '6.80', 'sell_rate' => '6.82', 'official_rate' => '6.81'],
                'USD' => ['scale_factor' => 1,    'buy_rate' => '6.90', 'sell_rate' => '6.96', 'official_rate' => '6.93'],
            ], 200),
        ]);

        $response = $this->getJson('/api/ext-rates')
            ->assertOk()
            ->assertJsonPath('source', 'external');

        // ARS: dividido por 1000 y scale_factor normalizado a 1 (idempotente).
        $this->assertEqualsWithDelta(0.00680, (float) $response->json('data.ARS.buy_rate'), 1e-6);
        $this->assertEqualsWithDelta(0.00682, (float) $response->json('data.ARS.sell_rate'), 1e-6);
        $this->assertEqualsWithDelta(0.00681, (float) $response->json('data.ARS.official_rate'), 1e-6);
        $this->assertSame(1, $response->json('data.ARS.scale_factor'));

        // USD (scale_factor 1): valores intactos, shape preservado.
        $this->assertEqualsWithDelta(6.90, (float) $response->json('data.USD.buy_rate'), 1e-6);
        $this->assertSame(1, $response->json('data.USD.scale_factor'));
    }

    /** @test */
    public function ext_rates_outage_serves_last_known_good_without_rehitting_forex(): void
    {
        config(['services.exchange_api.url' => 'http://forex.test/api/rates']);
        Cash::factory()->create(['status' => 1, 'name' => 'usd', 'buy' => 6.90, 'sell' => 7.10]);

        // Secuencia: 1ª respuesta viva, después forex cae (500 permanente).
        Http::fake([
            '*/exchange-rates/primary/' => Http::sequence()
                ->push(['USD' => ['scale_factor' => 1, 'buy_rate' => '13.35', 'sell_rate' => '13.55', 'official_rate' => '13.45']], 200)
                ->whenEmpty(Http::response('', 500)),
        ]);

        // 1) forex vivo → se cachea la respuesta y el last-known-good.
        $this->getJson('/api/ext-rates')->assertOk()->assertJsonPath('source', 'external');

        // 2) Expira el cache y forex cae: se sirve el last-known-good REAL
        //    (13.35, no el seed 6.90) con source honesto 'external_cache'.
        Cache::forget('kap_ext_rates');
        $this->getJson('/api/ext-rates')
            ->assertOk()
            ->assertJsonPath('source', 'external_cache')
            ->assertJsonPath('data.USD.buy_rate', '13.35');

        // 3) La caída quedó cacheada como centinela: el siguiente request NO
        //    re-pega a forex (antes: null nunca se cacheaba → un timeout de
        //    10s por request agotaba PHP-FPM durante la caída).
        $this->getJson('/api/ext-rates')->assertOk()->assertJsonPath('source', 'external_cache');
        Http::assertSentCount(2); // 1 viva + 1 del fallo; el tercer GET no pegó
    }

    /** @test */
    public function ext_rates_outage_without_lkg_falls_back_to_db_and_caches_the_failure(): void
    {
        config(['services.exchange_api.url' => 'http://forex.test/api/rates']);
        Cash::factory()->create(['status' => 1, 'name' => 'usd', 'buy' => 6.90, 'sell' => 7.10]);

        Http::fake(['*/exchange-rates/primary/' => Http::response('', 500)]);

        // Sin last-known-good → fallback DB con source 'internal' (nunca más
        // el 'external' mentiroso que reportaba forex aunque sirviera el seed).
        $this->getJson('/api/ext-rates')->assertOk()->assertJsonPath('source', 'internal');
        $this->getJson('/api/ext-rates')->assertOk()->assertJsonPath('source', 'internal');

        Http::assertSentCount(1); // el segundo request usó el centinela, sin re-pegar
    }

    /** @test */
    public function calculate_proxies_uppercase_type_and_normalizes_batch_scale(): void
    {
        config(['services.exchange_api.url' => 'http://forex.test/api/rates']);

        // forex-erp responde con la tasa del lote (ARS scale_factor 1000 →
        // "Bs por 1000 unidades") y solo aplica buy_rate si transaction_type
        // llega EN MAYÚSCULA ('BUY').
        Http::fake([
            '*/exchange-rates/calculate/' => Http::response([
                'amount_from'      => '100',
                'amount_to'        => '680.00',
                'rate'             => '6.80',
                'scale_factor'     => 1000,
                'transaction_type' => 'BUY',
                'currency_from'    => 'ARS',
                'currency_to'      => 'BOB',
            ], 200),
        ]);

        $response = $this->postJson('/api/ext-rates/calculate', [
            'amount'           => 100,
            'currency_from'    => 'ARS',
            'transaction_type' => 'buy',
        ])->assertOk();

        // Se proxeó en MAYÚSCULA: sin esto, forex-erp caía a sell_rate.
        Http::assertSent(fn ($request) => $request['transaction_type'] === 'BUY');

        // Normalizado a tasa/monto POR UNIDAD (no "Bs por 1000") y con el
        // MISMO shape que el fallback local (`result`, no `amount_to`).
        $this->assertEqualsWithDelta(0.0068, (float) $response->json('rate'), 1e-9);
        $this->assertEqualsWithDelta(0.68, (float) $response->json('result'), 1e-6);
        $response->assertJsonMissingPath('amount_to')
            ->assertJsonPath('transaction_type', 'buy')
            ->assertJsonPath('currency_from', 'ARS')
            ->assertJsonPath('source', 'external')
            ->assertJsonStructure([
                'amount', 'currency_from', 'currency_to', 'rate',
                'result', 'transaction_type', 'source', 'disclaimer',
            ]);
    }

    /** @test */
    public function calculate_falls_back_locally_when_forex_fails(): void
    {
        config(['services.exchange_api.url' => 'http://forex.test/api/rates']);
        Cash::factory()->create(['status' => 1, 'name' => 'usd', 'buy' => 6.90, 'sell' => 7.10]);

        Http::fake(['*/exchange-rates/calculate/' => Http::response('', 500)]);

        // "buy" local usa la tasa de COMPRA (6.90), mismo shape unificado.
        $response = $this->postJson('/api/ext-rates/calculate', [
            'amount'           => 100,
            'currency_from'    => 'USD',
            'transaction_type' => 'buy',
        ])
            ->assertOk()
            ->assertJsonPath('source', 'internal_fallback');

        $this->assertEqualsWithDelta(6.90, (float) $response->json('rate'), 1e-6);
        $this->assertEqualsWithDelta(690.0, (float) $response->json('result'), 1e-6);
    }
}
