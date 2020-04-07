<?php

// phpcs:ignoreFile

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;

class AddUserIdToAnalyticsTable extends Migration
{
    public function up()
    {
        Schema::table(ServerAnalytics::getAnalyticsDataTable(), function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::table(ServerAnalytics::getAnalyticsDataTable(), function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
