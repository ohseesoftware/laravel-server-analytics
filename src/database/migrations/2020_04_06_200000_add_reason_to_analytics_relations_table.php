<?php

// phpcs:ignoreFile

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;

class AddReasonToAnalyticsRelationsTable extends Migration
{
    public function up()
    {
        Schema::table(ServerAnalytics::getAnalyticsRelationTable(), function (Blueprint $table) {
            $table->string('reason')->nullable();
        });
    }

    public function down()
    {
        Schema::table(ServerAnalytics::getAnalyticsRelationTable(), function (Blueprint $table) {
            $table->dropColumn('reason');
        });
    }
}
