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

        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receptionist_id')->constrained()->unsigned();
            $table->foreignId('manager_id')->nullable()->constrained()->unsigned();
            $table->foreignId('finance_transaction_id')->nullable()->constrained()->unsigned();
            $table->decimal('price', 10, 2);
            $table->boolean('status')->default(0);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
