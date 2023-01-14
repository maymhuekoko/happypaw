<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    //
    protected $fillable = [
        
        'brand_code','name'
         
    ];
    public function subcategories()
    {
		return $this->belongsToMany('App\SubCategory','brand_sub_category','brand_id','sub_category_id')->withPivot('id','brand_id','sub_category_id');

    }
}
