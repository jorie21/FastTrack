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
        // Safe check for the old unique index
        $oldIndexExists = count(DB::select("SHOW INDEX FROM categories WHERE Key_name = 'categories_user_id_name_type_unique'")) > 0;

        Schema::table('categories', function (Blueprint $table) use ($oldIndexExists) {
            // 1. Drop the old unique index if it exists
            if ($oldIndexExists) {
                $table->dropUnique(['user_id', 'name', 'type']);
            }

            // 2. Add a virtual generated column for the unique constraint
            // - If 'deleted_at' is NULL (active), it returns 1
            // - If 'deleted_at' is NOT NULL (soft-deleted), it returns NULL
            // Since MySQL allows multiple NULL values in a unique index, this allows
            // multiple soft-deleted records but only ONE active record.
            if (!Schema::hasColumn('categories', 'is_active_unique')) {
                $table->boolean('is_active_unique')
                    ->virtualAs('CASE WHEN deleted_at IS NULL THEN 1 ELSE NULL END')
                    ->after('deleted_at');
            }

            // 3. Re-add the unique constraint including the virtual column
            $table->unique(['user_id', 'name', 'type', 'is_active_unique'], 'categories_unique_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique('categories_unique_active');
            $table->dropColumn('is_active_unique');
            
            // Re-add the original unique constraint
            $table->unique(['user_id', 'name', 'type']);
        });
    }
};
