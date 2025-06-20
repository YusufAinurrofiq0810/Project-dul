<?php

namespace Database\Seeders;

use App\Models\JenisAngkutan;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisAngkutansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisAngkutan::factory()->create([
            'Nama_Jenis_Angkutan' => 'AKDP',
        ]);
        JenisAngkutan::factory()->create([
            'Nama_Jenis_Angkutan' => 'AKAP',
        ]);
        JenisAngkutan::factory()->create([
            'Nama_Jenis_Angkutan' => 'Bus Pariwisata',
        ]);
        JenisAngkutan::factory()->create([
            'Nama_Jenis_Angkutan' => 'Angkutan Barang',
        ]);
        JenisAngkutan::factory()->create([
            'Nama_Jenis_Angkutan' => 'Angkutan Desa',
        ]);
    }
}
