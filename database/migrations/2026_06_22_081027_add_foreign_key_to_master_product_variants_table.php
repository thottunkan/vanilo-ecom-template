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
        Schema::table('master_product_variants', function (Blueprint $table) {
            // Add foreign key constraint
            $table->foreign('master_product_id')
                ->references('id')
                ->on('master_products')
                ->onDelete('cascade');
                
            $table->string('size')->nullable();
            $table->string('color')->nullable();   
           
        });
    }

    public function down(): void
    {
        Schema::table('master_product_variants', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['master_product_id']);
        });
    }
};
