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

        $photos = [
            [
               'path'=>'dolar.jpg',
               'status'=>1,
            ],
            [
                'path'=>'noticia.jpg',
                'status'=>1,
             ]
        ];
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        User::factory(5)->create();
        Typelatest::factory(5)->create();
        //  Photo::factory(5)->create();

        foreach ($photos as $key => $photo) {
            Photo::create($photo);
        }
         Cash::factory(5)->create();
         Latest::factory(5)->create();
         Client::factory(5)->create();
         Quotation::factory(3)->create();
         Transaction::factory(3)->create();
    }
}
