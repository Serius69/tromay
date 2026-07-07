<?php

namespace Tests\Feature;

use App\Models\Cash;
use App\Models\Client;
use App\Models\Transaction;
use App\Models\TurnSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TurnSessionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Cash::factory()->create(['name' => 'usd', 'buy' => 6.92, 'sell' => 7.10, 'status' => 1]);
    }

    /** @test */
    public function guests_cannot_use_turn_endpoints(): void
    {
        $this->getJson(route('admin.turn.status'))->assertUnauthorized();
        $this->postJson(route('admin.turn.open'))->assertUnauthorized();
        $this->postJson(route('admin.turn.close'))->assertUnauthorized();
        $this->getJson(route('admin.turn.history'))->assertUnauthorized();
    }

    /** @test */
    public function status_reports_no_open_turn_initially(): void
    {
        $this->actingAs($this->user)
            ->getJson(route('admin.turn.status'))
            ->assertOk()
            ->assertJsonPath('open', false)
            ->assertJsonPath('turn', null);
    }

    /** @test */
    public function a_turn_can_be_opened_and_snapshots_current_rates(): void
    {
        $this->actingAs($this->user)
            ->postJson(route('admin.turn.open'), ['opening_notes' => 'Inicio de jornada'])
            ->assertCreated()
            ->assertJsonStructure(['message', 'turn_id', 'opened_at']);

        $turn = TurnSession::first();
        $this->assertEquals('open', $turn->status);
        $this->assertEquals($this->user->id, $turn->user_id);
        // Sin balances explícitos, captura las tasas activas del momento.
        $this->assertArrayHasKey('Usd', $turn->initial_balances);
    }

    /** @test */
    public function a_second_turn_cannot_be_opened_while_one_is_open(): void
    {
        $this->actingAs($this->user)->postJson(route('admin.turn.open'))->assertCreated();

        $this->actingAs($this->user)
            ->postJson(route('admin.turn.open'))
            ->assertStatus(409)
            ->assertJsonStructure(['error', 'turn_id']);

        $this->assertEquals(1, TurnSession::count());
    }

    /** @test */
    public function closing_a_turn_counts_the_transactions_registered_during_it(): void
    {
        $this->actingAs($this->user)->postJson(route('admin.turn.open'))->assertCreated();

        Transaction::factory()->count(3)->create([
            'user_id'   => $this->user->id,
            'client_id' => Client::factory()->create()->id,
        ]);

        $this->actingAs($this->user)
            ->postJson(route('admin.turn.close'), ['closing_notes' => 'Cierre normal'])
            ->assertOk()
            ->assertJsonPath('transaction_count', 3);

        $turn = TurnSession::first();
        $this->assertEquals('closed', $turn->status);
        $this->assertNotNull($turn->closed_at);
    }

    /** @test */
    public function closing_without_an_open_turn_returns_404(): void
    {
        $this->actingAs($this->user)
            ->postJson(route('admin.turn.close'))
            ->assertNotFound();
    }

    /** @test */
    public function history_lists_past_turns(): void
    {
        $this->actingAs($this->user)->postJson(route('admin.turn.open'))->assertCreated();
        $this->actingAs($this->user)->postJson(route('admin.turn.close'))->assertOk();

        $this->actingAs($this->user)
            ->getJson(route('admin.turn.history'))
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.status', 'closed');
    }
}
