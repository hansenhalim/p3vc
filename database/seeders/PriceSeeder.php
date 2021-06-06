<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PriceSeeder extends Seeder
{
  public function run()
  {
    $prices = array(
      array('cluster_id' => '1','cost' => '600','per' => 'sqm','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '2','cost' => '750','per' => 'sqm','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '3','cost' => '800','per' => 'sqm','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '4','cost' => '1000','per' => 'sqm','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '5','cost' => '1500','per' => 'sqm','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '6','cost' => '128000','per' => 'mth','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '7','cost' => '133000','per' => 'mth','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '8','cost' => '158000','per' => 'mth','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '9','cost' => '200000','per' => 'mth','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '10','cost' => '210000','per' => 'mth','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '11','cost' => '280000','per' => 'mth','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '12','cost' => '300000','per' => 'mth','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '13','cost' => '310000','per' => 'mth','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '14','cost' => '250000','per' => 'mth','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '15','cost' => '400000','per' => 'mth','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '16','cost' => '437000','per' => 'mth','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '17','cost' => '557000','per' => 'mth','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '18','cost' => '567750','per' => 'mth','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '19','cost' => '574000','per' => 'mth','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '20','cost' => '1000000','per' => 'mth','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '21','cost' => '2000000','per' => 'mth','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('cluster_id' => '22','cost' => '554000','per' => 'mth','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL)
    );
    foreach ($prices as $price) {
      DB::table('prices')->insert([
        'cluster_id' => $price['cluster_id'],
        'cost' => $price['cost'],
        'per' => $price['per'],
        'approved_at' => $price['approved_at'],
        'approved_by' => $price['approved_by'],
        'created_at' => $price['created_at'],
        'updated_at' => $price['updated_at'],
        'updated_by' => $price['updated_by'],
        'deleted_at' => $price['deleted_at'],
      ]);
    }
  }
}
