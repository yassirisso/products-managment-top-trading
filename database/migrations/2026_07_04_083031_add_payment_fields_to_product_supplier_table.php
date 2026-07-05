<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_supplier', function (Blueprint $table) {

            $table->enum('payment_status', ['paid', 'partial', 'unpaid'])
                  ->default('unpaid')
                  ->after('buying_price');

            $table->enum('payment_method', [
                    'cash',
                    'bank_transfer',
                    'check',
                    'credit_card'
                ])->nullable();

            $table->date('date_first_payment')
                  ->nullable()
                  ->after('payment_method');

            $table->date('date_rest_payment')
                  ->nullable()
                  ->after('date_first_payment');

            $table->decimal('discount', 5, 2)
                  ->default(0)
                  ->after('date_rest_payment');
        });
    }

    public function down(): void
    {
        Schema::table('product_supplier', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'payment_method',
                'date_first_payment',
                'date_rest_payment',
                'discount',
            ]);
        });
    }
};
