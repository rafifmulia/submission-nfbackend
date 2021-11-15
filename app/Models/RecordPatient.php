<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordPatient extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'record_patients';
    protected $primaryKey = 'id';
    protected $guarded = [];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

	public function status_covid()
	{
		return $this->hasOne(StatusCovid::class, 'id', 'status_covid_id');
	}

	public function patient()
	{
		return $this->hasOne(Patient::class, 'id', 'patient_id');
	}
}