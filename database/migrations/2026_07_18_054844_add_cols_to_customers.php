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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('country_code')->nullable();
            $table->string('state_code')->nullable();
            $table->string('state')->nullable();
            $table->string('district')->nullable();
            $table->string('region')->nullable();
            $table->string('county')->nullable();
            $table->string('suburb')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('area')->nullable();

            $table->string('street')->nullable();
            $table->string('street_number')->nullable();

            $table->string('building_name')->nullable();
            $table->string('building_number')->nullable();
            $table->string('floor')->nullable();
            $table->string('apartment')->nullable();
            $table->string('unit')->nullable();
            $table->string('suite')->nullable();
            $table->string('landmark')->nullable();

            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->string('delivery_instructions')->nullable();
            $table->text('notes')->nullable();

            $table->boolean('is_default')->default(false);
            $table->boolean('is_billing')->default(false);
            $table->boolean('is_shipping')->default(false);

            $table->string('vat_number')->nullable();
            $table->string('company_registration_number')->nullable();

            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone')->nullable();

            $table->string('door_code')->nullable();
            $table->string('entrance')->nullable();

            $table->string('zone')->nullable();
            $table->string('block')->nullable();
            $table->string('plot_number')->nullable();
            $table->string('house_number')->nullable();

            $table->string('formatted_address')->nullable();

            $table->json('metadata')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $columns = [
                'country_code',
                'state_code',
                'state',
                'district',
                'region',
                'county',
                'suburb',
                'neighborhood',
                'area',

                'street',
                'street_number',

                'building_name',
                'building_number',
                'floor',
                'apartment',
                'unit',
                'suite',
                'landmark',

                'latitude',
                'longitude',

                

                'delivery_instructions',
                'notes',

                'is_default',
                'is_billing',
                'is_shipping',

                'vat_number',
                'company_registration_number',

                'recipient_name',
                'recipient_phone',

                'door_code',
                'entrance',

                'zone',
                'block',
                'plot_number',
                'house_number',

                'formatted_address',

                'metadata',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('addresses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
