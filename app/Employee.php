<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
	protected $guarded = [];

    protected $fillable = [
		'name','employee_code', 'dob', 'phone', 'photo','position_id','user_id',
	];
	public function user()
	{
		return $this->belongsTo(User::class);
	}

}
