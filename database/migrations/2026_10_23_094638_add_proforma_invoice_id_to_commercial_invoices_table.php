<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commercial_invoices', function (Blueprint $table) {
            $table->foreignId('proforma_invoice_id')
                ->nullable()
                ->after('client_id')
                ->constrained('proforma_invoices')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('commercial_invoices', function (Blueprint $table) {
            $table->dropForeign(['proforma_invoice_id']);
            $table->dropColumn('proforma_invoice_id');
        });
    }
};