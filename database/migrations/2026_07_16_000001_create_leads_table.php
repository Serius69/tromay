<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Captura de leads de email para alertas de tasas.
     *
     * El público que mira las tasas de la vitrina es el target perfecto de
     * "avisame cuando el dólar llegue a mi precio" (venta cruzada a
     * alertas.kapitalya.com.bo). Esta tabla solo persiste el contacto; el
     * envío/registro real ocurre en el servicio de alertas.
     *
     * Aditiva y segura: no toca ninguna tabla existente.
     */
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('currency', 3)->nullable();   // divisa de interés (usd, eur, brl, ars, pen, clp)
            $table->string('source', 32)->nullable();     // p.ej. 'home', 'quote'
            $table->string('ip', 45)->nullable();         // IPv4/IPv6
            $table->timestamp('created_at')->nullable();

            $table->index('email', 'idx_leads_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
