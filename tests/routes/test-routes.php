<?php

Route::get('/analytics', function () {
    return 'Test';
});

Route::get('/api/analytics', function () {
    return response()->json(['foo' => 'bar']);
});
