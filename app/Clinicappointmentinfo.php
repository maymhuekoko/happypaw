<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clinicappointmentinfo extends Model
{
    protected $fillable = [
        'body_temperature','weight_kg','weight_lb','appointment_id','next_appointmentdate','lung_sound','gum_color','titles','descriptions','complaint','procedure','vaccine_record','next_vaccine_date','vaccine_duration'
    ];

    public function appointment() {
		return $this->belongsTo(Appointment::class);
	}
}
