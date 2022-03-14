<?php

namespace Tests\Clients;

use App\Clients\BestBuy;
use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BestBuyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_tracks_a_product()
    {
        $this->seed(RetailerWithProductSeeder::class);
        $stock = tap(Stock::first())->update([
            'sku' => '6364253', // Nintendo Switch
            'url' => 'https://www.bestbuy.com/site/nintendo-switch-32gb-console-gray-joy-con/6364253.p?skuId=6364253'
        ]);
        try {
            $stockStatus = (new BestBuy())->checkAvailability($stock);
        } catch(\Exception $e){
            $this->fail('Failed to track the BestBuy Api properly'.$e->getMessage());
        }
        $this->assertTrue(true);
    }
    /** @test */
    function it_creates_the_proper_stock_status_response()
    {
        \Http::fake(fn()=> ['salePrice' => 299.99, 'onlineAvailability' => true]);
        $stockStatus = (new BestBuy())->checkAvailability(new Stock());

        $this->assertEquals(29999, $stockStatus->price);
        $this->assertTrue($stockStatus->available);
    }
}
