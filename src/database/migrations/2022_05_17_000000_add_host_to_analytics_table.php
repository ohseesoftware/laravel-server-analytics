<?php

// phpcs:ignoreFile

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;

class AddHostToAnalyticsTable extends Migration
{
    public function up()
    {
        Schema::table(ServerAnalytics::getAnalyticsDataTable(), function (Blueprint $table) {
            $table->string('host')->nullable()->before('path');
        });
    }

    public function down()
    {
        Schema::table(ServerAnalytics::getAnalyticsDataTable(), function (Blueprint $table) {
            $table->dropColumn('host');
        });
    }
}
