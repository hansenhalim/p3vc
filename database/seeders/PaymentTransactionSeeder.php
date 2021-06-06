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
      ['transaction_id' => 2, 'payment_id' => 1, 'amount' => 359000],
      ['transaction_id' => 2, 'payment_id' => 4, 'amount' => 359000],
      ['transaction_id' => 3, 'payment_id' => 1, 'amount' => 359000],
      ['transaction_id' => 3, 'payment_id' => 4, 'amount' => 359000],
      ['transaction_id' => 4, 'payment_id' => 1, 'amount' => 359000],
      ['transaction_id' => 4, 'payment_id' => 4, 'amount' => 359000],
      ['transaction_id' => 5, 'payment_id' => 1, 'amount' => 359000],
      ['transaction_id' => 5, 'payment_id' => 4, 'amount' => 359000],
      ['transaction_id' => 6, 'payment_id' => 1, 'amount' => 359000],
      ['transaction_id' => 6, 'payment_id' => 4, 'amount' => 359000],
      ['transaction_id' => 7, 'payment_id' => 1, 'amount' => 359000],
      ['transaction_id' => 7, 'payment_id' => 4, 'amount' => 359000],
      ['transaction_id' => 8, 'payment_id' => 1, 'amount' => 359000],
      ['transaction_id' => 8, 'payment_id' => 4, 'amount' => 359000],
      ['transaction_id' => 9, 'payment_id' => 1, 'amount' => 359000],
      ['transaction_id' => 9, 'payment_id' => 4, 'amount' => 359000],
    ];
    foreach ($payment_transactions as $payment_transaction) {
      DB::table('payment_transaction')->insert([
        'transaction_id' => $payment_transaction['transaction_id'],
        'payment_id' => $payment_transaction['payment_id'],
        'amount' => $payment_transaction['amount'],
      ]);
    }
  }
}
