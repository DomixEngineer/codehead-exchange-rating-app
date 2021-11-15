# Exchange Rates API wrapper

Exchange Rates Api wrapper is just a small demonstration of how works europa bank exchange api, this wrapper contains some methods for setting up API parameters like startPeriod / endPeriod, also possible to modify response data format (available jsondata, csvdata etc..).
The whole logic is stored in CurrencyService which was created by Dominik Dziewulski for recruitment task.
Also it is good to mention that whole wrapper is based on design pattern called builder, we can notice this in ExchangeRatesController where we create new instance of CurrencyService and then we are adding some additional data to our instance like api parameters, current currency and against currency etc..

## Installation

To run docker environment, just run docker-compose file and it will build a whole environment which is build from mariadb and laravel images.

```bash
docker-compose -f laravel.yml up
```

## OpenApi Generation

To generate OpenApi documentation (called also as swagger), run command in CLI:
```bash
php artisan l5-swagger:generate
```

