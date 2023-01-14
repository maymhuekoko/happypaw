<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopBrand extends Model
{
    //
    protected $fillable = [

        'shop_brand_code','name','shop_category_id','shop_sub_category_id'

    ];
    public function shop_category() {
        return $this->belongsTo('App\ShopCategory','shop_category_id');
    }
    public function shop_sub_category()
    {
		return $this->belongsTo('App\ShopSubCategory','shop_sub_category_id');

    }
}
