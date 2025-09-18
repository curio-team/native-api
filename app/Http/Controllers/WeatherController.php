<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public $countries = [
        'NL',
    ];

    public $cities = [
        'NL' => [
            'Amsterdam',
            'Rotterdam',
            'Den Haag',
            'Utrecht',
            'Eindhoven',
            'Tilburg',
            'Groningen',
            'Breda',
            'Apeldoorn',
            'Nijmegen',
            'Enschede',
            'Leeuwarden',
            'Zwolle',
            'Arnhem',
            'Maastricht',
            'Amersfoort',
            'Haarlem',
            'Leiden',
            'Delft',
            'Zwijndrecht',
            'Harderwijk',
            'Almere',
            'Heerlen',
            'Venlo',
            'Hilversum',
            'Zaanstad',
            'Roosendaal',
            'Dordrecht',
            'Gouda',
            'Zoetermeer',
            'Emmen',
            'Veenendaal',
            'Weesp',
            'Helmond'
        ]
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

        if (!in_array($city, $this->cities[$country])) {
            return response()
                ->json([
                    'error' => 'Invalid input',
                    'details' => 'City not found'
                ], 404);
        }

        return response()
            ->json([
                'country' => $country,
                'city' => $city,
                'Temp' => rand(-10, 35),
                'RainChance' => rand(0, 100)
            ]);
    }
}
