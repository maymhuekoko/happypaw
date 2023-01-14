<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_items', function (Blueprint $table) {
            $table->id();
            $table->string('shop_item_code');
            $table->string('shop_item_name');
            $table->string('created_by');
            $table->tinyInteger('customer_console');
            $table->string('photo_path');
            $table->string('unit_name');
            $table->unsignedInteger('shop_category_id');
            $table->unsignedInteger('shop_sub_category_id');
            $table->unsignedInteger('shop_brand_id');
            $table->unsignedInteger('shop_type_id');
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
        Schema::dropIfExists('shop_items');
    }
}
