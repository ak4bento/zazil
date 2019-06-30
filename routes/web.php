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
    return str_random(32);
});

$router->group(['prefix' => 'checklists'], function () use ($router) {
    $router->group(['prefix' => 'templates'], function () use ($router) {
        $router->get('/', ['uses' => 'ChecklistTemplateController@index']);
        $router->post('/', ['uses' => 'ChecklistTemplateController@store']);
        $router->get('/{id}', ['uses' => 'ChecklistTemplateController@show']);
        $router->patch('/{id}', ['uses' => 'ChecklistTemplateController@update']);
        $router->delete('/{id}', ['uses' => 'ChecklistTemplateController@destroy']);
        $router->post('{id}/assigns', ['uses' => 'ChecklistTemplateController@assignDomains']);
    });

    $router->get('/{checklistId}/items', ['uses' => 'ItemController@index']); //finish
    $router->post('/{checklistId}/items', ['uses' => 'ItemController@create']); //finish
    $router->get('/{checklistId}/items/{id}', ['uses' => 'ItemController@show']); //finish
    $router->patch('/{checklistId}/items/{id}', ['uses' => 'ItemController@update']); //finish
    $router->delete('/{checklistId}/items/{id}', ['uses' => 'ItemController@destroy']); //finish

    $router->post('/complete', ['uses' => 'ItemController@setComplete']); //finish
    $router->post('/incomplete', ['uses' => 'ItemController@setIncomplete']); //finish

    $router->get('/', ['uses' => 'ChecklistController@index']);
    $router->post('/', ['uses' => 'ChecklistController@store']);
    $router->get('/{id}', ['uses' => 'ChecklistController@show']);
    $router->patch('/{id}', ['uses' => 'ChecklistController@update']);
    $router->delete('/{id}', ['uses' => 'ChecklistController@destroy']);
});
