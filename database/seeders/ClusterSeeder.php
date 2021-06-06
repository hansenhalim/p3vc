<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClusterSeeder extends Seeder
{
  public function run()
  {
    $clusters = array(
      array('id' => '1','name' => 'cluster 1','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '2','name' => 'cluster 2','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '3','name' => 'cluster 3','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '4','name' => 'cluster 4','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '5','name' => 'cluster 5','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '6','name' => 'villa 128','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '7','name' => 'villa 133','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '8','name' => 'villa 158','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '9','name' => 'villa 200','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '10','name' => 'villa 210','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '11','name' => 'villa 280','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '12','name' => 'villa 300','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '13','name' => 'villa 310','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '14','name' => 'villa 250','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '15','name' => 'villa 400','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '16','name' => 'villa 437','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '17','name' => 'villa 557','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '18','name' => 'villa 568','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '19','name' => 'villa 574','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '20','name' => 'villa 1000','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '21','name' => 'villa 2000','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '22','name' => 'villa 554','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL)
    );
    foreach ($clusters as $cluster) {
      DB::table('clusters')->insert([
        'name' => $cluster['name'],
        'approved_at' => $cluster['approved_at'],
        'approved_by' => $cluster['approved_by'],
        'created_at' => $cluster['created_at'],
        'updated_at' => $cluster['updated_at'],
        'updated_by' => $cluster['updated_by'],
        'deleted_at' => $cluster['deleted_at'],
      ]);
    }
  }
}
