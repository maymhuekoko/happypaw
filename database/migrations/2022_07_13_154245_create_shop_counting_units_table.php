<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopCountingUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_counting_units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_code')->nullable();
            $table->string('original_code')->nullable();
            $table->string('unit_name');
            $table->integer('current_quantity');
            $table->integer('reorder_quantity');
            $table->integer('normal_sale_price');
            $table->integer('whole_sale_price');
            $table->integer('purchase_price');
            $table->unsignedInteger('shop_item_id');
            $table->integer('normal_fixed_flash');
            $table->integer('normal_fixed_percent');
            $table->integer('whole_fixed_flash');
            $table->integer('whole_fixed_percent');
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
        Schema::dropIfExists('shop_counting_units');
    }
}
