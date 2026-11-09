<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('receiving_notes', function (Blueprint $table) {
            // Drop the old FK
            $table->dropForeign(['proforma_invoice_id']);
            $table->dropColumn('proforma_invoice_id');

            // Add the new FK
            $table->foreignId('packing_list_id')
                ->nullable()
                ->after('id')
                ->constrained('packing_lists')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('receiving_notes', function (Blueprint $table) {
            $table->dropForeign(['packing_list_id']);
            $table->dropColumn('packing_list_id');

            $table->foreignId('proforma_invoice_id')
                ->nullable()
                ->after('id')
                ->constrained('proforma_invoices')
                ->nullOnDelete();
        });
    }
};
