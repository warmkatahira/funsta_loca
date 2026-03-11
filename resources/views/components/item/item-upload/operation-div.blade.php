<form method="POST" action="{{ route('item_upload.upload') }}" id="item_upload_form" enctype="multipart/form-data" class="m-0">
    @csrf
    <div class="flex flex-row gap-5">
        <div class="flex select_file">
            <label class="btn text-sm bg-btn-enter text-white py-2 px-5">
                アップロード
                <input type="file" name="select_file" class="hidden">
            </label>
        </div>
    </div>
</form>