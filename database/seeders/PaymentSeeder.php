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
    $payments = [
      ['name' => 'TAGIHAN'],
      ['name' => 'DENDA'],
      ['name' => 'TOP UP SALDO'],
      ['name' => 'OTHER'],
      ['name' => 'BANK TRANSFER'],
      ['name' => 'TUNAI'],
      ['name' => 'LINKAJA'],
      ['name' => 'HUTANG'],
      ['name' => 'DISKON'],
      ['name' => 'SALDO UNIT'],
    ];
    foreach ($payments as $payment) {
      DB::table('payments')->insert([
        'name' => $payment['name'],
        'approved_at' => '2020-06-01 00:00:00',
        'approved_by' => 5,
        'created_at' => '2020-06-01 00:00:00',
        'updated_by' => 3,
      ]);
    }
  }
}
