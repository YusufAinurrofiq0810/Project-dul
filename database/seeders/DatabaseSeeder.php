<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => bcrypt('123'), // Ensure to hash the password
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        $this->call(JenisAngkutansTableSeeder::class);
    }
}
