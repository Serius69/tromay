<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class KapitalyaUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'kapitalya@kapitalya.com.bo'],
            [
                'name' => 'kapitalya',
                'password' => Hash::make(env('KAPITALYA_ADMIN_PASSWORD') ?: abort(500, 'KAPITALYA_ADMIN_PASSWORD env var es obligatoria.')),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Usuario kapitalya creado/actualizado en tromay.');
    }
}
