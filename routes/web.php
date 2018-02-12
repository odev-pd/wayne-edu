<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Profile view
Route::get('{any?}profile/{accessid}', 'ProfileController@show')
    ->where(['any' => '.*', 'accessid' => '.+'])
    ->middleware('data', 'formy');

// News by category
Route::get('{any?}news/category/{slug}', 'NewsController@index')
    ->where(['any' => '.*', 'slug' => '.+'])
    ->middleware('data', 'formy');

// News view
Route::get('{any?}news/{slug}-{id}', 'NewsController@show')
    ->where(['any' => '.*', 'slug' => '.+', 'id' => '\d+'])
    ->middleware('data', 'formy');

// The wild card route is a catch all route that tries to resolve the requests path to a json file
Route::match(['get', 'post'], '{path}', function (Illuminate\Http\Request $request) {
        return app($request->controller)->index($request);
    })
    ->where('path', '.*')
    ->middleware('data', 'formy');
