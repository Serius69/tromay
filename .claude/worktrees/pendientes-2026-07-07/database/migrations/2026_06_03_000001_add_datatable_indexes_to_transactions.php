<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Índices específicos para DataTables server-side con 100k+ registros.
 *
 * - idx_tx_created_tipo: ordena/filtra por fecha y tipo (columna más usada en datatable)
 * - idx_tx_client_created: historial de cliente ordenado por fecha
 * - idx_tx_date: búsqueda por rango de fecha (filtro del panel admin)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Cubre ORDER BY date + filtros de tipo (columna default del datatable)
            if (!$this->indexExists('transactions', 'idx_dt_created_at_tipo')) {
                $table->index(['created_at', 'type'], 'idx_dt_created_at_tipo');
            }

            // Cubre queries por cliente ordenadas por fecha
            if (!$this->indexExists('transactions', 'idx_dt_client_created_at')) {
                $table->index(['client_id', 'created_at'], 'idx_dt_client_created_at');
            }

            // Cubre filtros de rango de fecha (date column)
            if (!$this->indexExists('transactions', 'idx_dt_date')) {
                $table->index('date', 'idx_dt_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            foreach (['idx_dt_created_at_tipo', 'idx_dt_client_created_at', 'idx_dt_date'] as $index) {
                if ($this->indexExists('transactions', $index)) {
                    $table->dropIndex($index);
                }
            }
        });
    }

    private function indexExists(string $table, string $index): bool
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            $indexes = \Illuminate\Support\Facades\DB::select(
                "SELECT name FROM sqlite_master WHERE type = 'index' AND name = ?",
                [$index]
            );
        } else {
            $indexes = \Illuminate\Support\Facades\DB::select(
                "SHOW INDEX FROM `{$table}` WHERE Key_name = ?",
                [$index]
            );
        }

        return !empty($indexes);
    }
};
