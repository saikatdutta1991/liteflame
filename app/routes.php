<?php


/**
 * register app routes
 */
Route::get('/', 'App\Controllers\TestController@test');
Route::get('test-route1', 'App\Controllers\TestController@test');
Route::get('test-route2', function(){
    return "jasdf";
});