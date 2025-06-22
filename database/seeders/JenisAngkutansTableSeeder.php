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
        $data = [
            ['Nama_Jenis_Angkutan' => 'AKDP'],
            ['Nama_Jenis_Angkutan' => 'AKAP'],
            ['Nama_Jenis_Angkutan' => 'Bus Pariwisata'],
            ['Nama_Jenis_Angkutan' => 'Angkutan Barang'],
            ['Nama_Jenis_Angkutan' => 'Angkutan Desa'],
        ];

        foreach ($data as $row) {
            JenisAngkutan::firstOrCreate($row);
        }
    }
}
