<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('voucher_code');
            $table->Integer('total_price');
            $table->Integer('total_quantity');
            $table->tinyInteger('type');
            $table->tinyInteger('status');
            $table->Integer('sale_by');
            $table->string('voucher_date')->nullable();
            $table->Integer('order_id')->default(0);
            $table->Integer('sales_customer_id');
            $table->string('sales_customer_name');
            $table->bigInteger('from_id');
            $table->Integer('pay');
            $table->Integer('change');
            $table->string('discount')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_vouchers');
    }
}
