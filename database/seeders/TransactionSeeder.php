<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $transactions = array(
      array('id' => '1','unit_id' => '1','period' => '2020-06-01','approved_at' => '2021-06-02 07:55:32','approved_by' => '5','created_at' => '2020-07-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '2','unit_id' => '2','period' => '2020-06-01','approved_at' => '2021-06-02 07:55:32','approved_by' => '5','created_at' => '2020-07-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '3','unit_id' => '3','period' => '2020-06-01','approved_at' => '2021-06-02 07:55:32','approved_by' => '5','created_at' => '2020-07-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '4','unit_id' => '4','period' => '2020-06-01','approved_at' => '2021-06-02 07:55:32','approved_by' => '5','created_at' => '2020-07-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '5','unit_id' => '5','period' => '2020-06-01','approved_at' => '2021-06-02 07:55:32','approved_by' => '5','created_at' => '2020-07-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '6','unit_id' => '6','period' => '2020-06-01','approved_at' => '2021-06-02 07:55:32','approved_by' => '5','created_at' => '2020-07-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '7','unit_id' => '7','period' => '2020-06-01','approved_at' => '2021-06-02 07:55:32','approved_by' => '5','created_at' => '2020-07-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '8','unit_id' => '8','period' => '2020-06-01','approved_at' => '2021-06-02 07:55:32','approved_by' => '5','created_at' => '2020-07-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '9','unit_id' => '9','period' => '2020-06-01','approved_at' => '2021-06-02 07:55:32','approved_by' => '5','created_at' => '2020-07-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
      array('id' => '10','unit_id' => '10','period' => '2020-06-01','approved_at' => '2021-06-02 07:55:32','approved_by' => '5','created_at' => '2020-07-01 00:00:00','updated_at' => NULL,'updated_by' => '3','deleted_at' => NULL),
    );
    foreach ($transactions as $transaction) {
      DB::table('transactions')->insert([
        'unit_id' => $transaction['unit_id'],
        'period' => $transaction['period'],
        'approved_at' => $transaction['approved_at'],
        'approved_by' => $transaction['approved_by'],
        'created_at' => $transaction['created_at'],
        'updated_at' => $transaction['updated_at'],
        'updated_by' => $transaction['updated_by'],
        'deleted_at' => $transaction['deleted_at'],
      ]);
    }
  }
}