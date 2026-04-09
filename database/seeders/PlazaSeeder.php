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
        Plaza::factory(1)->create()->each(function ($plaza) {

            // 2. For each Plaza, create 10 Units
            Unit::factory(1)->create([
                'plaza_id' => $plaza->id,
            ])->each(function ($unit) use ($plaza) {

                // 3. Create 2 Residents (Users) for each Unit
                 User::factory(3)->create([
                    'unit_id' => $unit->id,
                    'plaza_id' => $plaza->id,
                    'role' => 'member',
                ]);

                // 4. Record Unit History (e.g., when the first resident is added)
                // UnitHistory::create([
                //     'plaza_id' => $plaza->id,
                //     'unit_id' => $unit->id,
                //     'status' => 'Vacant',
                //     'notes' => 'Unit assigned to residents via Seeder',
                //     'changed_by' => $residents->first()->id, // Assigning to the first user created
                // ]);
            });
        });
        PlazaSetting::factory(1)->create([
            'plaza_id' => 1,
        ]);
    }
}
