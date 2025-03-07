<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 外部キー制約を一時的に無効化
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // 古いbooksテーブルのリネーム
        Schema::rename('books', 'books_old');
        Schema::rename('books_new', 'books');

        // recommendationsテーブルの調整
        Schema::table('recommendations', function (Blueprint $table) {
            // new_book_idをbook_idにリネーム前に外部キーをセット
            $table->foreign('new_book_id', 'recommendations_new_book_id_foreign')
                  ->references('id')
                  ->on('books')
                  ->onDelete('cascade');
        });

        // 古いカラムを削除し、新しいカラムをリネーム
        Schema::table('recommendations', function (Blueprint $table) {
            $table->dropColumn('book_id');
        });

        Schema::table('recommendations', function (Blueprint $table) {
            $table->renameColumn('new_book_id', 'book_id');
        });

        // 古いテーブルを削除
        Schema::dropIfExists('books_old');

        // 外部キー制約を再度有効化
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        // ロールバック処理
    }
};