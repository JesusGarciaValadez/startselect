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
        Schema::create('conversions', static function (Blueprint $table) {
            $table->id();

            $table->foreignId('from_currency_id')
                ->constrained('currencies', 'id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('to_currency_id')
                ->constrained('currencies', 'id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->float('amount', 12, 3);
            $table->float('conversion', 12, 3);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversions');
    }
};
