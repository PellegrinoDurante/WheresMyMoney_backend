<?php

use App\Models\AccessToken;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name', 255);
            $table->foreignIdFor(AccessToken::class)->nullable()->constrained();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
