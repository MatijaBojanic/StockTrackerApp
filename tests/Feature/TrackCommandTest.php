<?php

namespace Tests\Feature;

use App\Clients\StockStatus;
use App\Notifications\ImportantStockUpdate;
use Facades\App\Clients\ClientFactory;
use App\Models\Product;
use App\Models\Retailer;
use App\Models\Stock;
use App\Models\User;
use Database\Seeders\RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TrackCommandTest extends TestCase
{
    use RefreshDatabase;

    protected  function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        $this->seed(RetailerWithProductSeeder::class);
    }
    /** @test * */
    public function it_tracks_product_stock()
    {
        $this->assertFalse(Product::first()->inStock());

        TestCase::mockClientRequest();

        $this->artisan('track');

        $this->assertTrue(Product::first()->inStock());
    }
}
