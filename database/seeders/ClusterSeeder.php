<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClusterSeeder extends Seeder
{
    public function run()
    {
        $clusters = [
            ['name' => 'cluster 1'],
            ['name' => 'cluster 2'],
            ['name' => 'cluster 3'],
            ['name' => 'cluster 4'],
            ['name' => 'cluster 5'],
            ['name' => 'villa 128'],
            ['name' => 'villa 133'],
            ['name' => 'villa 158'],
            ['name' => 'villa 200'],
            ['name' => 'villa 210'],
            ['name' => 'villa 280'],
            ['name' => 'villa 300'],
            ['name' => 'villa 310'],
            ['name' => 'villa 250'],
            ['name' => 'villa 400'],
            ['name' => 'villa 437'],
            ['name' => 'villa 557'],
            ['name' => 'villa 568'],
            ['name' => 'villa 574'],
            ['name' => 'villa 1000'],
            ['name' => 'villa 2000'],
            ['name' => 'villa 554'],
        ];
        foreach ($clusters as $cluster) {
            DB::table('clusters')->insert([
                'name' => $cluster['name'],
                'deleted_by' => null,
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
