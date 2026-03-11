<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Item;

class DashboardController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'ダッシュボード']);
        return view('dashboard')->with([
        ]);
    }

    public function ajax_get_item(Request $request)
    {
        // 商品を取得
        $items = Item::where('item_jan_code', 'LIKE', '%'.$request->item_jan_code.'%')->with('locations')->get();
        return response()->json([
            'items' => $items,
        ]);
    }
}