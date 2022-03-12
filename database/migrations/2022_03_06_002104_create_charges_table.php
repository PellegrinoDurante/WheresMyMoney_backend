<?php

use App\Models\RecurringExpense;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("charges", function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(RecurringExpense::class);
            $table->integer("amount", unsigned: true);
            $table->dateTime("charged_at");
            $table->boolean("draft");
            // TODO: add date for draft?
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charges');
    }
};
