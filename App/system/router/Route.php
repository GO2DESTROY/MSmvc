<?php

namespace App\system\router;
/**
 * Class Route: this class will be used to create new routes
 * @package MSmvc\system\router
 */
class Route {
    /**
     * routes array indexed on the uri
     * @var array
     */
    private static $routeSet;

    /**
     * routes index based on name
     * @var array
     */
    private static $referenceSet;

    /**
     * this will add the route to the collection
     *
     * @param $methods
     * @param $requestMatch
     * @param $action
     */
    private static function addRoute($methods, $requestMatch, $action) {
        self::$routeSet[$requestMatch][] = ['methods' => $methods, 'action' => $action];
        if (is_array($action)) {
            if (!empty($action['as'])) {
                self::$referenceSet[$action['as']] = ['methods' => $methods, 'uri' => $requestMatch, 'action' => $action];
            }
        }
        elseif (is_callable($action)){
            return $action();
        }
    }

    /**
     * @return mixed: the route collection based on the controller
     */
    public static function returnRouteCollection() {
        return self::$routeSet;
    }

    /**
     * @return mixed: the reference collection based on the name
     */
    public static function returnReferenceCollection() {
        return self::$referenceSet;
    }

    /**
     * we will respond on  all requests except for the CLI this is done because not all things are possible withing the
     * CLI
     *
     * @param $uri    : the uri we use to match on
     * @param $action : the action set we take upon a match
     */
    public static function any($uri, $action) {
        $methods = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];
        self::addRoute($methods, $uri, $action);
    }

    /**
     * we will respond only respond on the post requests
     *
     * @param $uri    : the uri we use to match on
     * @param $action : the action set we take upon a match
     */
    public static function post($uri, $action) {
        self::addRoute('POST', $uri, $action);
    }

    /**
     * we will only respond on the get requests
     *
     * @param $uri    : the uri we use to match on
     * @param $action : the action set we take upon a match
     */
    public static function get($uri, $action) {
        $methods = ['GET', 'HEAD'];
        self::addRoute($methods, $uri, $action);
    }

    /**
     * we will only respond on the put requests
     *
     * @param $uri    : the uri we use to match on
     * @param $action : the action set we take upon a match
     */
    public static function put($uri, $action) {
        self::addRoute('PUT', $uri, $action);
    }

    /**
     * we will only respond on the patch request
     *
     * @param $uri    : the uri we use to match on
     * @param $action : the action set we take upon a match
     */
    public static function patch($uri, $action) {
        self::addRoute('PATCH', $uri, $action);
    }

    /**
     * we will only respond on the delete request
     *
     * @param $uri    : the uri we use to match on
     * @param $action : the action set we take upon a match
     */
    public static function delete($uri, $action) {
        self::addRoute('DELETE', $uri, $action);
    }

    /**
     * we will only respond on the head request
     *
     * @param $uri    : the uri we use to match on
     * @param $action : the action set we take upon a match
     */
    public static function options($uri, $action) {
        self::addRoute('OPTIONS', $uri, $action);
    }

    /**
     * we will only respond on the CLI
     *
     * @param $command : the command that we will react up
     * @param $action  : the action set we take upon a match
     */
    public static function cli($command, $action) {
        self::addRoute('CLI', $command, $action);
    }
}