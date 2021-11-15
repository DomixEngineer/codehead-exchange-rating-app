<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExchangeRatesController;

// Downloads data (based on two currencies, current measured and against, like PLN to EUR) from today to last 2 years
Route::get('/get-two-years-currencies-history', [ExchangeRatesController::class, 'getCurrenciesTwoYearsHistory']);

// Downloads data (based on two currencies, current measured and against, like PLN to EUR) between two dates
Route::get('/get-currencies-by-date-range', [ExchangeRatesController::class, 'getCurrenciesDataByDateRange']);

// Downloads data from today
Route::get('/get-newest-data', [ExchangeRatesController::class, 'getTodayNewData']);