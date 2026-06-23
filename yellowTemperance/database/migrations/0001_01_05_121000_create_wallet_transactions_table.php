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
        //Pivot Table linking the Users to their roles
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('type');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /** * Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
