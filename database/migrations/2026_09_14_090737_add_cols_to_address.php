
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop foreign keys only if they exist.
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'addresses'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        $existingKeys = collect($foreignKeys)
            ->pluck('CONSTRAINT_NAME')
            ->toArray();

        Schema::table('addresses', function (Blueprint $table) use ($existingKeys) {

            if (in_array('addresses_province_id_foreign', $existingKeys)) {
                $table->dropForeign('addresses_province_id_foreign');
            }

            if (in_array('addresses_country_id_foreign', $existingKeys)) {
                $table->dropForeign('addresses_country_id_foreign');
            }

            $columns = Schema::getColumnListing('addresses');

            $columnsToDrop = array_intersect(
                ['province_id', 'country_id', 'type'],
                $columns
            );

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        Schema::table('addresses', function (Blueprint $table) {

            if (!Schema::hasColumn('addresses', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('users')
                    ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('addresses', 'address_type')) {
                $table->string('address_type', 50)
                    ->nullable()
                    ->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {

            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'addresses'
                AND COLUMN_NAME = 'user_id'
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");

            foreach ($foreignKeys as $foreignKey) {
                $table->dropForeign($foreignKey->CONSTRAINT_NAME);
            }

            $columns = Schema::getColumnListing('addresses');

            $columnsToDrop = array_intersect(
                ['user_id', 'address_type'],
                $columns
            );

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        Schema::table('addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('addresses', 'province_id')) {
                $table->unsignedBigInteger('province_id')->nullable();
            }

            if (!Schema::hasColumn('addresses', 'country_id')) {
                $table->unsignedBigInteger('country_id')->nullable();
            }

            if (!Schema::hasColumn('addresses', 'type')) {
                $table->string('type')->nullable();
            }
        });
    }
};
