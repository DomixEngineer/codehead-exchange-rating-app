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
     * @OA\Post(
     *      path="/api/set-current-currency",
     *      summary="Sets current currency",
     *      description="Sets current currency",
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
     * Sets current currency
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

    public function setAgainstCurrency($currency)
    {
        $currency = strtoupper($currency);
        if ($currency === null || !$this->checkIfCurrencyIsValid($currency))
        {
            return response('Please provide a valid currency', 422);
        }
        $this->againstCurrency = $currency;
    }

    public function setStartPeriod($startPeriod)
    {
        $this->startPeriod = $startPeriod;
    }

    public function setEndPeriod($endPeriod)
    {
        $this->endPeriod = $endPeriod;
    }

    public function setFileFormat($fileFormat)
    {
        if (in_array($fileFormat, $availableFormats))
        {
            $this->fileFormat = $fileFormat;
        }
    }

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
     * @OA\Get(
     *      path="/api/get-current-currency",
     *      summary="Get current currency",
     *      description="Get current currency",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=400, description="Bad request"),
     *       security={
     *           {"api_key_security_example": {}}
     *       }
     *     )
     *
     * Get current currency
     */
    public function getCurrenctCurrency()
    {
        return $this->currentCurrency;
    }

    public function getAgainstCurrency()
    {
        return $this->againstCurrency;
    }

    public function getAvailableCurrencies()
    {
        return $this->availableCurrencies;
    }

    public function getCurrenciesUrl()
    {
        return $this->currenciesUrl;
    }

    public function getStartPeriod()
    {
        return $this->startPeriod;
    }

    public function getEndPeriod()
    {
        return $this->endPeriod;
    }

    public function getFileFormat()
    {
        return $this->fileFormat;
    }

    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    public function getApiParams()
    {
        return $this->apiParams;
    }

    public function urlCurrenciesBuilder()
    {
        $url = 'D.'.$this->currentCurrency.'.'.$this->againstCurrency.'.SP00.A';
        $this->currenciesUrl = $url;
    }

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

    public function getLastTwoYearsData()
    {
        $today = Carbon::now()->format('Y-m-d');
        $twoYearsAgo = Carbon::now()->subYears(2)->format('Y-m-d');
        $url = $this->apiUrl . '/data/EXR/' . $this->currenciesUrl . "?startPeriod=$twoYearsAgo&endPeriod=$today";
        $data = Http::get($url);
        return $data;
    }

    public function getCurrenciesDataByDateRange()
    {
        $url = $this->apiUrl . '/data/EXR/' . $this->currenciesUrl . $this->getApiParams();
        $data = Http::get($url);
        return $data;
    }
}
