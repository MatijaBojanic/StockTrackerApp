<?php

namespace App\UseCases;

use App\Clients\StockStatus;
use App\Events\NowInStock;
use App\Models\History;
use App\Models\Stock;
use App\Models\User;
use App\Notifications\ImportantStockUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TrackStock implements ShouldQueue
{
    use Dispatchable;

    protected Stock $stock;
    protected StockStatus $stockStatus;

    /**
     * @param Stock $stock
     */
    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
    }

    public function handle()
    {
        $this->checkAvailability();
        $this->notifyUser();
        $this->refreshStock();
        $this->recordToHistory();
    }


    protected function checkAvailability()
    {
        $this->stockStatus = $this->stock
            ->retailer
            ->client()
            ->checkAvailability($this->stock);
    }

    protected function notifyUser()
    {
        if ($this->isNowInStock()) {
            User::first()->notify(
                new ImportantStockUpdate($this->stock)
            );
        }
    }

    protected function refreshStock()
    {
        $this->stock->update([
            'in_stock' => $this->stockStatus->available,
            'price' => $this->stockStatus->price,
        ]);
    }

    protected function recordToHistory()
    {
        History::create([
            'price' => $this->stock->price,
            'in_stock' => $this->stock->in_stock,
            'stock_id' => $this->stock->id,
            'product_id' => $this->stock->product_id,
        ]);
    }

    /**
     * @return bool
     */
    protected function isNowInStock(): bool
    {
        return !$this->stock->in_stock && $this->stockStatus->available;
    }
}
