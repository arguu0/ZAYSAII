<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('buy_price', 12, 2)->default(0)->after('quantity');
            $table->decimal('sell_price', 12, 2)->default(0)->after('buy_price');
        });

        // Migrate existing price data → buy_price (preserve records)
        DB::statement('UPDATE products SET buy_price = price, sell_price = price');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('price')->default(0)->after('quantity');
        });

        DB::statement('UPDATE products SET price = sell_price');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['buy_price', 'sell_price']);
        });
    }
};
