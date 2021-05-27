<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTransactionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $payment_transactions = [
      ['transaction_id' => 1, 'payment_id' => 1, 'amount' => 359000],
      ['transaction_id' => 1, 'payment_id' => 4, 'amount' => 359000],
      ['transaction_id' => 2, 'payment_id' => 1, 'amount' => 190000],
      ['transaction_id' => 2, 'payment_id' => 4, 'amount' => 190000],
      ['transaction_id' => 3, 'payment_id' => 1, 'amount' => 216000],
      ['transaction_id' => 3, 'payment_id' => 4, 'amount' => 216000],
      ['transaction_id' => 4, 'payment_id' => 1, 'amount' => 222000],
      ['transaction_id' => 4, 'payment_id' => 4, 'amount' => 222000],
      ['transaction_id' => 5, 'payment_id' => 1, 'amount' => 444000],
      ['transaction_id' => 5, 'payment_id' => 4, 'amount' => 444000],
      ['transaction_id' => 6, 'payment_id' => 1, 'amount' => 147000],
      ['transaction_id' => 6, 'payment_id' => 4, 'amount' => 147000],
      ['transaction_id' => 7, 'payment_id' => 1, 'amount' => 168000],
      ['transaction_id' => 7, 'payment_id' => 4, 'amount' => 168000],
      ['transaction_id' => 8, 'payment_id' => 1, 'amount' => 184000],
      ['transaction_id' => 8, 'payment_id' => 4, 'amount' => 184000],
      ['transaction_id' => 9, 'payment_id' => 1, 'amount' => 196000],
      ['transaction_id' => 9, 'payment_id' => 4, 'amount' => 196000],
      ['transaction_id' => 10, 'payment_id' => 1, 'amount' => 208000],
      ['transaction_id' => 10, 'payment_id' => 4, 'amount' => 208000],
    ];
    foreach ($payment_transactions as $payment_transaction) {
      DB::table('payment_transaction')->insert([
        'transaction_id' => $payment_transaction['transaction_id'],
        'payment_id' => $payment_transaction['payment_id'],
        'amount' => $payment_transaction['amount'],
        'created_at' => now(),
      ]);
    }
  }
}
