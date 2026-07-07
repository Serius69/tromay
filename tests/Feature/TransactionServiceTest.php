<?php

namespace Tests\Feature;

use App\Models\Cash;
use App\Models\Client;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    private TransactionService $service;
    private Cash $cashForeign;
    private Cash $cashBase;
    private array $clientData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(TransactionService::class);

        $user = User::factory()->create();
        Auth::login($user);

        $this->cashForeign = Cash::factory()->create([
            'name'   => 'usd',
            'buy'    => 6.96,
            'sell'   => 6.97,
            'status' => 1,
        ]);

        $this->cashBase = Cash::factory()->create([
            'name'   => 'eur',
            'buy'    => 7.50,
            'sell'   => 7.55,
            'status' => 1,
        ]);

        $this->clientData = [
            'ci'       => '12345678',
            'name'     => 'Juan',
            'lastname' => 'Perez',
        ];
    }

    /** @test */
    public function type_sell_calculates_amount2_correctly(): void
    {
        // TYPE_SELL (2): client sells foreign → receives BOB
        // amount2 = amount1 * cash1.sell
        $transaction = $this->service->register(array_merge($this->clientData, [
            'cash1_id' => $this->cashForeign->id,
            'cash2_id' => $this->cashBase->id,
            'amount1'  => 100.0,
            'date'     => now()->toDateTimeString(),
            'type'     => TransactionService::TYPE_SELL,
        ]));

        $this->assertEqualsWithDelta(100.0 * 6.97, $transaction->amount2, 0.001);
        $this->assertEquals(TransactionService::TYPE_SELL, $transaction->type);
    }

    /** @test */
    public function type_buy_calculates_amount2_correctly(): void
    {
        // TYPE_BUY (1): client buys foreign → pays base currency
        // amount2 = amount1 / cash2.buy
        $transaction = $this->service->register(array_merge($this->clientData, [
            'cash1_id' => $this->cashBase->id,
            'cash2_id' => $this->cashForeign->id,
            'amount1'  => 696.0,
            'date'     => now()->toDateTimeString(),
            'type'     => TransactionService::TYPE_BUY,
        ]));

        $this->assertEqualsWithDelta(696.0 / 6.96, $transaction->amount2, 0.001);
        $this->assertEquals(TransactionService::TYPE_BUY, $transaction->type);
    }

    /** @test */
    public function register_creates_or_finds_client_by_ci(): void
    {
        $this->service->register(array_merge($this->clientData, [
            'cash1_id' => $this->cashForeign->id,
            'cash2_id' => $this->cashBase->id,
            'amount1'  => 50.0,
            'date'     => now()->toDateTimeString(),
            'type'     => TransactionService::TYPE_SELL,
        ]));

        $this->assertDatabaseHas('clients', ['ci' => '12345678']);
        $this->assertEquals(1, Client::where('ci', '12345678')->count());

        // Second registration with same CI should reuse the client
        $this->service->register(array_merge($this->clientData, [
            'cash1_id' => $this->cashForeign->id,
            'cash2_id' => $this->cashBase->id,
            'amount1'  => 25.0,
            'date'     => now()->toDateTimeString(),
            'type'     => TransactionService::TYPE_SELL,
        ]));

        $this->assertEquals(1, Client::where('ci', '12345678')->count());
    }

    /** @test */
    public function register_aborts_when_cash1_is_inactive(): void
    {
        $inactiveCash = Cash::factory()->create(['name' => 'clp', 'status' => 0]);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        $this->service->register(array_merge($this->clientData, [
            'cash1_id' => $inactiveCash->id,
            'cash2_id' => $this->cashBase->id,
            'amount1'  => 100.0,
            'date'     => now()->toDateTimeString(),
            'type'     => TransactionService::TYPE_SELL,
        ]));
    }

    /** @test */
    public function register_aborts_when_cash2_is_inactive(): void
    {
        $inactiveCash = Cash::factory()->create(['name' => 'pen', 'status' => 0]);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        $this->service->register(array_merge($this->clientData, [
            'cash1_id' => $this->cashForeign->id,
            'cash2_id' => $inactiveCash->id,
            'amount1'  => 100.0,
            'date'     => now()->toDateTimeString(),
            'type'     => TransactionService::TYPE_SELL,
        ]));
    }

    /** @test */
    public function register_stores_transaction_in_database(): void
    {
        $this->service->register(array_merge($this->clientData, [
            'cash1_id' => $this->cashForeign->id,
            'cash2_id' => $this->cashBase->id,
            'amount1'  => 150.0,
            'date'     => '2026-05-12 10:00:00',
            'type'     => TransactionService::TYPE_SELL,
        ]));

        $this->assertDatabaseHas('transactions', [
            'cash1_id' => $this->cashForeign->id,
            'cash2_id' => $this->cashBase->id,
            'amount1'  => 150.0,
            'status'   => 1,
        ]);
    }
}
