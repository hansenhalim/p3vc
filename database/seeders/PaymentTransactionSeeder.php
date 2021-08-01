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
    $payment_transactions = array(
      array('transaction_id' => '1','payment_id' => '1','amount' => '359000'),
      array('transaction_id' => '1','payment_id' => '4','amount' => '359000'),
      array('transaction_id' => '2','payment_id' => '1','amount' => '190000'),
      array('transaction_id' => '2','payment_id' => '4','amount' => '190000'),
      array('transaction_id' => '3','payment_id' => '1','amount' => '216000'),
      array('transaction_id' => '3','payment_id' => '4','amount' => '216000'),
      array('transaction_id' => '4','payment_id' => '1','amount' => '222000'),
      array('transaction_id' => '4','payment_id' => '4','amount' => '222000'),
      array('transaction_id' => '5','payment_id' => '1','amount' => '444000'),
      array('transaction_id' => '5','payment_id' => '4','amount' => '444000'),
      array('transaction_id' => '6','payment_id' => '1','amount' => '147000'),
      array('transaction_id' => '6','payment_id' => '4','amount' => '147000'),
      array('transaction_id' => '7','payment_id' => '1','amount' => '168000'),
      array('transaction_id' => '7','payment_id' => '4','amount' => '168000'),
      array('transaction_id' => '8','payment_id' => '1','amount' => '184000'),
      array('transaction_id' => '8','payment_id' => '4','amount' => '184000'),
      array('transaction_id' => '9','payment_id' => '1','amount' => '196000'),
      array('transaction_id' => '9','payment_id' => '4','amount' => '196000'),
      array('transaction_id' => '10','payment_id' => '1','amount' => '208000'),
      array('transaction_id' => '10','payment_id' => '4','amount' => '208000'),
      array('transaction_id' => '11','payment_id' => '1','amount' => '126000'),
      array('transaction_id' => '11','payment_id' => '4','amount' => '126000'),
      array('transaction_id' => '12','payment_id' => '1','amount' => '120000'),
      array('transaction_id' => '12','payment_id' => '4','amount' => '120000'),
      array('transaction_id' => '13','payment_id' => '1','amount' => '195000'),
      array('transaction_id' => '13','payment_id' => '4','amount' => '195000'),
      array('transaction_id' => '14','payment_id' => '1','amount' => '492000'),
      array('transaction_id' => '14','payment_id' => '4','amount' => '492000'),
      array('transaction_id' => '15','payment_id' => '1','amount' => '170000'),
      array('transaction_id' => '15','payment_id' => '4','amount' => '170000'),
      array('transaction_id' => '16','payment_id' => '1','amount' => '170000'),
      array('transaction_id' => '16','payment_id' => '4','amount' => '170000'),
      array('transaction_id' => '17','payment_id' => '1','amount' => '144000'),
      array('transaction_id' => '17','payment_id' => '4','amount' => '144000'),
      array('transaction_id' => '18','payment_id' => '1','amount' => '144000'),
      array('transaction_id' => '18','payment_id' => '4','amount' => '144000'),
      array('transaction_id' => '19','payment_id' => '1','amount' => '160000'),
      array('transaction_id' => '19','payment_id' => '4','amount' => '160000'),
      array('transaction_id' => '20','payment_id' => '1','amount' => '174000'),
      array('transaction_id' => '20','payment_id' => '4','amount' => '174000'),
    );
    foreach ($payment_transactions as $payment_transaction) {
      DB::table('payment_transaction')->insert([
        'transaction_id' => $payment_transaction['transaction_id'],
        'payment_id' => $payment_transaction['payment_id'],
        'amount' => $payment_transaction['amount'],
      ]);
    }
  }
}
