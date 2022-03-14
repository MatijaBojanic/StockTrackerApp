<?php

namespace App\Models;

use App\Clients\BestBuy;
use App\Clients\ClientException;
use App\Clients\ClientFactory;
use App\Clients\Target;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\Stock
 *
 * @method static \Database\Factories\StockFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stock query()
 * @mixin \Eloquent
 */
class Stock extends Model
{
    protected $casts = [
        'in_stock' => 'boolean'
    ];
    use HasFactory;

    public function track()
    {
        $stockStatus = (new ClientFactory())->make($this->retailer)
            ->checkAvailability($this);

        $this->update([
            'in_stock' => $stockStatus->available,
            'price' => $stockStatus->price,
        ]);
    }

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }

}
