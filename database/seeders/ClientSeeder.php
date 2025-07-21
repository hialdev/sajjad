<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

use App\Models\Client;
use App\Models\ClientPic;
use App\Models\ClientAddress;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $faker  = Faker::create('id_ID');   // locale Indonesia
        $total  = 40;                       // berapa client dummy yang diinginkan

        DB::transaction(function () use ($faker, $total) {

            for ($i = 0; $i < $total; $i++) {

                /* ──────── Client ──────── */
                $client = Client::create([
                    'name'        => $faker->company,
                    'npwp'        => $faker->numerify('###############'), // 15 digit
                    'email'       => $faker->unique()->companyEmail,
                    'phone'       => $faker->phoneNumber,
                    'address'     => $faker->streetAddress,
                    'city'        => strtoupper($faker->city),
                    'postal_code' => $faker->postcode,
                ]);

                /* ──────── PIC ──────── */
                ClientPic::create([
                    'client_id' => $client->id,
                    'name'      => $faker->name('male'),
                    'email'     => $faker->unique()->safeEmail,
                    'phone'     => $faker->phoneNumber,
                ]);

                /* ──────── Alamat Proyek ──────── */
                ClientAddress::create([
                    'client_id'   => $client->id,
                    'name'        => 'Proyek ' . $faker->city,
                    'address'     => $faker->streetAddress,
                    'city'        => $client->city,
                    'postal_code' => $faker->postcode,
                ]);
            }

        });
    }
}
