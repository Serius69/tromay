<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cash;
use App\Models\Latest;
use App\Models\User;
use App\Models\Quotation;
use App\Models\Photo;
use App\Models\Transaction;
use App\Models\Client;use App\Models\Typelatest;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(5)->create();
         Cash::factory(5)->create();
         Latest::factory(5)->create();
         Client::factory(5)->create();
         Quotation::factory(3)->create();
         Transaction::factory(3)->create();
    }
}
