<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Patient::insert([
            [
				'id' => 1,
                'name' => 'Rafif Mulia Reswara',
				'phone' => '081285091879',
				'address' => 'Depok Timur',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
				'id' => 2,
                'name' => 'Budi Pekerti',
				'phone' => '0812850918791',
				'address' => 'Depok Barat',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
				'id' => 3,
                'name' => 'Budi Santoso',
				'phone' => '0812850918792',
				'address' => 'Depok Utara',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
				'id' => 4,
                'name' => 'Budi Jack',
				'phone' => '0812850918793',
				'address' => 'Depok Selatan',
                'created_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
