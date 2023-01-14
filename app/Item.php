<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    //
    use SoftDeletes;

    protected $guarded = [];

    protected $fillable = [
        'item_code', 
        'item_name', 
        'created_by',
        'photo_path',
        'category_id',
        'sub_category_id',
        'brand_id',
        'type_id',
        'model',
        'deleted_at'
    ];
    
	public function category() {
        return $this->belongsTo(Category::class);
    }
    public function brand() {
        return $this->hasOne(Brand::class,'id','brand_id');
    }
    
    public function type() {
        return $this->hasOne(Type::class,'id','type_id');
    }

    public function sub_category() {
        return $this->belongsTo(SubCategory::class);
    }
    public function counting_units(){
        return $this->hasMany(CountingUnit::class);
    }
    public function froms()
    {
		return $this->belongsToMany('App\From','from_item','item_id','from_id')->withPivot('id','item_id','from_id');
    }
}
