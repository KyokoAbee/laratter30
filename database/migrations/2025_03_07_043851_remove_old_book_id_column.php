<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Schema::table('recommendations', function (Blueprint $table) {
        //     $table->dropColumn('old_book_id');
        // });
    }

    public function down(): void
    {
        // Schema::table('recommendations', function (Blueprint $table) {
        //     $table->string('old_book_id')->nullable()->after('user_id');
        // });
    }
};