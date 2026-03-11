<?php

namespace App\Enums;

enum ItemUploadEnum
{
    // アップロードに必要なヘッダーを定義
    const UPLOAD_REQUIRED_HEADER = [
        '商品コード',
        'JANコード',
        '商品名',
        '商品カラー',
    ];
}