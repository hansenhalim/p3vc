<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
  public function run()
  {
    $units = array(
      array('id' => '1','customer_id' => '1','cluster_id' => '4','name' => 'A-1','area_sqm' => '359.00','idlink' => '1010100','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '2','customer_id' => '2','cluster_id' => '4','name' => 'A-10','area_sqm' => '190.00','idlink' => '1011000','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '3','customer_id' => '3','cluster_id' => '4','name' => 'A-11','area_sqm' => '216.00','idlink' => '1011100','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '4','customer_id' => '4','cluster_id' => '4','name' => 'A-12','area_sqm' => '222.00','idlink' => '1011200','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '5','customer_id' => '5','cluster_id' => '4','name' => 'A-12A/14','area_sqm' => '444.00','idlink' => '1011201','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '6','customer_id' => '6','cluster_id' => '4','name' => 'A-15','area_sqm' => '147.00','idlink' => '1011500','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '7','customer_id' => '7','cluster_id' => '4','name' => 'A-16','area_sqm' => '168.00','idlink' => '1011600','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '8','customer_id' => '8','cluster_id' => '4','name' => 'A-17','area_sqm' => '184.00','idlink' => '1011700','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '9','customer_id' => '8','cluster_id' => '4','name' => 'A-18','area_sqm' => '196.00','idlink' => '1011800','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '10','customer_id' => '9','cluster_id' => '4','name' => 'A-19','area_sqm' => '208.00','idlink' => '1011900','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
    );
    foreach ($units as $unit) {
      DB::table('units')->insert([
        'customer_id' => $unit['customer_id'],
        'cluster_id' => $unit['cluster_id'],
        'name' => $unit['name'],
        'area_sqm' => $unit['area_sqm'],
        'idlink' => $unit['idlink'],
        'approved_at' => $unit['approved_at'],
        'approved_by' => $unit['approved_by'],
        'created_at' => $unit['created_at'],
        'updated_at' => $unit['updated_at'],
        'updated_by' => $unit['updated_by'],
        'deleted_at' => $unit['deleted_at'],
      ]);
    }
  }
}
