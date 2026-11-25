<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packing_list_product', function (Blueprint $table) {
            $table->integer('pcs_cts')->default(0)->after('ctn');
            $table->decimal('unit_cbm', 10, 3)->default(0)->after('pcs_cts');
            $table->decimal('unit_gw', 10, 2)->default(0)->after('unit_cbm');
            $table->decimal('unit_nw', 10, 2)->default(0)->after('unit_gw');
            $table->decimal('total_cbm', 10, 3)->default(0)->after('unit_nw');
            $table->decimal('total_gw', 10, 2)->default(0)->after('total_cbm');
            $table->decimal('total_nw', 10, 2)->default(0)->after('total_gw');
        });
    }

    public function down(): void
    {
        Schema::table('packing_list_product', function (Blueprint $table) {
            $table->dropColumn([
                'pcs_cts',
                'unit_cbm',
                'unit_gw',
                'unit_nw',
                'total_cbm',
                'total_gw',
                'total_nw'
            ]);
        });
    }
};
