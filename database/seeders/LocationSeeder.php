<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\Location;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::create([
            'item_code'     => 'P0169TBPSP-CBK',
            'location'      => 'A-001',
        ]);
        Location::create([
            'item_code'     => 'P0169TBPSP-CBK',
            'location'      => 'A-009',
        ]);
        Location::create([
            'item_code'     => 'P0169TBPSP-CBK',
            'location'      => 'A-120',
        ]);
        Location::create([
            'item_code'     => 'P0169TBPSP-MGY',
            'location'      => 'B-003',
        ]);
        Location::create([
            'item_code'     => 'P0316TBD02-BEG',
            'location'      => 'C-008',
        ]);
        Location::create([
            'item_code'     => 'P0316TBD02-BEG',
            'location'      => 'S-221',
        ]);
    }
}
