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
        Schema::create('supplier_purchases', function (Blueprint $table) {

            $table->id();


            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();


            $table->foreignId('supplier_id')
                ->constrained()
                ->cascadeOnDelete();


            // Quantity purchased
            $table->decimal('quantity', 12, 2);


            // Buying price per unit
            $table->decimal('unit_price', 12, 2);


            // Total amount before discount
            $table->decimal('total_amount', 12, 2);


            // RMB or USD
            $table->enum('currency', [
                'RMB',
                'USD'
            ])->default('RMB');


            // Purchase status
            $table->enum('payment_status', [
                'unpaid',
                'partial',
                'paid'
            ])->default('unpaid');


            $table->date('purchase_date');


            $table->text('note')->nullable();


            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_purchases');
    }
};
