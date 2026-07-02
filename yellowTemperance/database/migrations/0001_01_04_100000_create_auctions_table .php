<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auctions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('starting_bid', 10, 2);

            $table->decimal('current_bid', 10, 2)
                ->default(0);

            $table->decimal('reserve_price', 10, 2)
                ->nullable();

            $table->timestamp('starts_at');

            $table->timestamp('ends_at');

            $table->string('status')
                ->default('active');

            $table->foreignId('winner_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }
};
