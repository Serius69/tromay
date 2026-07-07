<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function dailyClose(Request $request)
    {
        $date = $request->input('date', today()->toDateString());

        $transactions = Transaction::with(['client', 'cash1', 'cash2', 'user'])
            ->whereDate('date', $date)
            ->orderBy('id')
            ->get();

        // Perspectiva de la casa: "compra" = TYPE_SELL (el cliente vende divisa),
        // "venta" = TYPE_BUY (el cliente compra divisa).
        $houseBuys  = $transactions->where('type', TransactionService::TYPE_SELL);
        $houseSells = $transactions->where('type', TransactionService::TYPE_BUY);

        $data = [
            'date'         => $date,
            'transactions' => $transactions,
            'buy_count'    => $houseBuys->count(),
            'sell_count'   => $houseSells->count(),
            // Volumen en BOB: en TYPE_SELL los BOB son amount2; en TYPE_BUY, amount1.
            'buy_volume'   => $houseBuys->sum('amount2'),
            'sell_volume'  => $houseSells->sum('amount1'),
            'total_count'  => $transactions->count(),
        ];

        if ($request->query('export') === 'csv') {
            return $this->dailyCloseCsv($data);
        }

        return view('admin.reports.daily_close', $data);
    }

    /**
     * CSV descargable (compatible con Excel: BOM UTF-8 y separador ";").
     */
    private function dailyCloseCsv(array $data): StreamedResponse
    {
        $filename = 'cierre-diario-' . $data['date'] . '.csv';

        return response()->streamDownload(function () use ($data) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM para que Excel detecte UTF-8

            // Anti inyección de fórmulas: nombres de cliente/cajero son texto libre;
            // un valor que empiece con = + - @ se evaluaría como fórmula en Excel.
            $safe = fn ($v) => is_string($v) && preg_match('/^[=+\-@\t\r]/', $v) ? "'" . $v : $v;
            $row  = fn (array $cols) => fputcsv($out, array_map($safe, $cols), ';');

            $row(['Tromay Casa de Cambio — Cierre Diario']);
            $row(['Fecha', $data['date']]);
            $row(['Generado', now()->format('Y-m-d H:i:s')]);
            $row([]);
            $row(['#', 'Hora', 'CI Cliente', 'Cliente', 'Tipo', 'Divisa origen', 'Divisa destino', 'Monto 1', 'Monto 2', 'Cajero']);

            foreach ($data['transactions'] as $i => $tx) {
                $row([
                    $i + 1,
                    $tx->date?->format('H:i') ?? '',
                    $tx->client?->ci ?? '',
                    trim(($tx->client?->name ?? '') . ' ' . ($tx->client?->lastname ?? '')),
                    $tx->type_label,
                    strtoupper($tx->cash1?->getRawOriginal('name') ?? ''),
                    strtoupper($tx->cash2?->getRawOriginal('name') ?? ''),
                    number_format((float) $tx->amount1, 4, '.', ''),
                    number_format((float) $tx->amount2, 4, '.', ''),
                    $tx->user?->name ?? '',
                ]);
            }

            $row([]);
            $row(['Total operaciones', $data['total_count']]);
            $row(['Compras (casa compra divisa)', $data['buy_count']]);
            $row(['Ventas (casa vende divisa)', $data['sell_count']]);
            $row(['Volumen compras (BOB)', number_format((float) $data['buy_volume'], 2, '.', '')]);
            $row(['Volumen ventas (BOB)', number_format((float) $data['sell_volume'], 2, '.', '')]);

            fclose($out);
        }, $filename, [
            'Content-Type'  => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store',
        ]);
    }
}
