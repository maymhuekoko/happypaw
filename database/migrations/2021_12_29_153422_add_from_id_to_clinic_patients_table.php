<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFromIdToClinicPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinic_patients', function (Blueprint $table) {
            $table->unsignedBigInteger('from_id');
            $table->foreign('from_id')->references('id')->on('froms');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clinic_patients', function (Blueprint $table) {
            //
        });
    }
}
