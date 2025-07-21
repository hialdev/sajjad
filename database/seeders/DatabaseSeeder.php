<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // \App\Models\User::factory(10)->create();
        $this->call([
            // MasterSeeder::class,
            ProductUnitPackSeeder::class,
            WarehouseStoreSeeder::class,
            ClientSeeder::class,
            LogisticSeeder::class,
            PrincipalSeeder::class,
            PartnerSeeder::class,
        ]);
    }
}
