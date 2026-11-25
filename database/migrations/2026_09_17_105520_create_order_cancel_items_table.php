
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_cancel_items', function (Blueprint $table) {

            $table->id();

            // Foreign keys
            $table->unsignedBigInteger('order_cancel_id');
            $table->unsignedInteger('order_item_id');

            $table->unsignedInteger('quantity');

            $table->string('reason')->nullable();

            $table->decimal('refund_amount', 12, 2)->default(0);

            $table->timestamps();

            $table->index('order_cancel_id');
            $table->index('order_item_id');

            $table->foreign('order_cancel_id')
                ->references('id')
                ->on('order_cancels')
                ->cascadeOnDelete();

            $table->foreign('order_item_id')
                ->references('id')
                ->on('order_items')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_cancel_items');
    }
};
