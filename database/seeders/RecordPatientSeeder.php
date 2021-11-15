<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RecordPatient;

class RecordPatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RecordPatient::insert([
            [
                'patient_id' => 1,
                'status_covid_id' => 1,
                'in_date_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
				'out_date_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'patient_id' => 2,
                'status_covid_id' => 2,
                'in_date_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
				'out_date_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'patient_id' => 3,
                'status_covid_id' => 3,
                'in_date_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
				'out_date_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'patient_id' => 4,
                'status_covid_id' => 4,
                'in_date_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
				'out_date_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
