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
        Schema::create('operations', static function (Blueprint $table) {
            $table->id();

            $table->foreignId('currency_id')
                ->constrained('currencies')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->enum('operation', ['add', 'subtract', 'multiply', 'divide', 'min', 'max', 'avg', 'total', 'discount']);
            $table->float('operand1', 12, 3);
            $table->float('operand2', 12, 3);
            $table->float('result', 12, 3);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations', static function (Blueprint $table) {
            $table->dropConstrainedForeignId('currency_id');
        });
        Schema::dropIfExists('operations');
    }
};
