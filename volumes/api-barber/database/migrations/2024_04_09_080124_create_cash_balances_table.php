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
        Schema::disableForeignKeyConstraints();

        Schema::create('cash_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receptionist_id')->constrained()->unsigned();
            $table->foreignId('manager_id')->constrained()->unsigned();
            $table->decimal('start_balance', 10, 2);
            $table->decimal('balance', 10, 2);
            $table->decimal('final_balance', 10, 2);
            $table->decimal('cash', 10, 2);
            $table->decimal('card', 10, 2);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_balances');
    }
};