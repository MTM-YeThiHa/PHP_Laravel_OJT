<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::table('users')->insert(
            [
              'name' => 'admin',
              'email' => 'scm.yth.yethiha@gmail.com',
              'password' => Hash::make('password'),
              'profile' => '1588646773.png',
              'type' => '0',
              'created_user_id' => 1,
              'updated_user_id' => 1,
            ]
          );
    }
}
