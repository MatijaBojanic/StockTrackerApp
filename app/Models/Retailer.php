<?php

namespace App\Models;

use Facades\App\Clients\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Retailer
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\RetailerFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Retailer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Retailer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Retailer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Retailer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Retailer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Retailer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Retailer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Retailer extends Model
{
    use HasFactory;
    public function addStock(Product $product, Stock $stock){
        $stock->product_id = $product->id;
        $this->stock()->save($stock);
    }
    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
    public function client()
    {
        return ClientFactory::make($this);
    }
}
