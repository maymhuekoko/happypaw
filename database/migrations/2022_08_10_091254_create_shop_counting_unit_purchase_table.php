<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopCountingUnitPurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_counting_unit_purchase', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('shop_counting_unit_id');
            $table->unsignedInteger('purchase_id');
            $table->Integer('quantity');
            $table->Integer('price');
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
        Schema::dropIfExists('shop_counting_unit_purchase');
    }
}
