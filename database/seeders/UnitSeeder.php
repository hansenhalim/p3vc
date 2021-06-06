<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
  public function run()
  {
    $units = [
      ['customer_id' => 1, 'cluster_id' => 4, 'name' => 'A-1', 'area_sqm' => 359, 'idlink' => '1010100'],
      ['customer_id' => 2, 'cluster_id' => 4, 'name' => 'A-10', 'area_sqm' => 190, 'idlink' => '1011000'],
      ['customer_id' => 3, 'cluster_id' => 4, 'name' => 'A-11', 'area_sqm' => 216, 'idlink' => '1011100'],
      ['customer_id' => 4, 'cluster_id' => 4, 'name' => 'A-12', 'area_sqm' => 222, 'idlink' => '1011200'],
      ['customer_id' => 5, 'cluster_id' => 4, 'name' => 'A-12A/14', 'area_sqm' => 444, 'idlink' => '1011201'],
      ['customer_id' => 6, 'cluster_id' => 4, 'name' => 'A-15', 'area_sqm' => 147, 'idlink' => '1011500'],
      ['customer_id' => 7, 'cluster_id' => 4, 'name' => 'A-16', 'area_sqm' => 168, 'idlink' => '1011600'],
      ['customer_id' => 8, 'cluster_id' => 4, 'name' => 'A-17', 'area_sqm' => 184, 'idlink' => '1011700'],
      ['customer_id' => 8, 'cluster_id' => 4, 'name' => 'A-18', 'area_sqm' => 196, 'idlink' => '1011800'],
      ['customer_id' => 9, 'cluster_id' => 4, 'name' => 'A-19', 'area_sqm' => 208, 'idlink' => '1011900'],
      ['customer_id' => 10, 'cluster_id' => 4, 'name' => 'A-1A', 'area_sqm' => 126, 'idlink' => '1010101'],
      ['customer_id' => 1, 'cluster_id' => 4, 'name' => 'A-1B', 'area_sqm' => 120, 'idlink' => '1010102'],
      ['customer_id' => 2, 'cluster_id' => 4, 'name' => 'A-1C', 'area_sqm' => 195, 'idlink' => '1010103'],
      ['customer_id' => 3, 'cluster_id' => 4, 'name' => 'A-2', 'area_sqm' => 492, 'idlink' => '1010200'],
      ['customer_id' => 4, 'cluster_id' => 4, 'name' => 'A-3', 'area_sqm' => 170, 'idlink' => '1010300'],
      ['customer_id' => 5, 'cluster_id' => 4, 'name' => 'A-4', 'area_sqm' => 170, 'idlink' => '1010400'],
      ['customer_id' => 6, 'cluster_id' => 4, 'name' => 'A-5', 'area_sqm' => 144, 'idlink' => '1010500'],
      ['customer_id' => 7, 'cluster_id' => 4, 'name' => 'A-6', 'area_sqm' => 144, 'idlink' => '1010600'],
      ['customer_id' => 8, 'cluster_id' => 4, 'name' => 'A-7', 'area_sqm' => 216, 'idlink' => '1010700'],
      ['customer_id' => 9, 'cluster_id' => 4, 'name' => 'A-8', 'area_sqm' => 160, 'idlink' => '1010800'],
      ['customer_id' => 10, 'cluster_id' => 4, 'name' => 'A-9', 'area_sqm' => 174, 'idlink' => '1010900'],
    ];
    foreach ($units as $unit) {
      DB::table('units')->insert([
        'customer_id' => $unit['customer_id'],
        'cluster_id' => $unit['cluster_id'],
        'name' => $unit['name'],
        'area_sqm' => $unit['area_sqm'],
        'idlink' => $unit['idlink'],
        'approved_at' => '2020-06-01 00:00:00',
        'approved_by' => 5,
        'created_at' => '2020-06-01 00:00:00',
        'updated_by' => 3,
      ]);
    }
  }
}
