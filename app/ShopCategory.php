<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{
    //
    protected $fillable = [
        'category_code',
        'category_name',
        'created_by',
        'deleted_at',
    ];
}
