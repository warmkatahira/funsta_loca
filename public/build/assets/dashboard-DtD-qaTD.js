$(document).on("change","#item_jan_code",function(){$.ajax({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},url:"/dashboard/ajax_get_item",type:"GET",data:{item_jan_code:$("#item_jan_code").val()},dataType:"json",success:function(o){try{if($("#result").empty(),!o.items||o.items.length===0){$("#result").append('<p class="text-base">検索結果なし</p>');return}$.each(o.items,function(n,t){let e="";$.each(t.locations,function(s,a){e+=`<p class="bg-white border border-black text-2xl rounded-md col-span-4 md:col-span-2 text-center py-2">${a.location}</p>`}),e||(e='<p class="text-sm col-span-12">ロケーションなし</p>'),$("#result").append(`
                        <div class="border border-gray-200 rounded mb-2 bg-white">
                            <button class="btn accordion-toggle w-full text-left bg-white px-4 py-2 font-semibold text-base">
                                <span class="text-xl text-red-600">${t.item_jan_code}</span>
                                <br>
                                ${t.item_name}${t.item_color?" / "+t.item_color:""}
                            </button>
                            <div class="accordion-body grid grid-cols-12 gap-2 p-3 bg-gray-300" style="display:none">
                                ${e}
                            </div>
                        </div>
                    `)})}catch{alert("失敗しました。")}},error:function(){alert("失敗しました。")}})});$(document).on("click",".accordion-toggle",function(){$(this).next(".accordion-body").toggle()});
