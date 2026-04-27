<?php

namespace Database\Seeders;

use App\Models\Plaza;
use App\Models\PlazaSetting;
use App\Models\Unit;
use App\Models\UnitHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

class PlazaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create 3 Plazas
        // Plaza::factory(1)->create()->each(function ($plaza) {

        //     // 2. For each Plaza, create 10 Units
        //     // Unit::factory(1)->create([
        //     //     'plaza_id' => $plaza->id,
        //     // ])->each(function ($unit) use ($plaza) {

        //         // 3. Create 2 Residents (Users) for each Unit
                 User::factory(1)->create([
                    // 'unit_id' => $unit->id,
                    'email' => 'admin@admin.com',
                    // 'plaza_id' => $plaza->id,
                    'role' => 'chairman',
                ]);


        //     });
        // });
        // PlazaSetting::factory(1)->create([
        //     'plaza_id' => 1,
        // ]);
    }
}
