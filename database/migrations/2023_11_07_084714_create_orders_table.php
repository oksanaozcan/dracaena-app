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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('client_id');
            $table->string('transaction_id');
            $table->string('customer_name');
            $table->boolean('payment_status');
            $table->decimal('total_amount', $precision = 8, $scale = 2);
            $table->enum('payment_method', ['credit card', 'PayPal', 'cash on delivery']);
            $table->json('shipping_address');
            $table->json('billing_address');
            $table->decimal('discount_amount', $precision = 8, $scale = 2);
            $table->timestamp('completed_at', $precision = 0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('client_id')->references('clerk_id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
