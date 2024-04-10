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

        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ["sale","commission_payment","withdrawal","cash_ajustment","purchase"]);
            $table->foreignId('user_id')->constrained()->unsigned();
            $table->foreignId('cash_balance_id')->constrained()->unsigned();
            $table->decimal('amount', 10, 2);
            $table->foreignId('sale_id');
            $table->foreignId('withdrawal_id');
            $table->foreignId('cash_ajustment_id');
            $table->foreignId('commission_payment_id');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_transactions');
    }
};
