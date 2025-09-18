<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/weer/{country}/{city}', [App\Http\Controllers\WeatherController::class, 'getWeather'])
    ->name('weather.index');

Route::get('/currencyconverter/{from}/{to}/{amount}', [App\Http\Controllers\CurrencyController::class, 'getCurrency'])
    ->name('currency.index');

Route::get('/currencyconverter', [App\Http\Controllers\CurrencyController::class, 'getCurrencies'])
    ->name('currency');

Route::get('/quote', [App\Http\Controllers\QuoteController::class, 'getQuote'])
    ->name('quote.index');
