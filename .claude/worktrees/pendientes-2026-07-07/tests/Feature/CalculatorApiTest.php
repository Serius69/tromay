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
            ->assertJsonPath('data.result', fn ($result) => abs($result - 100 * 6.96) < 0.0001)
            ->assertJsonPath('data.currency', 'USD')
            ->assertJsonPath('data.transaction_type', 'buy');
    }

    /** @test */
    public function calculator_returns_correct_sell_amount(): void
    {
        $response = $this->getJson('/api/calculator?currency=usd&amount=200&type=sell');

        $response->assertOk()
            ->assertJsonPath('data.result', fn ($result) => abs($result - 200 * 6.97) < 0.0001)
            ->assertJsonPath('data.rate', 6.97);
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
        $this->getJson('/api/calculator?currency=usd&amount=0&type=buy')
            ->assertStatus(422)
            ->assertJsonFragment(['error' => 'El monto debe ser mayor a cero.']);
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
            ->assertJsonFragment(['error' => 'El tipo debe ser "buy" o "sell".']);
    }
}
