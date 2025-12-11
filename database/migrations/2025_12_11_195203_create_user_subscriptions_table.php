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
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('subscription_id')->constrained('subscribtions')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            // Subscription Period
            $table->dateTime('start_date');
            $table->dateTime('end_date');

            // Status
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('user_id');
            $table->index('subscription_id');
            $table->index('order_id');
            $table->index('status');
            $table->index('end_date');
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
