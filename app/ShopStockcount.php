<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopStockcount extends Model
{
    //
    protected $fillable=['stock_qty','shop_counting_unit_id','from_id'];

    public function shop_stockunit()
    {
        return $this->belongsTo(ShopCountingUnit::class,'shop_counting_unit_id');

    }
}
