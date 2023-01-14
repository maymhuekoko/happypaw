<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopItem extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $fillable = [
        'shop_item_code',
        'shop_item_name',
        'created_by',
        'photo_path',
        'customer_console',
        'shop_category_id',
        'shop_sub_category_id',
        'shop_brand_id',
        'shop_type_id',
        'deleted_at',
        'unit_name'
    ];

	public function shop_category() {
        return $this->belongsTo(ShopCategory::class);
    }

    public function shop_sub_category() {
        return $this->belongsTo(ShopSubCategory::class);
    }

    public function shop_brand() {
        return $this->belongsTo(ShopBrand::class);
    }

    public function shop_counting_units(){
        return $this->hasMany(ShopCountingUnit::class);
    }
    public function froms()
    {
		return $this->belongsToMany('App\From','from_shop_item','shop_item_id','from_id')->withPivot('id','shop_item_id','from_id');
    }
}
