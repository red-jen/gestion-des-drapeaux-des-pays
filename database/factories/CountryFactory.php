<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Country::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->country(),
            'capital' => $this->faker->city(),
            'population' => $this->faker->numberBetween(100000, 1000000000),
            'region' => $this->faker->randomElement(['Europe', 'Asia', 'Africa', 'North America', 'South America', 'Oceania']),
            'flag_url' => $this->faker->imageUrl(640, 480, 'flag', true),
            'currency' => $this->faker->currencyCode(),
            'language' => $this->faker->languageCode(),
        ];
    }
}