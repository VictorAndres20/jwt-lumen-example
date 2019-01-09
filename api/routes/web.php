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

/** Main route for API */
$router->get('/', function () use ($router) {
    return $router->app->version();
});

/** Simple route to get a random str for keys */
$router->get('/keygenerator', function () use ($router) {
    return str_random(30);
});

/** Authenticate route */
$router->post('/login','AuthController@authenticate');

/**
 * ALL PROTECTED ROUTES WITH JWT
 */
$router->group(['middleware' => 'jwt'],function() use ($router){
    $router->get('/users','UserController@getAll');
    $router->get('/shopbyuser/{cod_user}','ShopController@getAllShopsByUSer');
    $router->post('/updatelogin','UserController@updateLogin');
});

