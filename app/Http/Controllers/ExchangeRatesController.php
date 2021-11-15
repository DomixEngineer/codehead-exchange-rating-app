<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\CurrencyService;

/**
 * @OA\Info(title="Exchange Rates controller", version="0.1")
 */
class ExchangeRatesController extends Controller
{
    public function getCurrenciesTwoYearsHistory(Request $request)
    {
        $currencyService = new CurrencyService();
        $currencyService->setCurrentCurrency($request->input('currentCurrency'));
        $currencyService->setAgainstCurrency($request->input('againstCurrency'));
        $currencyService->urlCurrenciesBuilder();
        return $currencyService->getLastTwoYearsData();
    }

    public function getCurrenciesDataByDateRange(Request $request)
    {
        $currencyService = new CurrencyService();
        $currencyService->setCurrentCurrency($request->input('currentCurrency'));
        $currencyService->setAgainstCurrency($request->input('againstCurrency'));
        $currencyService->urlCurrenciesBuilder();
        $currencyService->setStartPeriod($request->input('startPeriod'));
        $currencyService->setEndPeriod($request->input('endPeriod'));
        $currencyService->setApiParams();
        return $currencyService->getCurrenciesDataByDateRange();
    }

    public function getTodayNewData(Request $request)
    {
        $currencyService = new CurrencyService();
        $currencyService->setCurrentCurrency($request->input('currentCurrency'));
        $currencyService->urlWildcardBuilder();
        $currencyService->setStartPeriod(Carbon::now()->format('Y-m-d'));
        $currencyService->setApiParams();
        return $currencyService->getCurrenciesDataByDateRange();
    }
}
