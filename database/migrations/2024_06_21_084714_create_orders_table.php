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
            $table->foreignId('customer_id')->constrained();
            $table->string('session_id');
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->boolean('payment_status');
            $table->decimal('total_amount', $precision = 8, $scale = 2);
            $table->enum('payment_method', ['credit card', 'PayPal', 'cach on delivery']);
            $table->unsignedBigInteger('shipping_address_id')->nullable(); //TODO: delete nullable
            $table->unsignedBigInteger('billing_address_id')->nullable();
            $table->decimal('discount_amount', $precision = 8, $scale = 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('shipping_address_id')->references('id')->on('addresses');
            $table->foreign('billing_address_id')->references('id')->on('addresses');
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
