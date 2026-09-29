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
        Schema::create('carsensor_import_logs', function (Blueprint $table) {
            $table->id();
            $table->string('import_id')->unique();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('source_url', 2048);
            $table->string('source_id')->nullable()->index();
            $table->enum('status', ['started', 'scraping', 'mapping', 'images', 'completed', 'failed'])->default('started');
            $table->integer('image_count')->default(0);
            $table->integer('image_success_count')->default(0);
            $table->json('mapping_warnings')->nullable();
            $table->text('error_message')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->index(['source_id', 'status']);
        });

        Schema::create('carsensor_import_temp_images', function (Blueprint $table) {
            $table->id();
            $table->string('import_id')->index();
            $table->string('original_url', 2048);
            $table->string('local_filename');
            $table->string('local_path');
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
            $table->string('mime_type')->nullable();
            $table->unsignedInteger('file_size')->nullable();
            $table->boolean('download_success')->default(false);
            $table->string('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carsensor_import_temp_images');
        Schema::dropIfExists('carsensor_import_logs');
    }
};
