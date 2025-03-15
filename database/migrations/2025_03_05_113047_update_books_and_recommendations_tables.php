<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {

        Schema::table('books', function (Blueprint $table) {
            $table->string('thumbnail')->nullable();
            $table->string('google_books_id')->nullable()->unique();
            $table->string('description')->nullable();
        });

        // 外部キー制約を一時的に削除
        // Schema::table('recommendations', function (Blueprint $table) {
        //     $table->dropForeign('recommendations_book_id_foreign');
        // });

        // // recommendations テーブルを修正（book_idのリネーム）
        // Schema::table('recommendations', function (Blueprint $table) {
        //     $table->renameColumn('book_id', 'old_book_id');
        // });

        // // booksテーブルをバックアップ
        // DB::statement('CREATE TABLE books_backup LIKE books');
        // DB::statement('INSERT INTO books_backup SELECT * FROM books');

        // // booksテーブルを削除して再作成
        // Schema::dropIfExists('books');
        // Schema::create('books', function (Blueprint $table) {
        //     $table->id(); // bigint unsigned AUTO_INCREMENT
        //     $table->string('google_books_id')->nullable()->unique();
        //     $table->string('title');
        //     $table->string('author');
        //     $table->string('isbn')->nullable();
        //     $table->string('publisher')->nullable();
        //     $table->date('published_date')->nullable();
        //     $table->string('thumbnail')->nullable();
        //     $table->text('description')->nullable();
        //     $table->timestamps();
        // });

        // // // バックアップから必要なデータを新しいbooksテーブルに移行
        // // DB::statement("
        // //     INSERT INTO books (google_books_id, title, author, isbn, publisher, published_date, thumbnail, description, created_at, updated_at)
        // //     SELECT id as google_books_id, title, author, isbn, publisher, published_date, thumbnail, description, created_at, updated_at
        // //     FROM books_backup
        // // ");

        // // recommendationsテーブルに新しいbook_idカラムを追加
        // Schema::table('recommendations', function (Blueprint $table) {
        //     $table->unsignedBigInteger('book_id')->nullable()->after('old_book_id');
        // });

        // // recommendationsテーブルのbook_idを更新
        // DB::statement("
        //     UPDATE recommendations r
        //     JOIN books b ON r.old_book_id = b.google_books_id
        //     SET r.book_id = b.id
        //     WHERE r.old_book_id IS NOT NULL
        // ");

        // // 外部キー制約を再設定
        // Schema::table('recommendations', function (Blueprint $table) {
        //     $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        // });

        // // バックアップテーブルを削除（オプション）
        // Schema::dropIfExists('books_backup');
    }

    public function down(): void
    {
        // 
    }
};