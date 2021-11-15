<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'patients';
    protected $primaryKey = 'id';
    protected $guarded = [];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

	public function record_patient()
	{
		return $this->hasMany(RecordPatient::class, 'patient_id', 'id');
	}
}
