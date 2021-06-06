<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
  public function run()
  {
    $customers = [
      ['name' => 'GO CHI HWA'],
      ['name' => 'YULIANA'],
      ['name' => 'BUDI'],
      ['name' => 'HERMAN.D'],
      ['name' => 'HALIM BUDIMAN'],
      ['name' => 'YOK SILADO'],
      ['name' => 'DELI RAHMAWATI'],
      ['name' => 'DRS.SUHAILI'],
      ['name' => 'HASAN'],
      ['name' => 'ROSIDI SETIAWAN'],
    ];
    foreach ($customers as $customer) {
      DB::table('customers')->insert([
        'name' => $customer['name'],
        'approved_at' => '2020-06-01 00:00:00',
        'approved_by' => 5,
        'created_at' => '2020-06-01 00:00:00',
        'updated_by' => 3,
      ]);
    }
  }
}
