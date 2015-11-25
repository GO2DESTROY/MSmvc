<?php
/**
 * Setting routes is easy with the use of MS_route
 * we can use get, post, any, patch, delete, and options to set a new route this will this allows us to set the request
 * methods to match besides the uri cli is also available and will handle the command line requests
 *
 * IMPORTANT don't use the -m key for the cli requests since this is used for method identification
 *
 * example MS_route::get('blog/index')
 *
 * first parameter is the URI to match if you place a part between {}
 * it wil be seen as a wildcard and as long as it is filled.
 * It will match and return this value to the controller
 *
 * second parameter is an array which holds the items that we use apply to the matched route
 * currently we only support uses and as in this array
 *
 * uses: holds the controller with the method to call separated with @
 * as: holds the name of the controller so you can use the name to call a controller.
 * parameters: the parameters to be used for the request only used by the cli method
 */
use system\router\MS_route;

MS_route::any('/', ['uses' => 'example@index', 'as' => 'home']);
MS_route::any('/phpunit', ['uses' => 'example@phpUnit', 'as' => 'home']);

MS_route::get('/generate', ['uses' => 'generate@getGenerateFormPage', 'as' => 'generateFormPage']);
MS_route::post('/generate', ['uses' => 'generate@submitGenerateFormPage', 'as' => 'submitGenerateFormPage']);
MS_route::get('/generate/{id}', ['uses' => 'generate@getGenerateTables', 'as' => 'generateFormPage']);

MS_Route::any('/test',['uses' => 'tableControllerName@index']);

MS_Route::cli('model', ['uses' => 'generate@model', 'as' => 'modelGen', 'parameters' => 'n:']);
MS_Route::cli('controller', ['uses' => 'generate@controller', 'as' => 'controllerGen', 'parameters' => 'n:']);

MS_route::get('/products', ['uses' => 'products@index', 'as' => 'productsIndex']);
MS_route::get('/products/create', ['uses' => 'products@create', 'as' => 'productsCreate']);
MS_route::post('/products', ['uses' => 'products@store', 'as' => 'productsStore']);
MS_route::get('/products/{Id}', ['uses' => 'products@show', 'as' => 'productsShow']);
MS_route::get('/products/{Id}', ['uses' => 'products@edit', 'as' => 'productsEdit']);
MS_route::put('/products/{Id}', ['uses' => 'products@update', 'as' => 'productsUpdate']);
MS_route::delete('/products/{Id}', ['uses' => 'products@delete', 'as' => 'productsDelete']);

MS_route::get('/users', ['uses' => 'users@index', 'as' => 'usersIndex']);
MS_route::get('/users/create', ['uses' => 'users@create', 'as' => 'usersCreate']);
MS_route::post('/users', ['uses' => 'users@store', 'as' => 'usersStore']);
MS_route::get('/users/{Id}', ['uses' => 'users@show', 'as' => 'usersShow']);
MS_route::get('/users/{Id}', ['uses' => 'users@edit', 'as' => 'usersEdit']);
MS_route::put('/users/{Id}', ['uses' => 'users@update', 'as' => 'usersUpdate']);
MS_route::delete('/users/{Id}', ['uses' => 'users@delete', 'as' => 'usersDelete']);
