<?php

namespace Database\Seeders;

use App\Models\Perusahaan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PerusahaansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Perusahaan::factory()->create([
            'nama_perusahaan' => 'PT. Angkutan Bersama',
            // 'alamat' => 'Jl. Raya No. 1, Jakarta',
        ]);
    }
}
