<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla de cotizaciones (proformas) de la casa de cambio.
     *
     * Una cotización guarda el cálculo de una operación de compra/venta de
     * divisa a la tasa vigente en el momento, con una fecha de validez. No
     * mueve caja (a diferencia de `transactions`); es una oferta al cliente.
     */
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('cash_id')->constrained('cashes')->cascadeOnDelete();
            $table->enum('type', ['buy', 'sell'])->default('buy'); // compra/venta desde la casa de cambio
            $table->decimal('amount', 15, 4);  // monto en divisa extranjera
            $table->decimal('rate',   15, 6);  // tasa aplicada (buy o sell del cash)
            $table->decimal('total',  15, 4);  // total resultante en BOB
            $table->date('valid_until')->nullable();
            $table->string('notes', 500)->nullable();
            $table->integer('status')->default(1); // 1=vigente, 0=anulada
            $table->timestamps();

            $table->index(['cash_id', 'created_at'], 'idx_quotations_lookup');
            $table->index(['status', 'valid_until'], 'idx_quotations_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
