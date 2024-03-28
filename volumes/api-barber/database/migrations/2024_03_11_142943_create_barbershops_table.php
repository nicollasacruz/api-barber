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
        Schema::create('barbershops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('email');
            $table->string('address');
            $table->timestamps();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->unsignedBigInteger('receptionist_id')->nullable();

            $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('receptionist_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barbershops');
    }
};
