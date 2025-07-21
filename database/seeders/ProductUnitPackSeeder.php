<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

use App\Models\Unit;
use App\Models\ProductType;
use App\Models\Product;
use App\Models\Pack;

class  ProductUnitPackSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');   // bebas ganti locale

        DB::transaction(function () use ($faker) {

            /* ───── 1. Unit baku (idempotent) ───── */
            $unitMap = collect([
                ['name' => 'Liter',    'code' => 'L'],
                ['name' => 'Kilogram', 'code' => 'Kg'],
                ['name' => 'Pack',     'code' => 'pcs'],
                ['name' => 'Sak',      'code' => 'zak'],
            ])->mapWithKeys(function ($u) {
                $unit = Unit::firstOrCreate(
                    ['code' => $u['code']],
                    ['name' => $u['name']]
                );
                return [$u['name'] => $unit->id];
            });

            /* ───── 2. ProductType (kategori) ───── */
            $typeIds = [];
            $totalTypes = 10;

            for ($i = 0; $i < $totalTypes; $i++) {
                $ptname = $faker->unique()->word();
                $ptcode   = strtoupper(substr($ptname, 0, 3));
                $type = ProductType::create([
                    'code' => $ptcode,
                    'name' => 'Produk Tipe '.$ptname,
                    'type' => $faker->randomElement(['satuan', 'meteran']),
                ]);
                $typeIds[] = $type->id;
            }

            /* ───── 3. Product dummy ───── */
            $totalProducts = 30;

            for ($i = 0; $i < $totalProducts; $i++) {
                $unitName = $faker->randomElement($unitMap->keys()->all());
                $productTypeId = $faker->randomElement($typeIds);

                Product::create([
                    'name'            => 'Produk ' . strtoupper($faker->unique()->bothify('???')),
                    'unit_id'         => $unitMap[$unitName],
                    'price_per_unit'  => $faker->numberBetween(10_000, 500_000), // 10–500 rb
                    'height'          => $faker->numberBetween(10, 300),         // cm
                    'width'           => $faker->numberBetween(10, 300),         // cm
                    'product_type_id' => $productTypeId,
                ]);
            }

            /* ───── 4. Pack dummy ───── */
            $totalPacks = 10;

            for ($i = 0; $i < $totalPacks; $i++) {
                $unitName = $faker->randomElement($unitMap->keys()->all());

                Pack::create([
                    'name'     => $faker->randomElement(['Drum', 'Box', 'Peti', 'Karung']) . ' ' . $faker->randomNumber(2),
                    'capacity' => $faker->numberBetween(50, 1_000),
                    'unit_id'  => $unitMap[$unitName],
                ]);
            }
        });
    }
}
