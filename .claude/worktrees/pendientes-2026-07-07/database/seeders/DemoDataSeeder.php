<?php

namespace Database\Seeders;

use App\Models\Cash;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── Tasas referenciales bolivianas (actualizar según mercado) ──────
        $rates = [
            [
                'name'    => 'usd',
                'buy'     => 6.92,
                'sell'    => 7.10,
                'oficial' => 6.86,
                'status'  => 1,
            ],
            [
                'name'    => 'eur',
                'buy'     => 7.50,
                'sell'    => 7.72,
                'oficial' => 7.42,
                'status'  => 1,
            ],
            [
                'name'    => 'brl',
                'buy'     => 1.28,
                'sell'    => 1.38,
                'oficial' => 1.25,
                'status'  => 1,
            ],
            [
                'name'    => 'ars',
                'buy'     => 0.0072,
                'sell'    => 0.0080,
                'oficial' => 0.0068,
                'status'  => 1,
            ],
            [
                'name'    => 'pen',
                'buy'     => 1.82,
                'sell'    => 1.95,
                'oficial' => 1.79,
                'status'  => 1,
            ],
            [
                'name'    => 'clp',
                'buy'     => 0.0072,
                'sell'    => 0.0082,
                'oficial' => 0.0070,
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

        // ── Usuario administrador demo ─────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin.demo@kapitalya.com.bo'],
            [
                'name'     => 'Administrador Demo — Tromay',
                'email'    => 'admin.demo@kapitalya.com.bo',
                'password' => Hash::make('Demo2026!'),
            ],
        );

        $this->command->info('Usuario demo: admin.demo@kapitalya.com.bo / Demo2026!');
        $this->command->warn('IMPORTANTE: Cambiar contraseña del usuario demo antes de dar acceso a clientes reales.');
    }
}
