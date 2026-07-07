<?php

namespace App\Services;

use App\Models\Cash;
use App\Models\Client;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    // type constants — matches the integer stored in transactions.type
    public const TYPE_BUY  = 1;
    public const TYPE_SELL = 2;

    public function __construct(private RateService $rates) {}

    /**
     * Register a buy or sell transaction.
     * Finds or creates the client by CI, calculates amounts and persists.
     *
     * @param  array{ci:string, name:string, lastname:string, cash1_id:int, cash2_id:int, amount1:float, date:string, type:int}  $data
     */
    public function register(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $client = $this->resolveClient($data);

            $cash1 = Cash::findOrFail($data['cash1_id']);
            $cash2 = Cash::findOrFail($data['cash2_id']);

            abort_if($cash1->status !== 1 || $cash2->status !== 1, 422, 'Una o más divisas seleccionadas no están activas.');

            $amount2 = $this->calculateAmount2(
                (float) $data['amount1'],
                $cash1,
                $cash2,
                (int) $data['type'],
            );

            return Transaction::create([
                'user_id'   => Auth::id() ?? 1,
                'client_id' => $client->id,
                'cash1_id'  => $cash1->id,
                'cash2_id'  => $cash2->id,
                'amount1'   => $data['amount1'],
                'amount2'   => $amount2,
                'date'      => $data['date'],
                'type'      => $data['type'],
                'status'    => 1,
            ]);
        });
    }

    /**
     * Calculate the output amount using current rates.
     * For a buy (client buys foreign currency): amount1 is in BOB → amount2 in foreign.
     * For a sell (client sells foreign currency): amount1 is in foreign → amount2 in BOB.
     */
    public function calculateAmount2(float $amount1, Cash $cash1, Cash $cash2, int $type): float
    {
        if ($type === self::TYPE_BUY) {
            $rate = (float) $cash2->buy;
            return $rate > 0 ? round($amount1 / $rate, 4) : 0;
        }

        // SELL
        $rate = (float) $cash1->sell;
        return round($amount1 * $rate, 4);
    }

    /**
     * Find an existing client by CI or create a new one.
     */
    private function resolveClient(array $data): Client
    {
        return Client::firstOrCreate(
            ['ci' => $data['ci']],
            [
                'name'     => $data['name'],
                'lastname' => $data['lastname'],
                'status'   => 1,
            ],
        );
    }
}
