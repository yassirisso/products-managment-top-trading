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
        Schema::table('supplier_payments', function (Blueprint $table) {

            $table->foreignId('supplier_purchase_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();

        });
    }


    public function down(): void
    {
        Schema::table('supplier_payments', function (Blueprint $table) {

            $table->dropForeign(['supplier_purchase_id']);

            $table->dropColumn('supplier_purchase_id');

        });
    }
};
