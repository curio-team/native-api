<?php

namespace App\Http\Controllers;

use App\Support\TimeSeededRandom;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public $currencies = [
        'EUR' => [
            'USD' => 1.25,
            'GBP' => 0.85,
            'CHF' => 1.1,
            'CAD' => 1.35,
            'AUD' => 1.55,
            'NZD' => 1.65,
            'JPY' => 130,
            'SEK' => 10,
            'DKK' => 7.5,
            'NOK' => 9,
            'PLN' => 4.25,
            'ZAR' => 15,
            'HUF' => 300,
            'CZK' => 25,
            'ILS' => 3.75,
            'RON' => 4.5,
            'HRK' => 6.5,
            'RUB' => 70,
            'TRY' => 6.5,
            'UAH' => 30,
            'MXN' => 21,
            'BRL' => 4.5,
            'ARS' => 10,
            'CLP' => 750,
            'COP' => 250,
            'PEN' => 250,
            'PHP' => 500,
            'MYR' => 4.5,
            'SGD' => 1.5,
            'THB' => 30,
            'IDR' => 13000,
        ],
    ];

    public function getCurrencies()
    {
        $this->generateReverseRates();

        $availableCurrencies = array_keys($this->currencies);

        // For each currency, get what it can convert to
        $currencyPairs = [];
        foreach ($this->currencies as $from => $toCurrencies) {
            $currencyPairs[$from] = array_keys($toCurrencies);
        }

        return response()->json([
            'available_currencies' => $availableCurrencies,
            'conversion_pairs' => $currencyPairs,
            'total_currencies' => count($availableCurrencies)
        ]);
    }

    public function generateReverseRates($base = 'EUR')
    {
        $currencies = $this->currencies;

        foreach ($currencies[$base] as $to => $rate) {
            if (!isset($this->currencies[$to])) {
                $this->currencies[$to][$base] = round(1 / $rate, 6);
                $this->generateReverseRates($to);
            }
        }
    }

    public function getCurrency($from, $to, $amount)
    {
        $this->generateReverseRates();

        if (!isset($this->currencies[$from])) {
            return response()
                ->json([
                    'error' => 'Invalid input',
                    'details' => 'From currency not found'
                ], 404);
        }

        if (!isset($this->currencies[$from][$to])) {
            return response()
                ->json([
                    'error' => 'Invalid input',
                    'details' => 'To currency not found'
                ], 404);
        }

        $drift = TimeSeededRandom::smooth('fx:'.$from.':'.$to, 3 * 3600, 0.97, 1.03);
        $rate = round($this->currencies[$from][$to] * $drift, 6);

        return response()
            ->json([
                'from' => $from,
                'to' => $to,
                'amount' => $amount,
                'rate' => $rate,
                'calculated' => round($amount * $rate, 2)
            ]);
    }
}
