<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table){
            $table->string('item_code', 255)->primary();
            $table->string('item_jan_code', 13)->nullable();
            $table->string('item_name', 255);
            $table->string('item_color', 255)->nullable();
            $table->timestamps();
        });
        // 文字セット・照合順序を変更
        DB::statement("ALTER TABLE items MODIFY item_code VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin UNIQUE");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
