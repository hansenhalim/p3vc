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
      ['unit_id' => 1, 'period' => '2020-06-01'],
      ['unit_id' => 2, 'period' => '2020-06-01'],
      ['unit_id' => 3, 'period' => '2020-06-01'],
      ['unit_id' => 4, 'period' => '2020-06-01'],
      ['unit_id' => 5, 'period' => '2020-06-01'],
      ['unit_id' => 6, 'period' => '2020-06-01'],
      ['unit_id' => 7, 'period' => '2020-06-01'],
      ['unit_id' => 8, 'period' => '2020-06-01'],
      ['unit_id' => 9, 'period' => '2020-06-01'],
      ['unit_id' => 10, 'period' => '2020-06-01'],
    ];
    foreach ($transactions as $transaction) {
      DB::table('transactions')->insert([
        'unit_id' => $transaction['unit_id'],
        'period' => $transaction['period'],
        'approved_at' => now(),
        'approved_by' => 5,
        'created_at' => now(),
        'updated_by' => 3,
      ]);
    }
  }
}
