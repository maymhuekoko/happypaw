<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesCustomer extends Model
{
    //
    protected $table = "sales_customers";

    protected $guarded = [];

    protected $fillable = [
            'name',
            'phone',
            'credit_amount',
            'paid_status'
        ];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
