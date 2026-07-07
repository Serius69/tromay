<?php

namespace App\Services;

use App\Models\Cash;
use App\Models\Client;
use App\Models\Quotation;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    // type constants — matches the integer stored in transactions.type
    // Perspectiva del CLIENTE: TYPE_BUY = cliente compra divisa (la casa vende),
    // TYPE_SELL = cliente vende divisa (la casa compra).
    public const TYPE_BUY  = 1;
    public const TYPE_SELL = 2;

    public function __construct(
        private RateService $rates,
        private DashboardService $dashboard,
    ) {}

    /**
     * Register a buy or sell transaction.
     * Finds or creates the client by CI, calculates amounts and persists.
     *
     * @param  array{ci:string, name:string, lastname:string, cash1_id:int, cash2_id:int, amount1:float, date:string, type:int}  $data
     */
    public function register(array $data): Transaction
    {
        $transaction = DB::transaction(function () use ($data) {
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

        $this->dashboard->invalidate();

        return $transaction;
    }

    /**
     * Convierte una cotización vigente en una transacción real, honrando la
     * tasa pactada en la proforma (calculada en servidor al emitirla) mientras
     * no esté vencida. Bloquea la fila para impedir doble conversión.
     */
    public function registerFromQuotation(Quotation $quotation): Transaction
    {
        $transaction = DB::transaction(function () use ($quotation) {
            $locked = Quotation::whereKey($quotation->id)->lockForUpdate()->firstOrFail();

            if ($error = $locked->convertibilityError()) {
                abort(422, $error);
            }
            abort_if(optional($locked->cash)->status !== 1, 422, 'La divisa de la cotización ya no está activa.');

            // Mapeo: quotation 'buy' (la casa compra divisa) = el cliente vende → TYPE_SELL.
            //        quotation 'sell' (la casa vende divisa) = el cliente compra → TYPE_BUY.
            // En TYPE_SELL amount1 es la divisa entregada y amount2 los BOB pagados;
            // en TYPE_BUY amount1 son los BOB pagados y amount2 la divisa entregada.
            // cash1 = cash2 = divisa de la cotización: no existe fila BOB en `cashes`
            // (el lado BOB se identifica por el tipo, igual que en los formularios).
            $isHouseBuy = $locked->type === 'buy';

            $transaction = Transaction::create([
                'user_id'   => Auth::id() ?? 1,
                'client_id' => $locked->client_id,
                'cash1_id'  => $locked->cash_id,
                'cash2_id'  => $locked->cash_id,
                'amount1'   => $isHouseBuy ? $locked->amount : $locked->total,
                'amount2'   => $isHouseBuy ? $locked->total : $locked->amount,
                'date'      => now(),
                'type'      => $isHouseBuy ? self::TYPE_SELL : self::TYPE_BUY,
                'status'    => 1,
            ]);

            $locked->status = Quotation::STATUS_CONVERTIDA;
            $locked->transaction_id = $transaction->id;
            $locked->save();

            return $transaction;
        });

        $this->dashboard->invalidate();

        return $transaction;
    }

    /**
     * Calculate the output amount using current rates.
     *
     * Convención de tasas (columna `cashes.buy` < `cashes.sell`):
     *   buy  = tasa a la que LA CASA COMPRA divisa (el cliente vende) — la más baja.
     *   sell = tasa a la que LA CASA VENDE divisa (el cliente compra) — la más alta.
     *
     * TYPE_BUY  (cliente compra divisa): amount1 en BOB → amount2 = amount1 / sell.
     * TYPE_SELL (cliente vende divisa):  amount1 en divisa → amount2 = amount1 * buy.
     */
    public function calculateAmount2(float $amount1, Cash $cash1, Cash $cash2, int $type): float
    {
        if ($type === self::TYPE_BUY) {
            $rate = (float) $cash2->sell;
            return $rate > 0 ? round($amount1 / $rate, 4) : 0;
        }

        // SELL
        $rate = (float) $cash1->buy;
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
