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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Fields
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 4)->default(0);
            $table->string('currency')->default('EGP');
            $table->integer('duration')
                ->default(30)->comment('Default duration in days');
            $table->enum('duration_type', ['days', 'months', 'years'])
                ->default('days');
            $table->enum('status', ['active', 'inactive', 'deleted'])
                ->default('active');

            // Foreign Keys
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('deleted_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
