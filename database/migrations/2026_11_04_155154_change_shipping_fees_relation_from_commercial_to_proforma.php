<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_fees', function (Blueprint $table) {
            // Drop old FK
            $table->dropForeign(['commercial_invoice_id']);
            $table->dropColumn('commercial_invoice_id');

            // Add new FK
            $table->foreignId('proforma_invoice_id')
                ->nullable()
                ->after('shipping_company_id')
                ->constrained('proforma_invoices')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('shipping_fees', function (Blueprint $table) {
            $table->dropForeign(['proforma_invoice_id']);
            $table->dropColumn('proforma_invoice_id');

            $table->foreignId('commercial_invoice_id')
                ->nullable()
                ->after('shipping_company_id')
                ->constrained('commercial_invoices')
                ->nullOnDelete();
        });
    }
};
