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
        Schema::create('feed_images', function (Blueprint $table) {
            $table->id();
            $table->string('subtitle')->nullable();
            $table->string('image');
            $table->string('url_image')->nullable();
            $table->string('url_preview')->nullable();
            $table->boolean('isShow')->default(true);
            $table->unsignedBigInteger('likes_count')->default(0);

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_images');
    }
};
