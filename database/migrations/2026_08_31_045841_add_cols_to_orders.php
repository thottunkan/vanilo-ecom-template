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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_address')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_status')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->unsignedBigInteger('cancelled_by')->nullable();
            $table->string('source')->nullable();
            $table->text('customer_note')->nullable();
            $table->text('admin_note')->nullable();
            $table->text('user_agent')->nullable();
            $table->decimal('total_paid', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'shipping_address',
                'billing_address',
                'transaction_id',
                'paid_at',
                'cancelled_by',
                'source',
                'customer_note',
                'admin_note',
                'user_agent',
                'total_paid',
            ]);
        });
    }
};
