<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('blog_posts', 'category')) {
            Schema::table('blog_posts', function (Blueprint $table) {
                $table->string('category')->default('general')->after('slug')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('blog_posts', 'category')) {
            Schema::table('blog_posts', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }
    }
};
