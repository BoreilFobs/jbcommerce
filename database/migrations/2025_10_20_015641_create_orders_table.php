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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            
            // Order totals
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            
            // Order status
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            
            // Payment information
            $table->enum('payment_method', ['mobile_money_mtn', 'mobile_money_orange', 'cash_on_delivery', 'bank_transfer'])->default('cash_on_delivery');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_reference')->nullable();
            $table->string('payment_phone')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            // Shipping information
            $table->string('shipping_name');
            $table->string('shipping_phone');
            $table->string('shipping_email');
            $table->text('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_region');
            $table->string('shipping_postal_code')->nullable();
            
            // Additional information
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('tracking_number')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancelled_reason')->nullable();
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('user_id');
            $table->index('order_number');
            $table->index('status');
            $table->index('payment_status');
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
