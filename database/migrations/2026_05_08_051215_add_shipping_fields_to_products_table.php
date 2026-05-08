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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('pcs_cts')->nullable()->after('price');

            $table->decimal('unit_cbm', 8, 3)->nullable()->after('pcs_cts');

            $table->decimal('unit_gw', 8, 2)->nullable()->after('unit_cbm');

            $table->decimal('unit_nw', 8, 2)->nullable()->after('unit_gw');

            $table->text('description')->nullable()->after('unit_nw');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'pcs_cts',
                'unit_cbm',
                'unit_gw',
                'unit_nw',
                'description'
            ]);
        });
    }
};
