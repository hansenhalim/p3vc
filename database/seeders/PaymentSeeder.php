<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $payments = array(
      array('id' => '1','name' => 'TAGIHAN','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '2','name' => 'DENDA','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '3','name' => 'TOP UP SALDO','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '4','name' => 'OTHER','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '5','name' => 'BANK TRANSFER','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '6','name' => 'TUNAI','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '7','name' => 'LINKAJA','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '8','name' => 'HUTANG','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '9','name' => 'DISKON','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '10','name' => 'SALDO UNIT','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '11','name' => 'BAYAR HUTANG','approved_at' => '2020-06-01 00:00:00','approved_by' => '5','created_at' => '2020-06-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL)
    );
    foreach ($payments as $payment) {
      DB::table('payments')->insert([
        'name' => $payment['name'],
        'approved_at' => $payment['approved_at'],
        'approved_by' => $payment['approved_by'],
        'created_at' => $payment['created_at'],
        'updated_at' => $payment['updated_at'],
        'updated_by' => $payment['updated_by'],
        'deleted_at' => $payment['deleted_at'],
      ]);
    }
  }
}
