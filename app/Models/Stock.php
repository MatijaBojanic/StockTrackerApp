<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    protected $casts =[
        'in_stock' => 'boolean'
    ];
    use HasFactory;

    public function track(){
        if($this->retailer->name == 'Best Buy') {
            $results = \Http::get('http://foo.test')->json();

            $this->update([
               'in_stock' => $results['available'],
                'price' => $results['price'],
            ]);
        }
    }

    public function retailer(){
        return $this->belongsTo(Retailer::class);
    }

}
