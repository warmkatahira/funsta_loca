<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::create([
            'item_code'     => 'P0169TBPSP-CBK',
            'item_jan_code' => '4573280638899',
            'item_name'     => 'HAZUKI 宅配ボックス',
            'item_color'    => 'チャコールブラック',
        ]);
        Item::create([
            'item_code'     => 'P0169TBPSP-MGY',
            'item_jan_code' => '4573280642780',
            'item_name'     => 'HAZUKI 宅配ボックス',
            'item_color'    => 'ガンメタルグレー',
        ]);
        Item::create([
            'item_code'     => 'P0316TBD02-BEG',
            'item_jan_code' => '4573280644968',
            'item_name'     => 'ポスト付き宅配ボックス「デュオ」TBD-02',
            'item_color'    => 'ベージュ',
        ]);
    }
}
