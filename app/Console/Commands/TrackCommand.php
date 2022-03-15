<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class TrackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'track';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Track all product stock';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $products = Product::all()->tap(fn($products) => $this->output->progressStart($products->count()))
        ->each(function ($product){
            $product->track();
            $this->output->progressAdvance();
        });

        $this->showResults();
    }

    /**
     * @return void
     */
    protected function showResults(): void
    {
        $this->output->progressFinish();

        $data = Product::query()
            ->leftJoin('stocks', 'stocks.product_id', '=', 'product_id')
            ->get($this->keys());

        $this->table(array_map('ucwords', $this->keys()),
            $data
        );
    }

    /**
     * @return string[]
     */
    protected function keys(): array
    {
        return ['name', 'price', 'url', 'in_stock'];
    }
}
