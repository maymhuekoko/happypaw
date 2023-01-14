<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    //
    protected $fillable = [

        'subcategory_code','name','category_id'

    ];

    public function category() {
        return $this->belongsTo('App\Category','category_id');
    }

    public function brands()
    {
        return $this->belongsToMany('App\Brand','brand_sub_category','sub_category_id','brand_id')->withPivot('id','sub_category_id','brand_id');
    }
}


