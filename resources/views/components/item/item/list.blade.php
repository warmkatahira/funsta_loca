<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="item_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">商品コード</th>
                    <th class="font-thin py-1 px-2 text-center">商品JANコード</th>
                    <th class="font-thin py-1 px-2 text-center">商品名</th>
                    <th class="font-thin py-1 px-2 text-center">商品カラー</th>
                    <th class="font-thin py-1 px-2 text-center">ロケーション数</th>
                    <th class="font-thin py-1 px-2 text-center">最終更新日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($items as $item)
                    <tr class="text-left cursor-default whitespace-nowrap">
                        <td class="py-1 px-2 border">{{ $item->item_code }}</td>
                        <td class="py-1 px-2 border">{{ $item->item_jan_code }}</td>
                        <td class="py-1 px-2 border">{{ $item->item_name }}</td>
                        <td class="py-1 px-2 border">{{ $item->item_color }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($item->locations->count()) }}</td>
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($item->updated_at)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>