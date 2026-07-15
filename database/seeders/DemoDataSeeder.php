<?php

namespace Database\Seeders;

use App\Models\Cash;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── Tasas referenciales bolivianas — SOLO fallback offline ─────────
        // La fuente autoritativa en producción es forex-erp (vía EXCHANGE_API_URL);
        // RateService::overlayForex() sobrepone las tasas en vivo sobre estos valores.
        // Este seed solo se muestra si forex-erp no responde, así que se mantiene
        // como un snapshot reciente (mercado paralelo, 2026-07-14) para que el
        // fallback nunca quede grotescamente desactualizado. Valores POR UNIDAD
        // (ARS/CLP ya divididos por su scale_factor de 1000 en forex-erp).
        $rates = [
            [
                'name'    => 'usd',
                'buy'     => 10.2750,
                'sell'    => 11.1000,
                'oficial' => 10.6875,
                'status'  => 1,
            ],
            [
                'name'    => 'eur',
                'buy'     => 11.4875,
                'sell'    => 11.6333,
                'oficial' => 11.5604,
                'status'  => 1,
            ],
            [
                'name'    => 'brl',
                'buy'     => 1.9688,
                'sell'    => 1.9728,
                'oficial' => 1.9708,
                'status'  => 1,
            ],
            [
                'name'    => 'ars',
                'buy'     => 0.006774,
                'sell'    => 0.006794,
                'oficial' => 0.006784,
                'status'  => 1,
            ],
            [
                'name'    => 'pen',
                'buy'     => 2.9536,
                'sell'    => 2.9596,
                'oficial' => 2.9566,
                'status'  => 1,
            ],
            [
                'name'    => 'clp',
                'buy'     => 0.011023,
                'sell'    => 0.011056,
                'oficial' => 0.011040,
                'status'  => 1,
            ],
        ];

        foreach ($rates as $rate) {
            Cash::updateOrCreate(
                ['name' => $rate['name']],
                $rate,
            );
        }

        $this->command->info('Tasas demo creadas: ' . count($rates) . ' divisas (USD/EUR/BRL/ARS/PEN/CLP).');

        // El usuario administrador demo con password fija se eliminó: la vitrina
        // pública ya no tiene auth/login (refactor 2026-07-10), así que era una
        // cuenta muerta con credenciales sembradas. Ver auditoría 07, §otros.
    }
}
