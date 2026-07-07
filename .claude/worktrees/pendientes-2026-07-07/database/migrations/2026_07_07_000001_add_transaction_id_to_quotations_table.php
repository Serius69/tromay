<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Enlaza la cotización con la transacción generada al convertirla.
 * `status` pasa a admitir: 0=anulada, 1=vigente, 2=convertida.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            if (! Schema::hasColumn('quotations', 'transaction_id')) {
                $table->foreignId('transaction_id')
                    ->nullable()
                    ->after('status')
                    ->constrained('transactions')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            if (Schema::hasColumn('quotations', 'transaction_id')) {
                $table->dropConstrainedForeignId('transaction_id');
            }
        });
    }
};
