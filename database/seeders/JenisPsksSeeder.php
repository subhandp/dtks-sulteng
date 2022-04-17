<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPsksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jenis_psks')->insert([
            [
                'jenis' => 'psm',
                'detail' => 'PEKERJA SOSIAL MASYARAKAT',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'jenis' => 'tksk',
                'detail' => 'TENAGA KESEJAHTERAAN SOSIAL MASYARAKAT',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'jenis' => 'lk3',
                'detail' => 'LEMBAGA KONSULTASI KESEJAHTERAAN KELUARGA',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'jenis' => 'lks',
                'detail' => 'LEMBAGA KESEJAHTERAAN SOSIAL',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'jenis' => 'kt',
                'detail' => 'KARANG TARUNA',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'jenis' => 'wkskbm',
                'detail' => 'WAHANA KESEJAHTERAAN SOSIAL KELUARGA BERBASIS MASYARAKAT',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'jenis' => 'fcsr',
                'detail' => 'Forum CSR Kesejahteraan Sosial',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'jenis' => 'fcu',
                'detail' => 'Family Care Unir',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
        ]);
    }
}
