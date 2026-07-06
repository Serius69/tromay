<?php

namespace Tests\Feature;

use App\Models\Cash;
use App\Models\Client;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuotationCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function guests_cannot_access_the_quotation_admin(): void
    {
        $this->get('/admin/quotation')->assertRedirect();
    }

    /** @test */
    public function store_computes_rate_and_total_from_the_currency_on_a_buy(): void
    {
        $cash = Cash::factory()->create(['name' => 'usd', 'buy' => 6.92, 'sell' => 7.10, 'status' => 1]);

        $this->actingAs($this->user)
            ->postJson(route('admin.quotation.store'), [
                'cash_id' => $cash->id,
                'type'    => 'buy',
                'amount'  => 100,
            ])
            ->assertOk()
            ->assertJsonPath('success', 'Cotización guardada correctamente.');

        $this->assertDatabaseHas('quotations', [
            'cash_id' => $cash->id,
            'type'    => 'buy',
            'amount'  => 100,
            'rate'    => 6.92,
            'total'   => 692.0,
        ]);
    }

    /** @test */
    public function store_uses_the_sell_rate_on_a_sell(): void
    {
        $cash = Cash::factory()->create(['name' => 'eur', 'buy' => 7.50, 'sell' => 7.72, 'status' => 1]);

        $this->actingAs($this->user)
            ->postJson(route('admin.quotation.store'), [
                'cash_id' => $cash->id,
                'type'    => 'sell',
                'amount'  => 50,
            ])
            ->assertOk();

        $this->assertDatabaseHas('quotations', [
            'cash_id' => $cash->id,
            'rate'    => 7.72,
            'total'   => 386.0,
        ]);
    }

    /** @test */
    public function client_submitted_rate_and_total_are_ignored(): void
    {
        $cash = Cash::factory()->create(['name' => 'usd', 'buy' => 6.92, 'sell' => 7.10, 'status' => 1]);

        $this->actingAs($this->user)
            ->postJson(route('admin.quotation.store'), [
                'cash_id' => $cash->id,
                'type'    => 'buy',
                'amount'  => 10,
                'rate'    => 999,   // tampered
                'total'   => 999,   // tampered
            ])
            ->assertOk();

        $this->assertDatabaseHas('quotations', ['rate' => 6.92, 'total' => 69.2]);
        $this->assertDatabaseMissing('quotations', ['rate' => 999]);
    }

    /** @test */
    public function store_validates_required_fields(): void
    {
        $this->actingAs($this->user)
            ->postJson(route('admin.quotation.store'), ['amount' => 0])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['cash_id', 'type', 'amount']);
    }

    /** @test */
    public function store_updates_an_existing_quotation_when_id_is_present(): void
    {
        $cash = Cash::factory()->create(['name' => 'usd', 'buy' => 6.92, 'sell' => 7.10, 'status' => 1]);
        $quotation = Quotation::factory()->create(['cash_id' => $cash->id, 'amount' => 1]);

        $this->actingAs($this->user)
            ->postJson(route('admin.quotation.store'), [
                'id'      => $quotation->id,
                'cash_id' => $cash->id,
                'type'    => 'buy',
                'amount'  => 200,
            ])
            ->assertOk();

        $this->assertDatabaseCount('quotations', 1);
        $this->assertDatabaseHas('quotations', ['id' => $quotation->id, 'amount' => 200, 'total' => 1384.0]);
    }

    /** @test */
    public function edit_returns_the_quotation_as_json(): void
    {
        $cash = Cash::factory()->create(['name' => 'usd', 'status' => 1]);
        $quotation = Quotation::factory()->create(['cash_id' => $cash->id]);

        $this->actingAs($this->user)
            ->getJson(route('admin.quotation.edit', $quotation->id))
            ->assertOk()
            ->assertJsonPath('id', $quotation->id);
    }

    /** @test */
    public function destroy_deletes_the_quotation(): void
    {
        $cash = Cash::factory()->create(['name' => 'usd', 'status' => 1]);
        $quotation = Quotation::factory()->create(['cash_id' => $cash->id]);

        $this->actingAs($this->user)
            ->deleteJson(route('admin.quotation.destroy', $quotation->id))
            ->assertOk();

        $this->assertDatabaseMissing('quotations', ['id' => $quotation->id]);
    }
}
