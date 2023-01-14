<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinic_patients', function (Blueprint $table) {
            
            $table->id();
            $table->string("name");
            $table->text("pet_code");
            $table->string('ownername');
            $table->date('dob');
            $table->text('sex');
            $table->text('species');
            $table->text('microchip');
            $table->text('breed');
            $table->text('color');
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
        Schema::dropIfExists('clinic_patients');
    }
}
