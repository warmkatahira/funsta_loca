$(document).on("change", "#item_jan_code", function(){
    // AJAX通信のURLを定義
    const ajax_url = '/dashboard/ajax_get_item';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: ajax_url,
        type: 'GET',
        data: {
            item_jan_code: $('#item_jan_code').val(),
        },
        dataType: 'json',
        success: function(data){
            try {
                $('#result').empty();
                if(!data['items'] || data['items'].length === 0){
                    $('#result').append(`<p class="text-base">検索結果なし</p>`);
                    return;
                }
                $.each(data['items'], function(index, item) {
                    let locationsHtml = '';
                    $.each(item['locations'], function(i, location) {
                        locationsHtml += `<p class="bg-theme-main text-sm rounded-md col-span-4 md:col-span-2 text-center py-2">${location['location']}</p>`;
                    });
                    // ロケーションが無い場合
                    if(!locationsHtml){
                        locationsHtml = `<p class="text-sm col-span-12">ロケーションなし</p>`;
                    }
                    $('#result').append(`
                        <div class="border border-gray-300 rounded mb-2 bg-white">
                            <button class="btn accordion-toggle w-full text-left bg-orange-200 px-4 py-2 font-semibold text-sm">
                                <span class="text-xl text-red-600">${item['item_jan_code']}</span>
                                <br>
                                ${item['item_name']}${item['item_color'] ? ' / ' + item['item_color'] : ''}
                            </button>
                            <div class="accordion-body grid grid-cols-12 gap-2 p-3" style="display:none">
                                ${locationsHtml}
                            </div>
                        </div>
                    `);
                });
            } catch (e) {
                alert('失敗しました。');
            }
        },
        error: function(){
            alert('失敗しました。');
        }
    });
})

// アコーディオンの開閉
$(document).on('click', '.accordion-toggle', function() {
    $(this).next('.accordion-body').toggle();
});