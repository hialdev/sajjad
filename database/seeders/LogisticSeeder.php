<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

use App\Models\Logistic;
use App\Models\LogisticAddress;

class LogisticSeeder extends Seeder
{
    public function run(): void
    {
        $faker  = Faker::create('id_ID');   // locale Indonesia
        $total  = 30;                       // berapa logistic dummy ingin dibuat

        DB::transaction(function () use ($faker, $total) {

            for ($i = 0; $i < $total; $i++) {

                /* ──────── Logistic ──────── */
                $companyName = $faker->company;         // contoh: "PT Kargo Nusantara"
                $city        = $faker->city;

                $logistic = Logistic::create([
                    'name'        => $companyName,
                    'npwp'        => $faker->numerify('###############'), // 15 digit
                    'email'       => $faker->unique()->companyEmail,
                    'phone'       => $faker->phoneNumber,
                    'address'     => $faker->streetAddress,
                    'city'        => strtoupper($city),
                    'postal_code' => $faker->postcode,
                    'cp_name'     => $faker->name,
                    'cp_email'    => $faker->unique()->safeEmail,
                    'cp_phone'    => $faker->phoneNumber,
                ]);

                /* ──────── Alamat Lainnya ──────── */
                LogisticAddress::create([
                    'logistic_id' => $logistic->id,
                    'name'        => 'Operasional ' . $faker->city,          // mis. "Gudang Timur"
                    'address'     => $faker->streetAddress . ', ' . strtoupper($city),
                    'city'        => strtoupper($city),
                    'postal_code' => $faker->postcode,
                ]);
            }
        });
    }
}
