<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Dinero y tasas dejan de ser `double`: montos → decimal(15,4), tasas →
 * decimal(15,6), igual que las tablas nuevas (quotations, cash_rates).
 * El float acumula errores de redondeo en agregaciones (SUM del cierre diario).
 *
 * Solo MySQL: en sqlite (tests) los tipos son dinámicos y ALTER de tipo no
 * existe; las tablas de test se crean desde cero y no arrastran datos.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        // `oficial` nació nullable: normalizar antes del NOT NULL o el ALTER
        // falla con "Invalid use of NULL value" (sql_mode strict).
        DB::statement('UPDATE `cashes` SET `oficial` = 0 WHERE `oficial` IS NULL');

        DB::statement('ALTER TABLE `transactions` MODIFY `amount1` DECIMAL(15,4) NOT NULL, MODIFY `amount2` DECIMAL(15,4) NOT NULL');
        DB::statement('ALTER TABLE `cashes` MODIFY `buy` DECIMAL(15,6) NOT NULL, MODIFY `sell` DECIMAL(15,6) NOT NULL, MODIFY `oficial` DECIMAL(15,6) NOT NULL DEFAULT 0');
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE `transactions` MODIFY `amount1` DOUBLE NOT NULL, MODIFY `amount2` DOUBLE NOT NULL');
        DB::statement('ALTER TABLE `cashes` MODIFY `buy` DOUBLE NOT NULL, MODIFY `sell` DOUBLE NOT NULL, MODIFY `oficial` DOUBLE NOT NULL DEFAULT 0');
    }
};
