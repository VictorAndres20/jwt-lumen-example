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
$router->get('/','WelcomeController@index');

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
    /** RETRIVE with HTTP verb GET */
    $router->get('/users','UserController@getAll');
    $router->get('/shopuser/{cod_user}','ShopController@getAllShopsByUSer');
    /** UPDATES with HTTP verb PUT */
    $router->put('/updatelogin','UserController@updateLogin');
    /** CREATE with HTTP verb POST */
    $router->post('/create','UserController@insert');
});

