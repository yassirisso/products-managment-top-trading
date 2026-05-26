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
        Schema::create('commercial_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('date')->nullable();

            $table->string('invoice_no')->nullable();

            $table->string('port_of_loading')->nullable();

            $table->string('port_of_discharge')->nullable();

            $table->string('mode_of_delivery')->nullable();

            $table->string('country_of_origin')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commercial_invoices');
    }
};
