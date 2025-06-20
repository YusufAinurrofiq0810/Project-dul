<?php

namespace Database\Factories;

use App\Models\JenisAngkutan;
use Illuminate\Database\Eloquent\Factories\Factory;

class JenisAngkutanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JenisAngkutan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // You'll need to define how you want to generate dummy data for Nama_Jenis_Angkutan
        // For simple random names:
        $jenisAngkutanNames = ['Bus Pariwisata', 'Bus AKAP', 'Angkutan Kota (Angkot)', 'Taksi', 'Truk Barang', 'Mobil Pribadi', 'Minibus', 'Pickup'];
        return [
            'Nama_Jenis_Angkutan' => $this->faker->unique()->randomElement($jenisAngkutanNames),
            // Add other fields if your JenisAngkutan model has them
        ];
    }
}
