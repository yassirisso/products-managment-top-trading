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
        Schema::create('client_product', function (Blueprint $table) {
            $table->foreignId('client_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->decimal('price', 10, 2);
            $table->timestamps();
            $table->primary(['client_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
