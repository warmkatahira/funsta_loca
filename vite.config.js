import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // 共通
                'resources/js/app.js',
                'resources/css/app.css',
                'resources/sass/theme.scss',
                'resources/js/loading.js',
                'resources/js/search.js',
                'resources/js/file_select.js',
                'resources/js/search_date.js',
                'resources/sass/loading.scss',
                'resources/sass/navigation.scss',
                'resources/js/dropdown.js',
                'resources/js/image_fade_in.js',
                'resources/js/chart_color.js',
                'resources/js/checkbox.js',
                'resources/sass/dropdown.scss',
                'resources/sass/height_adjustment.scss',
                'resources/sass/welcome.scss',
                'resources/sass/common.scss',
                // 認証
                'resources/js/auth/register.js',
                // ダッシュボード
                'resources/js/dashboard/dashboard.js',
                // 商品
                'resources/js/item/item/item.js',
                'resources/js/item/item_upload/item_upload.js',
                // システム管理
                'resources/js/system_admin/user/user.js',
                // プロフィール
                'resources/js/profile/profile.js',
                'resources/sass/profile/profile.scss',
                'resources/sass/profile/profile_image.scss',
            ],
            refresh: true,
        }),
    ],
});
