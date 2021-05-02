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
            ['name' => 'Cash'],
            ['name' => 'Bank Transfer'],
            ['name' => 'LinkAja'],
            ['name' => 'OVO'],
            ['name' => 'Hutang'],
            ['name' => 'Diskon'],
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
