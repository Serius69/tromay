<?php

namespace Tests\Feature;

use App\Models\Cash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalculatorApiTest extends TestCase
{
    use RefreshDatabase;

    private Cash $usd;

    protected function setUp(): void
    {
        parent::setUp();
        // Sin forex configurado: el calculador debe usar las tasas sembradas
        // (si el .env local apunta a un forex real, el overlay contaminaría
        // los montos esperados).
        config(['services.exchange_api.url' => '']);
        $this->usd = Cash::factory()->create([
            'name'   => 'usd',
            'buy'    => 6.96,
            'sell'   => 6.97,
            'status' => 1,
        ]);
    }

    /** @test */
    public function calculator_returns_correct_buy_amount(): void
    {
        $response = $this->getJson('/api/calculator?currency=usd&amount=100&type=buy');

        $response->assertOk()
            ->assertJsonStructure(['data' => ['amount', 'currency', 'transaction_type', 'rate', 'result'], 'disclaimer', 'updated_at'])
            ->assertJsonPath('data.currency', 'USD')
            ->assertJsonPath('data.transaction_type', 'buy');

        // OJO: json_encode(696.0) emite "696" (int al decodificar), así que
        // assertJsonPath(assertSame) con un float esperado nunca calza.
        $this->assertEqualsWithDelta(100 * 6.96, (float) $response->json('data.result'), 1e-6);
    }

    /** @test */
    public function calculator_returns_correct_sell_amount(): void
    {
        $response = $this->getJson('/api/calculator?currency=usd&amount=200&type=sell');

        $response->assertOk();
        $this->assertEqualsWithDelta(200 * 6.97, (float) $response->json('data.result'), 1e-6);
        $this->assertEqualsWithDelta(6.97, (float) $response->json('data.rate'), 1e-6);
    }

    /** @test */
    public function calculator_returns_404_for_unknown_currency(): void
    {
        $this->getJson('/api/calculator?currency=xyz&amount=100&type=buy')
            ->assertNotFound()
            ->assertJsonFragment(['error' => 'Divisa no encontrada o no disponible.']);
    }

    /** @test */
    public function calculator_returns_404_for_inactive_currency(): void
    {
        Cash::factory()->create(['name' => 'eur', 'status' => 0]);

        $this->getJson('/api/calculator?currency=eur&amount=100&type=buy')
            ->assertNotFound();
    }

    /** @test */
    public function calculator_returns_422_for_zero_amount(): void
    {
        // El endpoint valida con el validador de Laravel (shape estándar
        // errors.amount), ya no con el mensaje custom antiguo.
        $this->getJson('/api/calculator?currency=usd&amount=0&type=buy')
            ->assertStatus(422)
            ->assertJsonValidationErrors('amount');
    }

    /** @test */
    public function calculator_returns_422_for_negative_amount(): void
    {
        $this->getJson('/api/calculator?currency=usd&amount=-50&type=buy')
            ->assertStatus(422);
    }

    /** @test */
    public function calculator_returns_422_for_invalid_type(): void
    {
        $this->getJson('/api/calculator?currency=usd&amount=100&type=exchange')
            ->assertStatus(422)
            ->assertJsonValidationErrors('type');
    }
}
