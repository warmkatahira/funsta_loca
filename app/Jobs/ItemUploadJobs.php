<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
// モデル
use App\Models\Item;
use App\Models\ItemImport;
use App\Models\Job;
use App\Models\ItemUploadHistory;
// 列挙
use App\Enums\ItemUploadEnum;
// 例外
use App\Exceptions\ItemUploadException;
// その他
use Rap2hpoutre\FastExcel\FastExcel;
use Throwable;
use Illuminate\Support\Facades\Validator;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class ItemUploadJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;              // 最大実行時間を120秒に設定
    public $user_no;                    // プロパティの定義
    public $save_file_full_path;        // プロパティの定義
    public $upload_original_file_name;  // プロパティの定義

    /**
     * Create a new job instance.
     */
    public function __construct($user_no, $save_file_full_path, $upload_original_file_name)
    {
        $this->user_no = $user_no;
        $this->save_file_full_path = $save_file_full_path;
        $this->upload_original_file_name = $upload_original_file_name;
    }

    /* public function queue($queue, $job)
    {
        $queue->pushOn('item_upload', $job);
    } */

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 現在のjob_idを取得
        $job_id = $this->job->getJobId();
        // カラムにパラメータの値を更新
        Job::where('id', $job_id)->update([
            'user_no'           => $this->user_no,
            'upload_file_path'  => $this->save_file_full_path,
        ]);
        // ジョブを管理するテーブルにレコードを追加
        $item_upload_history = ItemUploadHistory::create([
            'job_id'            => $job_id,
            'user_no'           => $this->user_no,
            'upload_file_path'  => $this->save_file_full_path,
            'upload_file_name'  => $this->upload_original_file_name,
        ]);
        // 全データを取得
        $all_line = (new FastExcel)->import($this->save_file_full_path);
        // インポートしたデータのヘッダーを取得
        $data_header = array_keys($all_line[0]);
        // ヘッダーを日本語から英語に変換
        $headers = $this->changeHeaderEn($data_header);
        // ファイルのデータを配列化（これをしないとチャンク処理できない）
        $all_line = $all_line->toArray();
        // チャンクサイズの設定
        $chunk_size = 500;
        // チャンクサイズ毎に分割
        $chunks = array_chunk($all_line, $chunk_size);
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // テーブルをクリア
        $this->clearItemImport();
        try {
            $proc_count = DB::transaction(function () use ($headers, $chunk_size, $chunks, $nowDate, $item_upload_history){
                // チャンク毎のループ処理
                foreach ($chunks as $chunk_index => $chunk){
                    // 追加するデータを配列に格納（同時にバリデーションも実施）
                    $data = $this->setArrayImportData($chunk, $headers, $chunk_size, $chunk_index);
                    // バリデーションエラーがある場合
                    if(count(array_filter($data['validation_error'])) != 0){
                        throw new ItemUploadException('データが正しくない為、アップロードできませんでした。', $data['validation_error'], $nowDate, $item_upload_history);
                    }
                    // item_importsテーブルへ追加
                    $this->createArrayImportData($data['create_data']);
                }
                // itemsテーブルへ追加処理
                return $this->procCreate($headers);
            });
        } catch (ItemUploadException $e){
            // 渡された内容を取得
            $validation_error = $e->getValidationError();
            $nowDate = $e->getNowDate();
            $item_upload_history = $e->getItemUploadHistory();
            // エラーファイルを作成してテーブルを更新
            $this->item_upload_error_export($validation_error, $nowDate, $item_upload_history, $e->getMessage());
            return;
        }
        // 完了フラグを更新
        ItemUploadHistory::where('item_upload_history_id', $item_upload_history->item_upload_history_id)->update([
            'status' => '完了',
            'message' => '処理件数：'.$proc_count.'件',
        ]);
    }

    public function changeHeaderEn($data_header)
    {
        // 1行のデータを格納する配列をセット
        $param = [];
        // 追加先テーブルのカラム名に合わせて配列を整理
        foreach($data_header as $header){
            // 英語カラムを定義している配列から取得
            $en_column = Item::column_en_change($header);
            // カラムが空ではない場合
            if($en_column != ''){
                // 配列に変換した英語カラムを格納
                $param[] = $en_column;
            }
        }
        return $param;
    }

    // テーブルをクリア
    public function clearItemImport()
    {
        // 追加先のテーブルをクリア
        ItemImport::query()->delete();
    }

    public function setArrayImportData($chunk, $headers, $chunk_size, $chunk_index)
    {
        // 配列をセット
        $create_data = [];
        // 取得したレコードの分だけループ
        foreach ($chunk as $line){
            // UTF-8形式に変換した1行分のデータを取得
            $line = $line;
            // 1行のデータを格納する配列をセット
            $param = [];
            // 追加先テーブルのカラム名に合わせて配列を整理
            foreach($line as $key => $value){
                // 英語カラムを定義している配列から取得
                $en_column = Item::column_en_change($key);
                // カラムが空ではない場合
                if($en_column != ''){
                    // 値の調整を行う
                    $adjustment_value = $this->valueAdjustment($key, $value);
                    // 配列に変換した英語カラムを格納
                    $param[$en_column] = $adjustment_value;
                }
            }
            // 追加用の配列に整理した情報を格納
            $create_data[] = $param;
        }
        // バリデーション（共通）
        $validation_error = $this->commonValidation($create_data, $headers, $chunk_size, $chunk_index);
        // エラーメッセージがあればバリデーションエラーを配列に格納
        if(!empty($validation_error)){
            return compact('validation_error');
        }
        return compact('create_data', 'validation_error');
    }

    public function valueAdjustment($key, $value)
    {
        // 特定のキーのみ値の調整を行う
        switch ($key){
            case '商品コード':
            case 'JANコード':
                // 半角・全角スペースを取り除いている
                $adjustment_value = str_replace(array(" ", "　", "'"), "", $value);
                break;
            default:
                // 何もしない
                $adjustment_value = $value;
                break;
        }
        return $adjustment_value === '' ? null : $adjustment_value;
    }

    public function commonValidation($params, $headers, $chunk_size, $chunk_index)
    {
        // ルールを格納する配列をセット
        $rules = [];
        // バリデーションルールを定義
        foreach($headers as $column){
            switch ($column){
                case 'item_code':
                    $rules += ['*.'.$column => 'required|max:255'];
                    break;
                case 'item_jan_code':
                    $rules += ['*.'.$column => 'nullable|max:13'];
                    break;
                case 'item_name':
                    $rules += ['*.'.$column => 'required|max:255'];
                    break;
                case 'item_color':
                    $rules += ['*.'.$column => 'nullable|max:255'];
                    break;
                default:
                    break;
            }
        }
        // バリデーションエラーメッセージを定義
        $messages = [
            'required'  => ':attributeは必須です。',
            'max'       => ':attribute（:input）は:max文字以内で入力して下さい。',
        ];
        // バリデーションエラー項目を定義
        $attributes = [
            '*.item_code'                   => '商品コード',
            '*.item_jan_code'               => '商品JANコード',
            '*.item_name'                   => '商品名',
            '*.item_color'                  => '商品カラー',
        ];
        // バリデーション実施
        return $this->procValidation($params, $rules, $messages, $attributes, $chunk_size, $chunk_index);
    }

    public function procValidation($params, $rules, $messages, $attributes, $chunk_size, $chunk_index)
    {
        // 配列をセット
        $validation_error = [];
        // バリデーション実施
        $validator = Validator::make($params, $rules, $messages, $attributes);
        // バリデーションエラーの分だけループ
        foreach($validator->errors()->getMessages() as $key => $value){
            // 値を「.」で分割
            $key_explode = explode('.', $key);
            // メッセージを格納
            $validation_error[] = [
                'エラー行数' => ($key_explode[0] + 2) + ($chunk_size * $chunk_index) . '行目',
                'エラー内容' => $value[0],
            ];
        }
        return $validation_error;
    }

    public function createArrayImportData($create_data)
    {
        // 追加用の配列に入っている情報をテーブルに追加
        ItemImport::insert($create_data);
    }

    public function procCreate($headers)
    {
        // 追加先のテーブルをクリア
        Item::query()->delete();
        // itemsに存在しないレコードを取得
        $create_item = ItemImport::doesntHave('item')->select(array_map(function ($column){
            return $column;
        }, $headers))->get()->toArray();
        // itemsテーブルに追加
        Item::upsert($create_item, 'item_code');
        return count($create_item);
    }

    public function item_upload_error_export($validation_error, $nowDate, $item_upload_history, $message)
    {
        // チャンクサイズを設定
        $chunk_size = 500;
        // チャンクサイズ毎に分割
        $chunks = array_chunk($validation_error, $chunk_size);
        // ファイル名を設定
        $error_file_name = '商品アップロードエラー_'.$nowDate->format('Y-m-d H-i-s').'_'.$item_upload_history->user_no.'.csv';
        // 保存場所を設定
        $csvFilePath = storage_path('app/public/export/item_upload_error/'.$error_file_name);
        // エラーファイル名を更新
        ItemUploadHistory::where('item_upload_history_id', $item_upload_history->item_upload_history_id)->update([
            'error_file_name' => $error_file_name,
            'status' => '失敗',
            'message' => $message,
        ]);
        // ヘッダ行を書き込む
        $header = ['エラー行数', 'エラー内容'];
        $csvContent = "\xEF\xBB\xBF" . implode(',', $header) . "\n";
        // チャンク毎のループ処理
        foreach ($chunks as $chunk){
            // レコード毎のループ処理
            foreach ($chunk as $item){
                // CSV形式で内容をセット
                $row = [$item['エラー行数'], $item['エラー内容']];
                $csvContent .= implode(',', $row) . "\n";
            }
        }
        // ファイルに出力
        file_put_contents($csvFilePath, $csvContent);
        return;
    }
}