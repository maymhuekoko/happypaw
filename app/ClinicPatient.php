<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClinicPatient extends Model
{
    protected $fillable = ["pet_code","name","ownername","dob","sex","species","microchip","breed","color","from_id","ownerphone"];
    public function appointments() {
		return $this->hasMany('App\Appointment');
	}
    public function latestappointments($count) {
		return $this->hasMany('App\Appointment')->latest()->take($count);
	}
    public function dateappointments($fromdate,$todate) {
		return $this->hasMany('App\Appointment')->whereBetween('date',[$fromdate,$todate]);
	}
}
