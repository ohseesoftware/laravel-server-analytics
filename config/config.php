<?php

return [

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
    'analytics_meta_table' => 'analytics_meta'
];
