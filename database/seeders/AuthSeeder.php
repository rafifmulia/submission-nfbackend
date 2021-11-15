<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
				'id' => 1,
                'name' => 'Rafif Mulia Reswara',
				'email' => 'rafif.mulia.r@gmail.com',
				'password' => Hash::make('123456'),
            ],
        ]);
    }
}
