<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * `leads.email` pasa de índice normal a UNIQUE.
 *
 * Antes, reenviar el formulario insertaba el mismo contacto tantas veces como
 * se quisiera (el throttle 5/min por IP no impide acumular): la tabla crecía
 * sin control y cualquier recuento de leads quedaba inflado. Con el UNIQUE, el
 * `firstOrCreate()` del LeadController es idempotente de verdad.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Defensivo: si ya hubiera duplicados previos, conservar el más antiguo
        // (el primer contacto real) antes de imponer la restricción.
        DB::statement('
            DELETE l1 FROM leads l1
            INNER JOIN leads l2
            WHERE l1.email = l2.email AND l1.id > l2.id
        ');

        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex('idx_leads_email');
            $table->unique('email', 'idx_leads_email_unique');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropUnique('idx_leads_email_unique');
            $table->index('email', 'idx_leads_email');
        });
    }
};
