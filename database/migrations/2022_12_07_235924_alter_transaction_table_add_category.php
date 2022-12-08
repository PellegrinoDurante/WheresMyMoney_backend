<?php

use App\Models\TransactionCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignIdFor(TransactionCategory::class, 'category_id')->nullable()->constrained('transaction_categories');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(TransactionCategory::class, 'category_id');
        });
    }
};
