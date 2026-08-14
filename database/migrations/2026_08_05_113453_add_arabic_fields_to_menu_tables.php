<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('title_ar')->nullable()->after('title');
            $table->text('description_ar')->nullable()->after('description');
        });

        Schema::table('product_option_groups', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
        });

        Schema::table('product_options', function (Blueprint $table) {
            $table->string('label_ar')->nullable()->after('label');
        });

        Schema::table('offers', function (Blueprint $table) {
            $table->string('title_ar')->nullable()->after('title');
            $table->string('subtitle_ar')->nullable()->after('subtitle');
        });
    }

    public function down(): void
    {
        Schema::table('categories', fn (Blueprint $table) => $table->dropColumn('name_ar'));
        Schema::table('products', fn (Blueprint $table) => $table->dropColumn(['title_ar', 'description_ar']));
        Schema::table('product_option_groups', fn (Blueprint $table) => $table->dropColumn('name_ar'));
        Schema::table('product_options', fn (Blueprint $table) => $table->dropColumn('label_ar'));
        Schema::table('offers', fn (Blueprint $table) => $table->dropColumn(['title_ar', 'subtitle_ar']));
    }
};
