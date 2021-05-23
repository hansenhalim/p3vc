<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $master_types = [
            ['name' => 'create'],
            ['name' => 'edit'],
            ['name' => 'delete'],
        ];
        foreach ($master_types as $master_type) {
            DB::table('master_types')->insert([
                'name' => $master_type['name']
            ]);
        }
    }
}
