<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commercial_invoices', function (Blueprint $table) {
            // Default 0 means no discount
            $table->decimal('discount_percent', 5, 2)->default(0.00)->after('currency');
        });
    }

    public function down(): void
    {
        Schema::table('commercial_invoices', function (Blueprint $table) {
            $table->dropColumn('discount_percent');
        });
    }
};