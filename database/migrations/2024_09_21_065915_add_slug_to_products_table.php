<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'slug')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('slug')->nullable(); // Add slug column
            });
        }

        DB::table('products')->whereNull('slug')->orWhere('slug', '')->get()->each(function ($product) {
            $slug = Str::slug($product->title . '-' . $product->id);
            DB::table('products')
                ->where('id', $product->id)
                ->update(['slug' => $slug]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change(); // Apply unique constraint
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('products', 'slug')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropUnique('products_slug_unique'); // Drop unique constraint
                $table->dropColumn('slug'); // Drop slug column
            });
        }
    }
};
