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
    ];
    foreach ($transactions as $transaction) {
      DB::table('transactions')->insert([
        'name' => $transaction['name'],
        'created_at' => now(),
        'updated_at' => now()
      ]);
    }
  }
}
