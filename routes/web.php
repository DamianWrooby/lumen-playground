<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/users', 'UserController@index');
    $router->post('/users', 'UserController@store');
    $router->get('/users/{das}', 'UserController@show');
    $router->put('/users/{das}', 'UserController@update');
    $router->delete('/users/{das}', 'UserController@destroy');

    $router->get('/articles/latest', ['as'=>'articles.latest', 'uses'=>'ArticleController@latest']);
    $router->get('/articles/by-category/{categoryAlias}', ['as'=>'articles.category', 'uses'=>'ArticleController@category']);
    $router->get('/articles/{id}', ['as'=>'articles.get', 'uses'=>'ArticleController@get']);
    $router->post('/articles', ['as'=>'articles.store', 'uses'=>'ArticleController@store']);
    $router->patch('/articles/{id}', ['as'=>'articles.edit', 'uses'=>'ArticleController@edit']);
    $router->delete('/articles/{id}', ['as'=>'articles.delete', 'uses'=>'ArticleController@delete']);
    // w obrębie danej metody (get, post itd.) ważne jest aby najpierw były podane ścieżki zdefiniowane, bez parametrów, a później z parametrami. Jeśli najpierw będą z parametrami to nie dojdziemy od tych bez parametrów
});