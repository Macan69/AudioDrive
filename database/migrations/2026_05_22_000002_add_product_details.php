<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->nullable()->after('slug');
            $table->text('full_description')->nullable()->after('description');
            $table->json('features')->nullable()->after('full_description');
            $table->unsignedSmallInteger('warranty_months')->default(12)->after('stock');
            $table->string('country')->nullable()->after('warranty_months');
            $table->string('weight')->nullable()->after('country');
            $table->string('dimensions')->nullable()->after('weight');
            $table->text('package_contents')->nullable()->after('dimensions');
            $table->text('installation')->nullable()->after('package_contents');
        });

    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'sku', 'full_description', 'features', 'warranty_months',
                'country', 'weight', 'dimensions', 'package_contents', 'installation',
            ]);
        });
    }
};
