<?php

// phpcs:ignoreFile

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;

class ChangeUserAgentToText extends Migration
{
    public function up()
    {
        Schema::table(ServerAnalytics::getAnalyticsDataTable(), function (Blueprint $table) {
            $table->text('user_agent')->change();
        });
    }

    public function down()
    {
        Schema::table(ServerAnalytics::getAnalyticsDataTable(), function (Blueprint $table) {
            $table->string('user_agent')->change();
        });
    }
}
