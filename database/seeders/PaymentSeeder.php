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
        'created_at' => now(),
        'updated_at' => now()
      ]);
    }
  }
}
