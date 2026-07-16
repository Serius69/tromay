<?php

namespace Tests\Feature;

use App\Models\Cash;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
