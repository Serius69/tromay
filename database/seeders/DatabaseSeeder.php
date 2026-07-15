<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Latest;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Tromay es una vitrina pública: sembramos las divisas (fallback de tasas
     * cuando forex-erp no está disponible) y algunas noticias financieras.
     * No hay datos transaccionales (clientes/transacciones) por diseño.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DemoDataSeeder::class,
        ]);

        Latest::factory(5)->create();
    }
}
