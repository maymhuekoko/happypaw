<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopSubCategory extends Model
{
    //
     protected $fillable = [

        'subcategory_code','name','shop_category_id'

    ];

    public function shop_category() {
        return $this->belongsTo('App\ShopCategory','shop_category_id');
    }
}
