
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_returns', function (Blueprint $table) {

            $table->id();

            // Must match the actual orders.id type
            $table->unsignedInteger('order_id');

            $table->string('return_number')->unique();

            $table->string('status')->default('requested');

            $table->text('reason')->nullable();

            $table->text('customer_note')->nullable();

            $table->decimal('refund_amount', 12, 2)->default(0);

            $table->timestamp('requested_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('refunded_at')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'status']);

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_returns');
    }
};