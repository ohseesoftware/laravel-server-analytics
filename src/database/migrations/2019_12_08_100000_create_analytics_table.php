<?php

// phpcs:ignoreFile

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use OhSeeSoftware\LaravelServerAnalytics\ServerAnalytics;

class CreateAnalyticsTable extends Migration
{
    public function up()
    {
        Schema::create(ServerAnalytics::getAnalyticsDataTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('path');
            $table->string('method');
            $table->integer('status_code');
            $table->integer('duration_ms');
            $table->string('user_agent');
            $table->string('ip_address');
            $table->string('referrer')->nullable();
            $table->json('query_params')->nullable();
            $table->timestamps();
        });

        Schema::create(ServerAnalytics::getAnalyticsRelationTable(), function (Blueprint $table) {
            $table->unsignedBigInteger('analytics_id');
            $table->foreign('analytics_id')
                ->references('id')
                ->on(ServerAnalytics::getAnalyticsDataTable());
            $table->morphs('relation');
        });

        Schema::create(ServerAnalytics::getAnalyticsMetaTable(), function (Blueprint $table) {
            $table->unsignedBigInteger('analytics_id');
            $table->foreign('analytics_id')
                ->references('id')
                ->on(ServerAnalytics::getAnalyticsDataTable());
            $table->string('key');
            $table->text('value')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists(ServerAnalytics::getAnalyticsMetaTable());
        Schema::dropIfExists(ServerAnalytics::getAnalyticsRelationTable());
        Schema::dropIfExists(ServerAnalytics::getAnalyticsDataTable());
    }
}
