<?php

namespace Tests\Feature;

use App\Models\Cash;
use App\Models\Client;
use App\Models\Quotation;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuotationConvertTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Cash $usd;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user   = User::factory()->create();
        $this->usd    = Cash::factory()->create(['name' => 'usd', 'buy' => 6.92, 'sell' => 7.10, 'status' => 1]);
        $this->client = Client::factory()->create(['status' => 1]);
    }

    private function makeQuotation(array $overrides = []): Quotation
    {
        return Quotation::factory()->create(array_merge([
            'client_id'   => $this->client->id,
            'cash_id'     => $this->usd->id,
            'type'        => 'buy',
            'amount'      => 100,
            'rate'        => 6.92,
            'total'       => 692.0,
            'valid_until' => today()->addDay(),
            'status'      => Quotation::STATUS_VIGENTE,
        ], $overrides));
    }

    /** @test */
    public function guests_cannot_convert_quotations(): void
    {
        $quotation = $this->makeQuotation();

        $this->postJson(route('admin.quotation.convert', $quotation))
            ->assertUnauthorized();

        $this->assertDatabaseHas('quotations', [
            'id'     => $quotation->id,
            'status' => Quotation::STATUS_VIGENTE,
        ]);
    }

    /** @test */
    public function converting_a_buy_quotation_creates_a_type_sell_transaction_with_the_quoted_rate(): void
    {
        // 'buy' = la casa compra divisa: el cliente entrega 100 USD y recibe 692 BOB.
        $quotation = $this->makeQuotation(['type' => 'buy']);

        $this->actingAs($this->user)
            ->postJson(route('admin.quotation.convert', $quotation))
            ->assertOk()
            ->assertJsonPath('success', 'Cotización convertida en transacción correctamente.');

        $this->assertDatabaseHas('transactions', [
            'client_id' => $this->client->id,
            'cash1_id'  => $this->usd->id,
            'type'      => TransactionService::TYPE_SELL,
            'amount1'   => 100.0,
            'amount2'   => 692.0,
            'status'    => 1,
        ]);

        $quotation->refresh();
        $this->assertEquals(Quotation::STATUS_CONVERTIDA, $quotation->status);
        $this->assertNotNull($quotation->transaction_id);
    }

    /** @test */
    public function converting_a_sell_quotation_creates_a_type_buy_transaction(): void
    {
        // 'sell' = la casa vende divisa: el cliente paga 710 BOB y recibe 100 USD.
        $quotation = $this->makeQuotation([
            'type'  => 'sell',
            'rate'  => 7.10,
            'total' => 710.0,
        ]);

        $this->actingAs($this->user)
            ->postJson(route('admin.quotation.convert', $quotation))
            ->assertOk();

        $this->assertDatabaseHas('transactions', [
            'client_id' => $this->client->id,
            'type'      => TransactionService::TYPE_BUY,
            'amount1'   => 710.0,
            'amount2'   => 100.0,
        ]);
    }

    /** @test */
    public function a_quotation_cannot_be_converted_twice(): void
    {
        $quotation = $this->makeQuotation();

        $this->actingAs($this->user)->postJson(route('admin.quotation.convert', $quotation))->assertOk();
        $this->actingAs($this->user)->postJson(route('admin.quotation.convert', $quotation))->assertStatus(422);

        $this->assertDatabaseCount('transactions', 1);
    }

    /** @test */
    public function an_annulled_quotation_cannot_be_converted(): void
    {
        $quotation = $this->makeQuotation(['status' => Quotation::STATUS_ANULADA]);

        $this->actingAs($this->user)
            ->postJson(route('admin.quotation.convert', $quotation))
            ->assertStatus(422);

        $this->assertDatabaseCount('transactions', 0);
    }

    /** @test */
    public function an_expired_quotation_cannot_be_converted(): void
    {
        $quotation = $this->makeQuotation(['valid_until' => today()->subDay()]);

        $this->actingAs($this->user)
            ->postJson(route('admin.quotation.convert', $quotation))
            ->assertStatus(422);

        $this->assertDatabaseCount('transactions', 0);
    }

    /** @test */
    public function a_quotation_without_client_cannot_be_converted(): void
    {
        $quotation = $this->makeQuotation(['client_id' => null]);

        $this->actingAs($this->user)
            ->postJson(route('admin.quotation.convert', $quotation))
            ->assertStatus(422);

        $this->assertDatabaseCount('transactions', 0);
    }

    /** @test */
    public function a_quotation_with_an_inactive_currency_cannot_be_converted(): void
    {
        $quotation = $this->makeQuotation();
        $this->usd->update(['status' => 0]);

        $this->actingAs($this->user)
            ->postJson(route('admin.quotation.convert', $quotation))
            ->assertStatus(422);

        $this->assertDatabaseCount('transactions', 0);
    }

    /** @test */
    public function a_converted_quotation_cannot_be_deleted(): void
    {
        $quotation = $this->makeQuotation();
        $this->actingAs($this->user)->postJson(route('admin.quotation.convert', $quotation))->assertOk();

        $this->actingAs($this->user)
            ->deleteJson(route('admin.quotation.destroy', $quotation->id))
            ->assertStatus(422);

        $this->assertDatabaseHas('quotations', ['id' => $quotation->id]);
    }

    /** @test */
    public function a_converted_quotation_cannot_be_edited(): void
    {
        $quotation = $this->makeQuotation();
        $this->actingAs($this->user)->postJson(route('admin.quotation.convert', $quotation))->assertOk();

        $this->actingAs($this->user)
            ->postJson(route('admin.quotation.store'), [
                'id'      => $quotation->id,
                'cash_id' => $this->usd->id,
                'type'    => 'buy',
                'amount'  => 999,
            ])
            ->assertStatus(422);

        $this->assertDatabaseHas('quotations', ['id' => $quotation->id, 'amount' => 100.0]);
    }
}
