<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->boolean('low_stock_notified')->default(false)->after('minimum_stock');
            $table->timestamp('last_stock_notification_at')->nullable()->after('low_stock_notified');
        });
    }

    public function down()
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropColumn(['low_stock_notified', 'last_stock_notification_at']);
        });
    }
};
