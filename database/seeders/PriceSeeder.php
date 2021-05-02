<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prices = [
            ['cluster_id' => 1, 'cost' => 600, 'per' => 'sqm'],
            ['cluster_id' => 2, 'cost' => 750, 'per' => 'sqm'],
            ['cluster_id' => 3, 'cost' => 800, 'per' => 'sqm'],
            ['cluster_id' => 4, 'cost' => 1000, 'per' => 'sqm'],
            ['cluster_id' => 5, 'cost' => 1000, 'per' => 'sqm'],
            ['cluster_id' => 6, 'cost' => 1500, 'per' => 'sqm'],
            ['cluster_id' => 7, 'cost' => 128000, 'per' => 'mth'],
            ['cluster_id' => 8, 'cost' => 133000, 'per' => 'mth'],
            ['cluster_id' => 9, 'cost' => 158000, 'per' => 'mth'],
            ['cluster_id' => 10, 'cost' => 200000, 'per' => 'mth'],
            ['cluster_id' => 11, 'cost' => 210000, 'per' => 'mth'],
            ['cluster_id' => 12, 'cost' => 280000, 'per' => 'mth'],
            ['cluster_id' => 13, 'cost' => 300000, 'per' => 'mth'],
            ['cluster_id' => 14, 'cost' => 310000, 'per' => 'mth'],
            ['cluster_id' => 15, 'cost' => 325000, 'per' => 'mth'],
            ['cluster_id' => 16, 'cost' => 400000, 'per' => 'mth'],
            ['cluster_id' => 17, 'cost' => 437000, 'per' => 'mth'],
            ['cluster_id' => 18, 'cost' => 557000, 'per' => 'mth'],
            ['cluster_id' => 19, 'cost' => 567750, 'per' => 'mth'],
            ['cluster_id' => 20, 'cost' => 574000, 'per' => 'mth'],
            ['cluster_id' => 21, 'cost' => 1000000, 'per' => 'mth'],
            ['cluster_id' => 22, 'cost' => 2000000, 'per' => 'mth'],
        ];
        foreach ($prices as $price) {
            DB::table('prices')->insert([
                'cluster_id' => $price['cluster_id'],
                'cost' => $price['cost'],
                'per' => $price['per'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
