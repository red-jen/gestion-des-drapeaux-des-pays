<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            [
                'name' => 'France',
                'capital' => 'Paris',
                'population' => 67390000,
                'region' => 'Europe',
                'flag_url' => 'https://example.com/flags/france.png',
                'currency' => 'Euro',
                'language' => 'French'
            ],
            [
                'name' => 'Japan',
                'capital' => 'Tokyo',
                'population' => 126300000,
                'region' => 'Asia',
                'flag_url' => 'https://example.com/flags/japan.png',
                'currency' => 'Yen',
                'language' => 'Japanese'
            ],
            [
                'name' => 'Brazil',
                'capital' => 'Brasília',
                'population' => 212600000,
                'region' => 'South America',
                'flag_url' => 'https://example.com/flags/brazil.png',
                'currency' => 'Real',
                'language' => 'Portuguese'
            ],
        ];

        foreach ($countries as $countryData) {
            Country::create($countryData);
        }
    }
}