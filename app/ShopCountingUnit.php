<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopCountingUnit extends Model
{
	use SoftDeletes;

    protected $guarded = [];

    protected $fillable = [
    	'unit_code',
    	'original_code',
		'unit_name',
		'current_quantity',
		'reorder_quantity',
		'normal_sale_price',
		'whole_sale_price',
		'purchase_price',
		'shop_item_id',
		'normal_fixed_flash',
		'normal_fixed_percent',
		'whole_fixed_flash',
		'whole_fixed_percent',
        'deleted_at',
	];

	public function shop_item() {
		return $this->belongsTo(ShopItem::class);
	}

	// public function order() {
	// 	return $this->belongsToMany('App\Order')->withPivot('id','quantity');
	// }
	public function shop_stockcounts()
	{
        return $this->hasMany(ShopStockcount::class);
	}
}
