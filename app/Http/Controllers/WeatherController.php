<?php

namespace App\Http\Controllers;

use App\Support\TimeSeededRandom;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public $countries = [
        'NL',
    ];

    public function getWeather($country, $city)
    {
        if (!in_array($country, $this->countries)) {
            return response()
                ->json([
                    'error' => 'Invalid input',
                    'details' => 'Country not found'
                ], 404);
        }

        if (!preg_match('/^[a-zA-Z\s\-]{2,50}$/', $city)) {
            return response()
                ->json([
                    'error' => 'Invalid input',
                    'details' => 'City must be 2-50 characters, letters, spaces and dashes only'
                ], 422);
        }

        $seasonalMid = TimeSeededRandom::smooth('weather:season:'.$city, 365 * 24 * 3600, 4, 20);
        $temp = TimeSeededRandom::smooth('weather:temp:'.$city, 24 * 3600, $seasonalMid - 8, $seasonalMid + 8);

        $rainChance = TimeSeededRandom::smooth('weather:rain:'.$city, 6 * 3600, 0, 100);

        return response()
            ->json([
                'country' => $country,
                'city' => $city,
                'Temp' => round($temp),
                'RainChance' => round($rainChance)
            ]);
    }
}
