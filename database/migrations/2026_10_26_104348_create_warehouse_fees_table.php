<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_fees', function (Blueprint $table) {
            $table->id();
            $table->string('container_no');
            $table->date('date');
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shipping_mark_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('montant_rmb', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_fees');
    }
};