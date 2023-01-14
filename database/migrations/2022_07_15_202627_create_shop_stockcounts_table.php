<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopStockcountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_stockcounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('stock_qty');
            $table->unsignedBigInteger('shop_counting_unit_id');
            $table->foreign('shop_counting_unit_id')->references('id')->on('shop_counting_units')->onDelete('cascade');
            $table->unsignedBigInteger('from_id');
            $table->foreign('from_id')->references('id')->on('froms')->onDelete('cascade');
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
        Schema::dropIfExists('shop_stockcounts');
    }
}
