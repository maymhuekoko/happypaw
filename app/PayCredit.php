<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayCredit extends Model
{
    //
    protected $fillable = [
        'sale_customer_id','voucher_id','pay_amount',
        'left_amount',
        'pay_date','description','paid_status'
     ];
}
