<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profiles')->insert([
            'profile_name'=>'Administrador'
        ]);

        DB::table('profiles')->insert([
            'profile_name'=>'Cliente'
        ]);
    }
}
