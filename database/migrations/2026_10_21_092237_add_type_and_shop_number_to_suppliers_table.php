<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->enum('type', ['shop', 'factory'])->default('factory')->after('name');
            $table->string('shop_number')->nullable()->unique()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropUnique(['shop_number']);
            $table->dropColumn(['type', 'shop_number']);
        });
    }
};