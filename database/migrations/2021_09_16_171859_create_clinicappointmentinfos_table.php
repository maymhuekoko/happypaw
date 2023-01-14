<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicappointmentinfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinicappointmentinfos', function (Blueprint $table) {
            $table->id();
            $table->integer('body_temperature')->nullable();
            $table->unsignedBigInteger('appointment_id');
            $table->date('next_appointmentdate')->nullable();
            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
            $table->text('complaint')->nullable();
            $table->text('procedure')->nullable();
            $table->integer('weight_kg')->nullable();
            $table->integer('weight_lb')->nullable();
            $table->text('lung_sound')->nullable();
            $table->text('gum_color')->nullable();
            $table->text('titles')->nullable();
            $table->text('descriptions')->nullable();
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
        Schema::dropIfExists('clinicappointmentinfos');
    }
}
