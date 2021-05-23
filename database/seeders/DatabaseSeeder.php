<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersAndNotesSeeder::class,
            MenusTableSeeder::class,
            PaymentSeeder::class,
            CustomerSeeder::class,
            ClusterSeeder::class,
            PriceSeeder::class,
            UnitSeeder::class,
            MasterTypeSeeder::class,
        ]);
    }
}
