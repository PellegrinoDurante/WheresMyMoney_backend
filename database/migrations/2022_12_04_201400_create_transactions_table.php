<?php

use App\Models\Wallet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('amount');
            $table->timestamp('spent_at');
            $table->foreignIdFor(Wallet::class)->constrained();
            $table->json('metadata');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
