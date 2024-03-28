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
        Schema::table('barbershops', function (Blueprint $table) {
            $table->json('businessHours')->default(json_encode([
                ["08:00-20:00"],
                ["08:00-20:00"],
                ["08:00-20:00"],
                ["08:00-20:00"],
                ["08:00-20:00"],
                ["08:00-20:00"],
                ["08:00-20:00"],
            ]));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barbershops', function (Blueprint $table) {
            $table->dropColumn('businessHours');
        });
    }
};
