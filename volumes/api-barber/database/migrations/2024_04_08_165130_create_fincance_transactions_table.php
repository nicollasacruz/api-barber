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

        Schema::create('fincance_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ["commission","product_commission","sale","commission_paid","withdrawal","cash_ajustment"]);
            $table->foreignId('user_id')->constrained()->unsigned();
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fincance_transactions');
    }
};
