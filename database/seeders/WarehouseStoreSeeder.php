<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

use App\Models\Warehouse;
use App\Models\Store;

class WarehouseStoreSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');         // locale Indonesia

        DB::transaction(function () use ($faker) {

            /* ──────── Warehouse ──────── */
            $totalWarehouses = 15;               // ubah sesuai kebutuhan
            for ($i = 0; $i < $totalWarehouses; $i++) {
                $wcity = $faker->city;
                Warehouse::create([
                    'name'        => 'Gudang ' .$wcity,
                    'email'       => $faker->unique()->companyEmail,
                    'phone'       => $faker->phoneNumber,
                    'fax'         => $faker->optional()->phoneNumber,
                    'description' => $faker->sentence(),
                    'address'     => $faker->streetAddress,
                    'city'        => strtoupper($wcity),
                    'postal_code' => $faker->postcode,
                ]);
            }

            /* ──────── Store ──────── */
            $totalStores = 25;                   // ubah sesuai kebutuhan
            for ($i = 0; $i < $totalStores; $i++) {

                Store::create([
                    'name'        => 'Toko '. $faker->company,
                    'email'       => $faker->unique()->companyEmail,
                    'phone'       => $faker->phoneNumber,
                    'fax'         => $faker->optional()->phoneNumber,
                    'description' => $faker->sentence(),
                    'address'     => $faker->streetAddress,
                    'city'        => strtoupper($faker->city),
                    'postal_code' => $faker->postcode,
                ]);
            }
        });
    }
}
