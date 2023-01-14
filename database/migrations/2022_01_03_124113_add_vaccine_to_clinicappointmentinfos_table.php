<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVaccineToClinicappointmentinfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinicappointmentinfos', function (Blueprint $table) {
            $table->text('vaccine_record')->nullable();
            $table->date('next_vaccine_date')->nullable();
            $table->integer('vaccine_duration')->nullable()->comment('1= monthly, 0=yearly');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clinicappointmentinfos', function (Blueprint $table) {
            //
        });
    }
}
