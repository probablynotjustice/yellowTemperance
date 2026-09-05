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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('invoice_number')
                ->unique();
            $table->string('status')
                ->default('outstanding');
            $table->timestamp('issued_at');
            $table->timestamp('period_start')
                ->nullable();
            $table->timestamp('period_end')
                ->nullable();
            $table->unsignedInteger('total_bids')
                ->default(0);
            $table->unsignedInteger('total_tickets_used')
                ->default(0);
            $table->decimal('winning_bid', 10, 2)
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
