<?php

return [

    /**
     * The model class used to represent users.
     */
    'user_model' => 'App\User',

    /**
     * The name of the table where the analytics data will be stored.
     */
    'analytics_data_table' => 'analytics',

    /**
     * The name of the morphs table used to relate analytics
     * data to entities in your application.
     */
    'analytics_relation_table' => 'analytics_relations',

    /**
     * The name of the meta table used to attach
     * metadata to analytics request records.
     */
    'analytics_meta_table' => 'analytics_meta',

    /**
     * Controls whether or not the page views
     * from bots will be recorded.
     */
    'ignore_bot_requests' => true,

    /**
     * The FQN of the class used to generate the details for the request.
     */
    'request_details_class' => \OhSeeSoftware\LaravelServerAnalytics\RequestDetails::class,

    /**
     * The name of the queue connection to use
     * to process the analytics requests.
     *
     * If left null, the analytics records will
     * be created synchronously at the end of the request.
     */
    'queue_connection' => null
];
