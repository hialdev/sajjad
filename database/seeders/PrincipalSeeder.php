<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

use App\Models\Principal;
use App\Models\PrincipalPic;
use App\Models\PrincipalAddress;

class PrincipalSeeder extends Seeder
{
    public function run(): void
    {
        $faker  = Faker::create('id_ID');   // locale Indonesia
        $total  = 40;                       // berapa principal dummy ingin dibuat

        DB::transaction(function () use ($faker, $total) {

            for ($i = 0; $i < $total; $i++) {

                /* ──────── Principal ──────── */
                $principal = Principal::create([
                    'name'        => 'PT. ' . $faker->unique()->company,
                    'npwp'        => $faker->numerify('###############'), // 15 digit
                    'email'       => $faker->unique()->companyEmail,
                    'phone'       => $faker->phoneNumber,
                    'address'     => $faker->streetAddress,
                    'city'        => strtoupper($faker->city),
                    'postal_code' => $faker->postcode,
                ]);

                /* ──────── PIC ──────── */
                PrincipalPic::create([
                    'principal_id' => $principal->id,
                    'name'         => $faker->name('male'),
                    'email'        => $faker->unique()->safeEmail,
                    'phone'        => $faker->phoneNumber,
                ]);

                /* ──────── Alamat Tambahan ──────── */
                PrincipalAddress::create([
                    'principal_id' => $principal->id,
                    'name'         => 'Representative Office - ' . $principal->city,
                    'address'      => $faker->streetAddress,
                    'city'         => $principal->city,
                    'postal_code'  => $principal->postal_code,
                ]);
            }

        });
    }
}
