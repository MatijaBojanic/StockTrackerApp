<?php

namespace App\Models;

use App\Clients\BestBuy;
use App\Clients\ClientException;
use App\Events\NowInStock;
use App\UseCases\TrackStock;
use Database\Factories\StockFactory;
use Eloquent;
use Facades\App\Clients\ClientFactory;
use App\Clients\Target;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\Stock
 *
 * @method static StockFactory factory(...$parameters)
 * @method static Builder|Stock newModelQuery()
 * @method static Builder|Stock newQuery()
 * @method static Builder|Stock query()
 * @mixin Eloquent
 */
class Stock extends Model
{
    protected $casts = [
        'in_stock' => 'boolean'
    ];
    use HasFactory;

    public function track($callback = null)
    {
        TrackStock::dispatch($this);

    }

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
