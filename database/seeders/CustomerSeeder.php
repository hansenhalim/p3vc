<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
  public function run()
  {
    $customers = array(
      array('id' => '1','name' => 'GO CHI HWA','phone_number' => '08999745723','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => '2021-06-03 03:32:47','updated_by' => '3','deleted_at' => NULL),
      array('id' => '2','name' => 'YULIANA','phone_number' => '081279007230','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => '2021-06-03 03:33:55','updated_by' => '3','deleted_at' => NULL),
      array('id' => '3','name' => 'BUDI','phone_number' => '08117839600','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => '2021-06-03 03:35:53','updated_by' => '3','deleted_at' => NULL),
      array('id' => '4','name' => 'HERMAN.D','phone_number' => '08127925189','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => '2021-06-03 03:36:22','updated_by' => '3','deleted_at' => NULL),
      array('id' => '5','name' => 'HALIM BUDIMAN','phone_number' => '0811720121','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => '2021-06-03 03:37:02','updated_by' => '3','deleted_at' => NULL),
      array('id' => '6','name' => 'YOK SILADO','phone_number' => '0811796279','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => '2021-06-03 03:37:33','updated_by' => '3','deleted_at' => NULL),
      array('id' => '7','name' => 'DELI RAHMAWATI','phone_number' => '082177584093','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => '2021-06-03 03:38:05','updated_by' => '3','deleted_at' => NULL),
      array('id' => '8','name' => 'DRS.SUHAILI','phone_number' => NULL,'approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '9','name' => 'HASAN','phone_number' => NULL,'approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
    );
    foreach ($customers as $customer) {
      DB::table('customers')->insert([
        'name' => $customer['name'],
        'phone_number' => $customer['phone_number'],
        'approved_at' => $customer['approved_at'],
        'approved_by' => $customer['approved_by'],
        'created_at' => $customer['created_at'],
        'updated_at' => $customer['updated_at'],
        'updated_by' => $customer['updated_by'],
        'deleted_at' => $customer['deleted_at'],
      ]);
    }
  }
}
