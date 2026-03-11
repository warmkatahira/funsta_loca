<div id="navigation-bar">
    <div id="navigation_top_div" class="flex flex-col sticky top-0 z-[999] bg-theme-sub">
        <!-- ロゴ -->
        <img id="logo" src="{{ asset('image/smooth_logo.svg') }}">
        <!-- 顧客名 -->
        <p class="text-center pt-2 cursor-default">{{ SystemEnum::CUSTOMER_NAME_JP }}</p>
        <!-- システム名 -->
        <p class="text-center cursor-default">{{ SystemEnum::SYSTEM_NAME_JP }}</p>
    </div>
    <div class="flex flex-col gap-3 pt-7 pl-5">
        <!-- ダッシュボード -->
        <x-navigation-btn route="dashboard.index" label="ダッシュボード" icon="las la-home" isRightMargin="true" />
        <!-- 商品 -->
        <div class="flex flex-col gap-0.5">
            <x-navigation-btn label="商品" icon="las la-tshirt" openCloseKey="item" />
             <div class="navigation-content hidden">
                <x-navigation-btn route="item.index" label="商品" isLeftMargin="true" isRightMargin="true" />
                <x-navigation-btn route="item_upload.index" label="商品アップロード" isLeftMargin="true" isRightMargin="true" />
            </div>
        </div>
        @can('admin_check')
            <!-- システム管理 -->
             <div class="flex flex-col gap-0.5">
                <x-navigation-btn label="システム管理" icon="las la-robot" openCloseKey="system_admin" />
                <div class="navigation-content hidden">
                    <x-navigation-btn route="user.index" label="ユーザー" isLeftMargin="true" isRightMargin="true" />
                    <x-navigation-btn route="operation_log.index" label="操作ログ" isLeftMargin="true" isRightMargin="true" />
                </div>
            </div>
        @endcan
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const nav = document.getElementById('navigation-bar');
        const navScrollPosition = 'navScrollPosition';
        // ページを離れる前にスクロール位置を保存
        window.addEventListener('beforeunload', () => {
            localStorage.setItem(navScrollPosition, nav.scrollTop);
        });
        // ページロード後にスクロール位置を復元
        const saved = localStorage.getItem(navScrollPosition);
        if(saved !== null){
            nav.scrollTop = saved;
        }
    });
    const navOpenStates = 'navOpenStates';
    // ページ読み込み時：保存されている開閉状態を復元
    const openStates = JSON.parse(localStorage.getItem(navOpenStates)) || {};
    for(const key in openStates){
        if(openStates[key]){
            const $section = $(`.navigation-open-close[data-open-close-key="${key}"]`);
            const $content = $section.closest('.navigation-btn-div').next('.navigation-content');
            const $arrow = $section.find('.arrow');

            $content.removeClass('hidden');
            $arrow.addClass('open');
        }
    }
    // クリック時
    $('.navigation-open-close').on('click', function() {
        // 要素を取得
        const $content = $(this).closest('.navigation-btn-div').next('.navigation-content');
        const $arrow = $(this).find('.arrow');
        const key = $(this).data('open-close-key');
        // クラスの付け外し
        $content.toggleClass('hidden');
        $arrow.toggleClass('open');
        // 現在の開閉状態を保存
        const updatedStates = JSON.parse(localStorage.getItem(navOpenStates)) || {};
        updatedStates[key] = !$content.hasClass('hidden');
        localStorage.setItem(navOpenStates, JSON.stringify(updatedStates));
    });
</script>