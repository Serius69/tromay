<?php

namespace Tests\Feature;

use App\Models\Cash;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function guests_cannot_manage_currencies(): void
    {
        $this->getJson(route('admin.cash.index'))->assertUnauthorized();
        $this->postJson(route('admin.cash.store'), [])->assertUnauthorized();
    }

    /** @test */
    public function updating_a_rate_records_a_snapshot_in_cash_rates(): void
    {
        $usd = Cash::factory()->create(['name' => 'usd', 'buy' => 6.92, 'sell' => 7.10, 'status' => 1]);
        $snapshotsBefore = \App\Models\CashRate::count();

        $this->actingAs($this->user)
            ->postJson(route('admin.cash.store'), [
                'cash_id' => $usd->id,
                'name'    => 'usd',
                'buy'     => 6.95,
                'sell'    => 7.15,
                'status'  => 1,
            ])
            ->assertOk();

        $this->assertDatabaseHas('cashes', ['id' => $usd->id, 'buy' => 6.95, 'sell' => 7.15]);
        // El CashObserver debe registrar el histórico del cambio de tasa.
        $this->assertEquals($snapshotsBefore + 1, \App\Models\CashRate::count());
        $this->assertDatabaseHas('cash_rates', ['cash_id' => $usd->id, 'buy' => 6.95, 'sell' => 7.15]);
    }

    /** @test */
    public function updating_a_rate_invalidates_the_public_rates_cache(): void
    {
        $usd = Cash::factory()->create(['name' => 'usd', 'buy' => 6.92, 'sell' => 7.10, 'status' => 1]);

        // Calienta el caché de la API pública con la tasa vieja.
        $this->getJson('/api/rates')->assertOk()->assertJsonPath('data.0.buy', 6.92);

        $this->actingAs($this->user)->postJson(route('admin.cash.store'), [
            'cash_id' => $usd->id,
            'name'    => 'usd',
            'buy'     => 7.00,
            'sell'    => 7.20,
            'status'  => 1,
        ])->assertOk();

        // Sin esperar el TTL de 300s, la API ya refleja la tasa nueva.
        $this->getJson('/api/rates')->assertOk()
            ->assertJsonPath('data.0.buy', fn ($buy) => abs($buy - 7.0) < 0.0001);
    }

    /** @test */
    public function an_inverted_spread_is_rejected(): void
    {
        $usd = Cash::factory()->create(['name' => 'usd', 'buy' => 6.92, 'sell' => 7.10, 'status' => 1]);

        $this->actingAs($this->user)
            ->postJson(route('admin.cash.store'), [
                'cash_id' => $usd->id,
                'name'    => 'usd',
                'buy'     => 7.10,
                'sell'    => 6.92, // venta < compra: la casa perdería en cada operación
                'status'  => 1,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['sell']);

        $this->assertDatabaseHas('cashes', ['id' => $usd->id, 'buy' => 6.92, 'sell' => 7.10]);
    }

    /** @test */
    public function store_validates_required_fields(): void
    {
        $this->actingAs($this->user)
            ->postJson(route('admin.cash.store'), [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'buy', 'sell', 'status']);
    }

    /** @test */
    public function a_currency_can_be_deleted(): void
    {
        $cash = Cash::factory()->create(['name' => 'clp', 'status' => 1]);

        $this->actingAs($this->user)
            ->deleteJson(route('admin.cash.destroy', $cash->id))
            ->assertOk();

        $this->assertDatabaseMissing('cashes', ['id' => $cash->id]);
    }
}
