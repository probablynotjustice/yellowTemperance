<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auction_product_units', function (Blueprint $table) {
            $table->id();

            $table->foreignId('auction_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('product_unit_id')
                ->constrained('product_units')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['auction_id', 'product_unit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auction_product_units');
    }
};
