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

        Schema::create('commission_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->unsigned();
            $table->decimal('amount', 10, 2);
            $table->foreignId('finance_transaction_id')->constrained()->unsigned();
            $table->dateTime('closed_at');
            $table->dateTime('payed_at');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_payments');
    }
};
