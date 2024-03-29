<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('pay_credits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_customer_id');
            $table->unsignedBigInteger('voucher_id');
            $table->string('pay_amount');
            $table->string('left_amount');
            $table->string('description');
            $table->date('pay_date');
            $table->integer('paid_status');
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
        Schema::dropIfExists('pay_credits');
    }
}
