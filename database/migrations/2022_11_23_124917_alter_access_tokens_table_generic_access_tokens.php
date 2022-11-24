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
        Schema::table('access_tokens', function (Blueprint $table) {
            $table->string('access_token', 1024)->change();
            $table->string('refresh_token', 1024)->nullable()->change();
            $table->string('name', 128)->after('updated_at');
            $table->string('type', 64)->after('name');
            $table->string('provider', 128)->after('type');
            $table->dropColumn('created');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('access_tokens', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->integer('created');
            $table->dropColumn('provider');
            $table->dropColumn('type');
            $table->dropColumn('name');
            $table->string('refresh_token', 255)->change();
            $table->string('access_token', 255)->change();
        });
    }
};
