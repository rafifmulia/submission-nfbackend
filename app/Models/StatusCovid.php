<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusCovid extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'status_covid';
    protected $primaryKey = 'id';
    protected $guarded = [];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const TEXT_NEGATIVE = 'negative';
    const TEXT_POSITIVE = 'positive';
    const TEXT_RECOVERED = 'recovered';
    const TEXT_DEAD = 'dead';

    public function record_patient()
	{
		return $this->hasMany(RecordPatient::class, 'status_covid_id', 'id');
	}
}