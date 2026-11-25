
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_return_items', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('order_return_id');
            $table->unsignedInteger('order_item_id');

            $table->unsignedInteger('quantity');

            $table->string('reason')->nullable();

            $table->string('condition', 50)->nullable();

            $table->decimal('refund_amount', 12, 2)->default(0);

            $table->timestamps();

            $table->index('order_return_id');
            $table->index('order_item_id');

            $table->foreign('order_return_id')
                ->references('id')
                ->on('order_returns')
                ->cascadeOnDelete();

            $table->foreign('order_item_id')
                ->references('id')
                ->on('order_items')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_return_items');
    }
};