<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_marks', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "ABC / NY-123"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_marks');
    }
};