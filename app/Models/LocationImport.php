<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationImport extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'location_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'item_code',
        'location',
    ];
}