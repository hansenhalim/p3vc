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
    $transactions = [
      ['unit_id' => 1, 'period' => '2020-06-01', 'created_at' => '2020-07-01 00:00:00'],
      ['unit_id' => 2, 'period' => '2020-06-01', 'created_at' => '2020-07-01 00:00:00'],
      ['unit_id' => 3, 'period' => '2020-06-01', 'created_at' => '2020-07-01 00:00:00'],
      ['unit_id' => 4, 'period' => '2020-06-01', 'created_at' => '2020-07-01 00:00:00'],
      ['unit_id' => 5, 'period' => '2020-06-01', 'created_at' => '2020-07-01 00:00:00'],
      ['unit_id' => 6, 'period' => '2020-06-01', 'created_at' => '2020-07-01 00:00:00'],
      ['unit_id' => 7, 'period' => '2020-06-01', 'created_at' => '2020-07-01 00:00:00'],
      ['unit_id' => 8, 'period' => '2020-06-01', 'created_at' => '2020-07-01 00:00:00'],
      ['unit_id' => 9, 'period' => '2020-06-01', 'created_at' => '2020-07-01 00:00:00'],
      ['unit_id' => 10, 'period' => '2020-06-01', 'created_at' => '2020-07-01 00:00:00'],
    ];
    foreach ($transactions as $transaction) {
      DB::table('transactions')->insert([
        'unit_id' => $transaction['unit_id'],
        'period' => $transaction['period'],
        'approved_at' => $transaction['created_at'],
        'approved_by' => 5,
        'created_at' => $transaction['created_at'],
        'updated_by' => 3,
      ]);
    }
  }
}