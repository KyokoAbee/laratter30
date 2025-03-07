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

        // recommendationsテーブルを修正（カラム追加とリネーム）
        Schema::table('recommendations', function (Blueprint $table) {
            // 既存のbook_idカラムをrenameせずに残す
            // 新しいカラムを追加
            $table->unsignedBigInteger('new_book_id')->nullable();
        });

        // booksテーブルを削除せずに新しいテーブルを作成
        Schema::create('books_new', function (Blueprint $table) {
            $table->id(); // bigint unsigned AUTO_INCREMENT
            $table->string('google_books_id')->nullable()->unique();
            $table->string('title');
            $table->string('author');
            $table->string('isbn')->nullable();
            $table->string('publisher')->nullable();
            $table->date('published_date')->nullable();
            $table->string('thumbnail')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 既存のbooksデータを新しいテーブルに移行
        DB::statement("
            INSERT INTO books_new (google_books_id, title, author, isbn, publisher, published_date, thumbnail, description, created_at, updated_at)
            SELECT id as google_books_id, title, author, isbn, publisher, published_date, thumbnail, description, created_at, updated_at
            FROM books
        ");

        // recommendationsの新しいbook_idを更新
        DB::statement("
            UPDATE recommendations r
            JOIN books_new b ON r.book_id = b.google_books_id
            SET r.new_book_id = b.id
            WHERE r.book_id IS NOT NULL
        ");

        // 外部キー制約を再度有効化
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        // ロールバック処理
        Schema::dropIfExists('books_new');
        
        Schema::table('recommendations', function (Blueprint $table) {
            $table->dropColumn('new_book_id');
        });
    }
};