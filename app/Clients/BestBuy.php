<?php

namespace App\Clients;

use App\Models\Stock;

class BestBuy implements Client
{
    public function checkAvailability(Stock $stock):StockStatus
    {
        $results = \Http::get($this->endpoint($stock->sku))->json();

        return new StockStatus(
            (boolean) $results['onlineAvailability'],
            (int) $results['salePrice'] * 100
        );
    }

    public function endpoint($sku)
    {
        $key = config('services.clients.bestBuy.key');
        return "https://api.bestbuy.com/v1/products/{$sku}.json?apiKey={$key}";
    }
}
