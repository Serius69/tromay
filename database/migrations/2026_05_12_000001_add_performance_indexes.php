<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // transactions: FK columns + date filters used in dashboard queries
        Schema::table('transactions', function (Blueprint $table) {
            $table->index('client_id',   'idx_tx_client');
            $table->index('user_id',     'idx_tx_user');
            $table->index('cash1_id',    'idx_tx_cash1');
            $table->index('cash2_id',    'idx_tx_cash2');
            $table->index('type',        'idx_tx_type');
            $table->index('created_at',  'idx_tx_created_at');
            $table->index('date',        'idx_tx_date');
        });

        // cashes: status is used in every public query
        Schema::table('cashes', function (Blueprint $table) {
            $table->index('status', 'idx_cashes_status');
        });

        // clients: ci is used for duplicate detection and search
        Schema::table('clients', function (Blueprint $table) {
            $table->index('ci',         'idx_clients_ci');
            $table->index('created_at', 'idx_clients_created_at');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('idx_tx_client');
            $table->dropIndex('idx_tx_user');
            $table->dropIndex('idx_tx_cash1');
            $table->dropIndex('idx_tx_cash2');
            $table->dropIndex('idx_tx_type');
            $table->dropIndex('idx_tx_created_at');
            $table->dropIndex('idx_tx_date');
        });

        Schema::table('cashes', function (Blueprint $table) {
            $table->dropIndex('idx_cashes_status');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex('idx_clients_ci');
            $table->dropIndex('idx_clients_created_at');
        });
    }
};
