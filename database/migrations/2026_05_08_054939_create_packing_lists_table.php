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
        Schema::create('packing_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');

            // Header info
            $table->string('company_name')->nullable();
            $table->string('company_address')->nullable();
            $table->string('company_tel')->nullable();
            $table->string('title')->default('PACKING LIST');

            // Shipping info
            $table->string('port_of_loading')->nullable();
            $table->string('port_of_discharge')->nullable();

            $table->date('date')->nullable();

            $table->string('container_no')->nullable();
            $table->string('seal_no')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packing_lists');
    }
};
