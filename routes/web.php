<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    // return $router->app->version();
    return "Hello from " . env("API_NAME");
});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'middleware' => ['api.throttle'],
    'limit' => 100,
    'expires' => 5,
    'prefix' => 'api/v1',
    'namespace' => 'App\Http\Controllers\V1',
], function ($api) {

    /**
     * Unauthenticated Routes
     */

    // Auth
    $api->post('/auth/login', ['uses' => 'AuthController@login', 'as' => 'api.auth.login']);

    // Category
    $api->get('/categories', ['uses' => 'CategoryController@index', 'as' => 'api.categories.index']);
    $api->get('/categories/{id}', ['uses' => 'CategoryController@show', 'as' => 'api.categories.show']);

    // Product
    $api->get('/products', ['uses' => 'ProductController@index', 'as' => 'api.products.index']);
    $api->get('/products/{id}', ['uses' => 'ProductController@show', 'as' => 'api.products.show']);

    /**
     * Authenticated Routes
     */
    $api->group([
        'middleware' => ['api.auth'],
    ], function ($api) {

        // Auth
        $api->get('/auth/me', ['uses' => 'AuthController@me', 'as' => 'api.auth.me']);
        $api->put('/auth/refresh', ['uses' => 'AuthController@refresh', 'as' => 'api.auth.refresh']);
        $api->delete('/auth/logout',['uses' => 'AuthController@logout', 'as' => 'api.auth.logout']);

        // Category
        $api->post('/categories', ['uses' => 'CategoryController@store', 'as' => 'api.categories.store']);
        $api->put('/categories/{id}', ['uses' => 'CategoryController@update', 'as' => 'api.categories.update']);
        $api->delete('/categories/{id}', ['uses' => 'CategoryController@destroy', 'as' => 'api.categories.destroy']);

        // Product
        $api->post('/products', ['uses' => 'ProductController@store', 'as' => 'api.products.store']);
        $api->put('/products/{id}', ['uses' => 'ProductController@update', 'as' => 'api.products.update']);
        $api->delete('/products/{id}', ['uses' => 'ProductController@destroy', 'as' => 'api.products.destroy']);

    });
});
