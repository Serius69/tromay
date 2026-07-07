<?php

namespace Database\Seeders;

use App\Models\Cash;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Genera 100,000 transacciones para pruebas de performance del DataTable.
 * Usa inserts por lotes de 1,000 filas para evitar memory overflow.
 *
 * Uso: php artisan db:seed --class=TransactionPerformanceSeeder
 */
class TransactionPerformanceSeeder extends Seeder
{
    private const TOTAL      = 100_000;
    private const CHUNK_SIZE = 1_000;

    public function run(): void
    {
        // Crear entidades de soporte si no existen
        $user    = User::first()    ?? User::factory()->create();
        $clients = Client::limit(50)->pluck('id')->toArray();
        if (empty($clients)) {
            Client::factory(50)->create();
            $clients = Client::limit(50)->pluck('id')->toArray();
        }
        $cashes = Cash::limit(10)->pluck('id')->toArray();
        if (empty($cashes)) {
            Cash::factory(10)->create();
            $cashes = Cash::limit(10)->pluck('id')->toArray();
        }

        $userId    = $user->id;
        $now       = now();
        $inserted  = 0;
        $batchNum  = 0;

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::beginTransaction();

        try {
            while ($inserted < self::TOTAL) {
                $chunk = min(self::CHUNK_SIZE, self::TOTAL - $inserted);
                $rows  = [];

                for ($i = 0; $i < $chunk; $i++) {
                    $cash1 = $cashes[array_rand($cashes)];
                    $cash2 = $cashes[array_rand($cashes)];
                    $type  = rand(1, 2);
                    $amount1 = round(mt_rand(100, 100000) / 100, 2);
                    $amount2 = round($amount1 * (mt_rand(690, 710) / 100), 2);

                    // Distribuir fechas en los últimos 2 años
                    $daysAgo = rand(0, 730);
                    $date    = $now->copy()->subDays($daysAgo)->format('Y-m-d H:i:s');

                    $rows[] = [
                        'user_id'    => $userId,
                        'client_id'  => $clients[array_rand($clients)],
                        'cash1_id'   => $cash1,
                        'cash2_id'   => $cash2,
                        'type'       => $type,
                        'amount1'    => $amount1,
                        'amount2'    => $amount2,
                        'date'       => $date,
                        'status'     => 1,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ];
                }

                DB::table('transactions')->insert($rows);
                $inserted += $chunk;
                $batchNum++;

                if ($batchNum % 20 === 0) {
                    DB::commit();
                    DB::beginTransaction();
                    $this->command->info("  Insertadas {$inserted} de " . self::TOTAL . " transacciones...");
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $this->command->info("✓ TransactionPerformanceSeeder: " . self::TOTAL . " registros insertados.");
    }
}
