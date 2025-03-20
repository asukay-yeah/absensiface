<?php

use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('pegawais')->insert([
        //     'nip' => '123456789',
        //     'nama' => 'Fauzan',
        //     'jabatan' => 'staff',
        //     'tim' => 'monev',
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ]);

        // 10 data pegawai seeder

        for ($i = 1; $i <= 60; $i++) {
            DB::table('pegawais')->insert([
                'nip' => '202500' . $i, // NIP unik
                'nama' => 'Pegawai ' . $i,
                'jabatan' => 'Staff',
                'tim' => 'Monev ', // Tim 1,2,3 secara bergantian
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}
