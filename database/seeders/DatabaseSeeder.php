<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Mine;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

         User::factory()->create([
             'username' => 'amottier',
             'email' => 'alex_mottier@hotmail.com',
             'type' => 'administrator',
             'status' => 'validated',
             'validated_at' => now()
         ]);

         Mine::factory(10)->create();
    }
}
