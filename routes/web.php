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

    $api->group([
        'middleware' => ['api.auth'],
    ], function ($api) {

        // Auth
        $api->post('/auth/login', ['uses' => 'AuthController@login', 'as' => 'api.auth.login']);
        $api->get('/auth/me', ['middleware' => ['api.auth'], 'uses' => 'AuthController@me', 'as' => 'api.auth.me']);
        $api->put('/auth/refresh', ['middleware' => ['api.auth'], 'uses' => 'AuthController@refresh', 'as' => 'api.auth.refresh']);
        $api->delete('/auth/logout', ['middleware' => ['api.auth'], 'uses' => 'AuthController@logout', 'as' => 'api.auth.logout']);

        // User
        $api->post('/users', ['middleware' => ['api.auth'], 'uses' => 'UserController@store', 'as' => 'api.users.store']);

    });
});
