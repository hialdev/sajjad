<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

use App\Models\Partner;
use App\Models\PartnerPic;
use App\Models\PartnerAddress;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $faker  = Faker::create('id_ID');   // locale Indonesia
        $total  = 50;                       // berapa partner yang ingin dibuat

        DB::transaction(function () use ($faker, $total) {

            for ($i = 0; $i < $total; $i++) {

                /* ────────── Partner ────────── */
                $partner = Partner::create([
                    'name'         => 'PT. ' . $faker->unique()->company,
                    'npwp'         => $faker->numerify('###############'), // 15 digit
                    'email'        => $faker->unique()->companyEmail,
                    'phone'        => $faker->phoneNumber,
                    'address'      => $faker->streetAddress,
                    'city'         => strtoupper($faker->city),
                    'postal_code'  => $faker->postcode,
                ]);

                /* ────────── PIC ────────── */
                PartnerPic::create([
                    'partner_id' => $partner->id,
                    'name'       => $faker->name('male'),
                    'email'      => $faker->unique()->safeEmail,
                    'phone'      => $faker->phoneNumber,
                ]);

                /* ────────── Alamat Tambahan ────────── */
                PartnerAddress::create([
                    'partner_id'  => $partner->id,
                    'name'        => 'Representative Office - ' . $partner->city,
                    'address'     => $faker->streetAddress,
                    'city'        => $partner->city,
                    'postal_code' => $partner->postal_code,
                ]);
            }

        });
    }
}
