<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\CurrencyService;

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

    /**
     * @OA\Get(
     *      path="/api/get-newest-data",
     *      summary="Get newest data",
     *      description="Get newest data",
     *      @OA\Parameter(
     *          name="currentCurrency",
     *          description="Current currency",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(
     *          response=422, 
     *          description="Bad currency"
     *      ),
     *     )
     *
     *
     */
    public function getTodayNewData(Request $request = null, $staticCurrency = null)
    {
        $currency = '';
        if (!$request) {
            $currency = $staticCurrency;
        }
        $currencyService = new CurrencyService();
        $currencyService->setCurrentCurrency($currency);
        $currencyService->urlWildcardBuilder();
        $currencyService->setStartPeriod(Carbon::now()->format('Y-m-d'));
        $currencyService->setApiParams();
        return $currencyService->getCurrenciesDataByDateRange();
    }
}
