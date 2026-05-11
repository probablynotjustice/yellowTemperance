<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** * Runs migrations. */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            //Test.
            $table->string('name');
            $table->bigInteger('price');
        });
    }

    /** * Reverse the migrations.  */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
