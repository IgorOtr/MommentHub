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
        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folder_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('mime_type')->nullable();
            $table->enum('file_type', ['image', 'video'])->default('image');
            $table->string('google_drive_file_id')->unique();
            $table->string('google_drive_url')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('preview_url')->nullable();
            $table->string('download_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_files');
    }
};
