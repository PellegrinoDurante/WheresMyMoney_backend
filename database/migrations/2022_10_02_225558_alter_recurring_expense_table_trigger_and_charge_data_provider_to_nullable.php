<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('recurring_expenses', function (Blueprint $table) {
            $table->json('trigger')->nullable()->change();
            $table->json('charge_data_provider')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('recurring_expenses', function (Blueprint $table) {
            $table->json('trigger')->change();
            $table->json('charge_data_provider')->change();
        });
    }
};
