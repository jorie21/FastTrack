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
        // Database-agnostic check for the old unique index
        $indexes = Schema::getIndexes('categories');
        $oldIndexExists = collect($indexes)->contains('name', 'categories_user_id_name_type_unique');

        Schema::table('categories', function (Blueprint $table) use ($oldIndexExists) {
            // 1. Drop the old unique index if it exists
            if ($oldIndexExists) {
                // For SQLite, dropping a unique index might fail if it's part of the table definition
                // but in modern Laravel/SQLite it usually works or is ignored if not supported directly.
                try {
                    $table->dropUnique('categories_user_id_name_type_unique');
                } catch (\Exception $e) {
                    // Silently fail if index doesn't exist or can't be dropped (common in some SQLite versions)
                }
            }

            // 2. Add a virtual generated column for the unique constraint
            if (!Schema::hasColumn('categories', 'is_active_unique')) {
                // Note: SQLite supports generated columns since 3.31.0
                $table->boolean('is_active_unique')
                    ->virtualAs('CASE WHEN deleted_at IS NULL THEN 1 ELSE NULL END')
                    ->after('deleted_at')
                    ->nullable(); // Important for the "NULL allows duplicates" logic
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
