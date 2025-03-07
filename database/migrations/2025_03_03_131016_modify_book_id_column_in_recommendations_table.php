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
        // 外部キー制約を削除
        Schema::table('recommendations', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
        });

        // book_idカラムを文字列型に変更
        Schema::table('recommendations', function (Blueprint $table) {
            // book_idカラムを文字列型に変更
            $table->string('book_id', 255)->nullable()->change();
        });

        // books.idカラムを文字列型に変更
        Schema::table('books', function (Blueprint $table) {
            $table->string('id', 255)->change();
        });
        
        // 外部キー制約を再追加
        Schema::table('recommendations', function (Blueprint $table) {
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 外部キー制約を削除
        Schema::table('recommendations', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
        });

        // book_idカラムを元のbigint型に戻す
        Schema::table('recommendations', function (Blueprint $table) {
            $table->bigInteger('book_id')->unsigned()->nullable(false)->change();
        });

        // books.idカラムを元の型に戻す
        Schema::table('books', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary()->change();
        });

        // 外部キー制約を再追加
        Schema::table('recommendations', function (Blueprint $table) {
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });



    }
};
