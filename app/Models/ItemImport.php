<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemImport extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'item_code';
    // オートインクリメント無効化
    public $incrementing = false;
    // 操作可能なカラムを定義
    protected $fillable = [
        'item_code',
        'item_jan_code',
        'item_name',
        'item_color',
    ];
    // itemsテーブルとのリレーション
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_code', 'item_code');
    }
}
