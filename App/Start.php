<?php
// here we open a class main this is the core of the system this makes sure the MVC boots up

// this file contains a lot of dirty code we have to improve this in the near future
namespace App;

use App\system\Request;
use App\system\MS_filesystem;
use App\system\router\Route;
use App\system\router\Router;

/**
 * Class MS_start: this class will start the framework
 * @package system: MSmvc main
 * @author  Maurice Schurink
 */
class Start {
    /**
     * the controller that is requested
     * @var null
     */
    private $currentRequestInterface = NULL;

    /**
     * the uri as a string
     * @var null string
     */
    private $uri = NULL;

    /**
     * MS_start constructor.
     */
    public function __construct() {
        //	set_exception_handler([new Handler, 'exceptionHandler']);
        //	set_error_handler([new Handler, 'errorHandler']);
        //	register_shutdown_function([new Handler, 'fatal_handler']);
    }

    /**
     * we let the router run to find the right route to use then we pass it to the request so the controller can be
     * called followed by the response
     */
    public function boot() {
        //MS_pipeline::includeFile('system' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'MS_functions');
        $this->setRequestInterface();
        if ($this->currentRequestInterface !== 'CLI') {
            $this->setRequestUri();
        }
        $request = new Request();
        $request->requestInterface = $this->currentRequestInterface;

        set_include_path(get_include_path() . PATH_SEPARATOR . dirname(dirname(__FILE__)));

        chdir(dirname(dirname(__FILE__)));

        $functions = new MS_filesystem("App/system/helpers/MS_functions.php");
        $functions->include();

        $configs = new MS_filesystem("App/config");
        $configs->filterExtensions("php");
        $configs->include();

        $router = new Router();
        $router->routes = Route::returnRouteCollection();
        $router->currentRequestMethod = $this->currentRequestInterface;

        if ($this->currentRequestInterface !== 'CLI') {
            $router->uri = $this->uri;
        }
        $request->requestRoute = $router->matchRequest();
        if ($router->variables !== NULL) {
            $request->requestVariables = $router->variables;
        }
        $request->request();
    }

    /**
     * we set this->uri to the current http uri
     */
    private function setRequestUri() {
        if ($this->uri === NULL) {
            $request_path = explode('?', $_SERVER['REQUEST_URI']);    //root of the URI
            $request_root = rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/');    //The url
            $uri = '/' . rtrim(utf8_decode(substr(urldecode($request_path[0]), strlen($request_root) + 1)), "/");
            $this->uri = $uri;
        }
    }


    /**
     * this function will set the request method
     */
    private function setRequestInterface() {
        if ($this->currentRequestInterface === NULL) {
            if (php_sapi_name() == 'cli') {
                $this->currentRequestInterface = 'CLI';
            } else {
                if (!empty($_REQUEST['method'])) {
                    $this->currentRequestInterface = $_REQUEST['method'];
                } else {
                    $this->currentRequestInterface = $_SERVER['REQUEST_METHOD'];
                }
            }
        }
    }
}