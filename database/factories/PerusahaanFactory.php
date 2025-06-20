<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Perusahaan>
 */
class PerusahaanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $perusahaanNames = ['PT. Angkutan Bersama', 'CV. Transportasi Maju', 'UD. Layanan Angkutan', 'PT. Jasa Angkutan Indonesia', 'PT. Angkutan Sejahtera', 'CV. Angkutan Mandiri', 'PT. Angkutan Utama', 'PT. Angkutan Bersatu'];
        return [
            // Use faker to pick a unique random element from the list
            'nama_perusahaan' => $this->faker->unique()->randomElement($perusahaanNames),
        ];
    }
}
