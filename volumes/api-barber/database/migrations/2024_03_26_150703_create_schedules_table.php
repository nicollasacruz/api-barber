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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->float('amount')->default(0);
            $table->string('status')->default('scheduled');
            $table->unsignedBigInteger('barbershop_id')->nullable();
            $table->unsignedBigInteger('barber_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            
            $table->foreign('barbershop_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('barber_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('client_id')->references('id')->on('users')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
