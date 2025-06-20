<?php

namespace Database\Factories;

use App\Models\Merek; // Ensure you import your Merek model
use Illuminate\Database\Eloquent\Factories\Factory;

class MerekFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Merek::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Define some common brand names
        $merekNames = ['Toyota', 'Honda', 'Suzuki', 'Mitsubishi', 'Daihatsu', 'Hino', 'Isuzu', 'Nissan', 'Mazda'];
        return [
            // Use faker to pick a unique random element from the list
            'nama_merek' => $this->faker->unique()->randomElement($merekNames),
        ];
    }
}
