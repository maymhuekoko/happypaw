<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFromShopItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('from_shop_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_item_id');
            $table->foreign('shop_item_id')->references('id')->on('shop_items');
            $table->unsignedBigInteger('from_id');
            $table->foreign('from_id')->references('id')->on('froms');
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
        Schema::dropIfExists('from_shop_item');
    }
}
