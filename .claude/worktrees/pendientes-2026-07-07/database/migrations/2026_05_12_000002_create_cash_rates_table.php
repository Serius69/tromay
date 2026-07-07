<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_id')->constrained('cashes')->cascadeOnDelete();
            $table->decimal('buy',    15, 6);
            $table->decimal('sell',   15, 6);
            $table->decimal('oficial',15, 6)->nullable();
            $table->timestamps();

            $table->index(['cash_id', 'created_at'], 'idx_cash_rates_lookup');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_rates');
    }
};
