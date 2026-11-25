
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_status_histories', function (Blueprint $table) {

            $table->id();

            // Match these types with orders.id and users.id
            $table->unsignedInteger('order_id');
            $table->unsignedBigInteger('changed_by')->nullable();

            $table->string('status', 50);
            $table->text('comment')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'status']);

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');

            $table->foreign('changed_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
    }
};
