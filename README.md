## Requirements

1. PHP >= 8.2
2. Redis >= 14.11
3. Composer >= 2.7.1
4. SqlLite >= 3.46.0

## Installation

```sh
composer install
cp .env.example .env
php artisan key:generate
```

## Run

```sh
php artisan serve
```

## Run Tests

run following command in main folder
```sh
vendor/bin/phpunit
```


## APIs

1. _Order -> Report Delay_

   * POST: <localhost:8000/api/orders/{order_id}/delay-report>
    
2. _Agent -> Assign Delay Report_

    * POST: <localhost:8000/api/agents/{agent_id}/assign-delay-report>

3. _Delay Report -> Last Week Delay Report Analyse_
    
    * GET: <localhost:8000/api/delay-report/analyse>
