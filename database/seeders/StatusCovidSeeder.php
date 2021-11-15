<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StatusCovid;

class StatusCovidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StatusCovid::insert([
            [
                'id' => 1,
                'name' => 'negative',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'name' => 'positive',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'name' => 'recovered',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'name' => 'dead',
                'created_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
