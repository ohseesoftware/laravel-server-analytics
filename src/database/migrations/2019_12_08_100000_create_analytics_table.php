<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use OhSeeSoftware\LaravelServerAnalytics\LaravelServerAnalyticsFacade;

class CreateAnalyticsTable extends Migration
{
    public function up()
    {
        Schema::create(LaravelServerAnalyticsFacade::getAnalyticsDataTable(), function (Blueprint $table) {
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

        Schema::create(LaravelServerAnalyticsFacade::getAnalyticsRelationTable(), function (Blueprint $table) {
            $table->unsignedBigInteger('analytics_id');
            $table->foreign('analytics_id')->references('id')->on(LaravelServerAnalyticsFacade::getAnalyticsDataTable());
            $table->morphs('relation');
        });
    }

    public function down()
    {
        Schema::dropIfExists(LaravelServerAnalyticsFacade::getAnalyticsRelationTable());
        Schema::dropIfExists(LaravelServerAnalyticsFacade::getAnalyticsDataTable());
    }
}
