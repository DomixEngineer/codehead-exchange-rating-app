<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * @OA\Info(title="Exchange Rates service", version="0.1")
 */
class CurrencyService
{
    protected $currentCurrency = 'PLN';
    protected $againstCurrency = 'USD';
    protected $startPeriod = '';
    protected $endPeriod = '';
    protected $fileFormat = 'jsondata';
    protected $availableFormats = ['csvdata', 'jsondata', 'structurespecificdata', 'genericdata'];
    protected $apiUrl = 'https://sdw-wsrest.ecb.europa.eu/service';
    protected $apiParams = '';
    protected $availableCurrencies = [
        'USD',
        'JPY',
        'BGN',
        'CZK',
        'DKK',
        'GBP',
        'HUF',
        'PLN',
        'RON',
        'SEK',
        'CHF',
        'ISK',
        'NOK',
        'HRK',
        'RUB',
        'TRY',
        'AUD',
        'BRL',
        'CAD',
        'CNY',
        'HKD',
        'IDR',
        'ILS',
        'INR',
        'KRW',
        'MXN',
        'MYR',
        'NZD',
        'PHP',
        'SGD',
        'THB',
        'ZAR',
        'EUR'
    ];
    protected $currenciesUrl = '';

    /**
     * setter for current currency
     */
    public function setCurrentCurrency($currency)
    {
        $currency = strtoupper($currency);
        if ($currency === null || !$this->checkIfCurrencyIsValid($currency))
        {
            return response('Please provide a valid currency', 422);
        }
        $this->currentCurrency = $currency;
    }

    /**
     * setter for against currency
     */
    public function setAgainstCurrency($currency)
    {
        $currency = strtoupper($currency);
        if ($currency === null || !$this->checkIfCurrencyIsValid($currency))
        {
            return response('Please provide a valid currency', 422);
        }
        $this->againstCurrency = $currency;
    }

    /**
     * setter for start period
     */
    public function setStartPeriod($startPeriod)
    {
        $this->startPeriod = $startPeriod;
    }

    /**
     * setter for end period
     */
    public function setEndPeriod($endPeriod)
    {
        $this->endPeriod = $endPeriod;
    }

    /**
     * setter for file format
     */
    public function setFileFormat($fileFormat)
    {
        if (in_array($fileFormat, $availableFormats))
        {
            $this->fileFormat = $fileFormat;
        }
    }

    /**
     * Method for settings API parameters in URL
     */
    public function setApiParams()
    {
        $url = '?';
        if (!empty($this->getStartPeriod()))
        {
            $url .= "&startPeriod=".$this->getStartPeriod();
        }
        if (!empty($this->getEndPeriod()))
        {
            $url .= "&endPeriod=".$this->getEndPeriod();
        }
        if (!empty($this->getFileFormat()))
        {
            $url .= "&format=".$this->getFileFormat();
        }
        $this->apiParams .= $url;
    }

    /**
     * Getter for current currency
     */
    public function getCurrenctCurrency()
    {
        return $this->currentCurrency;
    }

    /**
     * Getter for against currency
     */
    public function getAgainstCurrency()
    {
        return $this->againstCurrency;
    }

    /**
     * Get available currencies
     */
    public function getAvailableCurrencies()
    {
        return $this->availableCurrencies;
    }

    /**
     * Get URL for currencies which was created by UrlBuilder method
     */
    public function getCurrenciesUrl()
    {
        return $this->currenciesUrl;
    }

    /**
     * Getter for start period
     */
    public function getStartPeriod()
    {
        return $this->startPeriod;
    }

    /**
     * Getter for end period
     */
    public function getEndPeriod()
    {
        return $this->endPeriod;
    }

    /**
     * Getter for file format
     */
    public function getFileFormat()
    {
        return $this->fileFormat;
    }

    /**
     * Getter for API url
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * Getter for API parameters
     */
    public function getApiParams()
    {
        return $this->apiParams;
    }

    /**
     * URL builder when using two currencies (current measured and against)
     */
    public function urlCurrenciesBuilder()
    {
        $url = 'D.'.$this->currentCurrency.'.'.$this->againstCurrency.'.SP00.A';
        $this->currenciesUrl = $url;
    }

    /**
     * URL builder when using only one currency (current measured)
     */
    public function urlWildcardBuilder()
    {
        $url = 'D..'.$this->getCurrenctCurrency().'.SP00.A';
        $this->currenciesUrl = $url;
    }

    public function checkIfCurrencyIsValid($currency)
    {
        if (!in_array($currency, $this->getAvailableCurrencies()))
        {
            return false;
        }
        return true;
    }

    /**
     * @OA\Get(
     *      path="/api/get-two-years-currencies-history",
     *      summary="Get two years history",
     *      description="Get two years history",
     *      @OA\Parameter(
     *          name="currentCurrency",
     *          description="Current currency",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="currentCurrency",
     *          description="Against currency",
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
    public function getLastTwoYearsData()
    {
        $today = Carbon::now()->format('Y-m-d');
        $twoYearsAgo = Carbon::now()->subYears(2)->format('Y-m-d');
        $url = $this->apiUrl . '/data/EXR/' . $this->currenciesUrl . "?startPeriod=$twoYearsAgo&endPeriod=$today";
        $data = Http::get($url);
        return $data;
    }

    /**
     * @OA\Get(
     *      path="/api/get-currencies-by-date-range",
     *      summary="Get currencies by date range",
     *      description="Get currencies by date range",
     *      @OA\Parameter(
     *          name="currentCurrency",
     *          description="Current currency",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="againstCurrency",
     *          description="against currency",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="startPeriod",
     *          description="start period date",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="date"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="endPeriod",
     *          description="end period date",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="date"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *      )
     * )
     */
    public function getCurrenciesDataByDateRange()
    {
        $url = $this->apiUrl . '/data/EXR/' . $this->currenciesUrl . $this->getApiParams();
        $data = Http::get($url);
        return $data;
    }
}
