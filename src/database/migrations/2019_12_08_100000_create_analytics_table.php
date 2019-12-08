<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use OhSeeSoftware\LaravelServerAnalytics\LaravelServerAnalytics;

class CreateAnalyticsTable extends Migration
{
    public function up()
    {
        Schema::create(LaravelServerAnalytics::getAnalyticsDataTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('path');
            $table->string('method');
            $table->integer('status_code');
            $table->integer('duration_ms');
            $table->string('user_agent');
            $table->string('ip_address');
            $table->json('query_params')->nullable();
            $table->timestamps();
        });

        Schema::create(LaravelServerAnalytics::getAnalyticsRelationTable(), function (Blueprint $table) {
            $table->unsignedBigInteger('analytics_id');
            $table->foreign('analytics_id')->references('id')->on(LaravelServerAnalytics::getAnalyticsDataTable());
            $table->morphs('relation');
        });
    }

    public function down()
    {
        Schema::dropIfExists(LaravelServerAnalytics::getAnalyticsRelationTable());
        Schema::dropIfExists(LaravelServerAnalytics::getAnalyticsDataTable());
    }
}
