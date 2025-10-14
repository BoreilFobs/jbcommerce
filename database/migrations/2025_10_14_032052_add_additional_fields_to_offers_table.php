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
        Schema::table('offers', function (Blueprint $table) {
            $table->string('brand')->nullable()->after('category');
            $table->string('sku')->unique()->nullable()->after('brand');
            $table->json('specifications')->nullable()->after('description');
            $table->enum('status', ['active', 'inactive', 'out_of_stock'])->default('active')->after('specifications');
            $table->boolean('featured')->default(false)->after('status');
            $table->integer('discount_percentage')->default(0)->after('price');
            $table->integer('views')->default(0)->after('featured');
            $table->string('meta_title')->nullable()->after('views');
            $table->text('meta_description')->nullable()->after('meta_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn([
                'brand',
                'sku',
                'specifications',
                'status',
                'featured',
                'discount_percentage',
                'views',
                'meta_title',
                'meta_description'
            ]);
        });
    }
};
