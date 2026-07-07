<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Dashboard: SUM(amount2) WHERE type=1 AND created_at >= X
            $table->index(['type', 'created_at'], 'idx_tx_type_created_at');

            // Top clientes del mes: WHERE MONTH(created_at)=X AND YEAR(created_at)=Y
            $table->index(['created_at', 'client_id'], 'idx_tx_created_client');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('idx_tx_type_created_at');
            $table->dropIndex('idx_tx_created_client');
        });
    }
};
