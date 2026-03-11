<x-app-layout>
    <p class="text-xl mb-2"><i class="las la-search mr-1 la-lg"></i>ロケーション検索</p>
    <div class="flex flex-col">
        <input type="tel" id="item_jan_code" name="item_jan_code" class="text-base border border-black md:w-2/5 w-4/5" placeholder="JANコード入力(含むで検索されます)" autocomplete="off">
    </div>
    <div id="result" class="mt-3"></div>
</x-app-layout>
@vite(['resources/js/dashboard/dashboard.js'])