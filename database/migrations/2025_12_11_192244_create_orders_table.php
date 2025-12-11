<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignUuid('subscription_id')->constrained('subscribtions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Paymob Order Details
            $table->unsignedBigInteger('paymob_order_id')->unique()->comment('Paymob order ID');
            $table->string('payment_token')->nullable()->comment('Paymob payment token');
            $table->text('payment_url')->nullable()->comment('Paymob payment URL');

            // Payment Details
            $table->unsignedBigInteger('amount_cents');
            $table->unsignedBigInteger('paid_amount_cents')->default(0)->comment('Amount actually paid');
            $table->string('currency', 3)->default('EGP');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'cancelled', 'refunded'])
                ->default('pending');
            $table->string('payment_method')->nullable()->comment('Payment method type (e.g., wallet, card)');
            $table->string('merchant_order_id')->nullable()->comment('Custom merchant order ID');

            // Transaction Details (from callback)
            $table->unsignedBigInteger('transaction_id')->nullable()->comment('Paymob transaction ID');
            $table->unsignedBigInteger('integration_id')->nullable()->comment('Paymob integration ID');
            $table->boolean('is_successful')->default(false);
            $table->string('transaction_response_code')->nullable();
            $table->text('transaction_message')->nullable();

            // Payment Source Details (from callback)
            $table->string('source_data_type')->nullable()->comment('Payment source type (wallet, card, etc.)');
            $table->string('source_data_pan')->nullable()->comment('Masked payment info');

            // Refund/Void Details
            $table->boolean('is_refunded')->default(false);
            $table->boolean('is_voided')->default(false);
            $table->unsignedBigInteger('refunded_amount_cents')->default(0);

            // Security
            $table->string('hmac')->nullable()->comment('HMAC hash for verification');

            // Shipping Data (JSON)
            $table->json('shipping_data')->nullable();

            // Full Paymob Response (JSON) - for reference and debugging
            $table->json('paymob_order_response')->nullable()->comment('Full order creation response');
            $table->json('paymob_callback_response')->nullable()->comment('Full callback response');

            // Additional Metadata
            $table->text('notes')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('paymob_order_id');
            $table->index('subscription_id');
            $table->index('user_id');
            $table->index('payment_status');
            $table->index('transaction_id');
            $table->index('created_at');
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
