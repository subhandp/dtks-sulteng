<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Operator PMKS',
                'email' => 'admin.pmks@gmail.com',
                'role' => 'operator_pmks',
                'username' => 'opr_pmks',
                'password' => Hash::make('123456'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Operator PSKS',
                'email' => 'admin.psks@gmail.com',
                'role' => 'operator_psks',
                'username' => 'opr_psks',
                'password' => Hash::make('123456'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ]);
    }
}
