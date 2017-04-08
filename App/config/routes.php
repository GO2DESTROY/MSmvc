<?php
/**
 * Setting routes is easy with the use of Route
 * we can use get, post, any, patch, delete, and options to set a new route this will this allows us to set the request
 * methods to match besides the uri cli is also available and will handle the command line requests
 *
 * IMPORTANT don't use the -m key for the cli requests since this is used for method identification
 *
 * example Route::get('blog/index')
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
use App\system\router\Route;

/*Route::any('/', function (){
    return 123;
});
*/
Route::any('/', ['uses' => 'example@index', 'as' => 'home']);

Route::get('/generate', ['uses' => 'generate@index', 'as' => 'generateFormPage']);
//Route::get('/generate/model/{id}',['uses' => 'generate@requestModelContent']);

Route::any('/test',['uses' => 'tableControllerName@index']);

//Route::cli('model', ['uses' => 'generate@generateModel', 'as' => 'modelGen', 'parameters' => 'n:']);
//Route::cli('controller', ['uses' => 'generate@generateController', 'as' => 'controllerGen', 'parameters' => 'n:']);
