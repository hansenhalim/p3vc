<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('configs')->insert([
      [
        'key' => 'fine_amount',
        'value' => '2000'
      ],
      [
        'key' => 'units_last_sync',
        'value' => '1970-01-01 00:00:00'
      ],
    ]);
  }
}
