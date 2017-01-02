<?php
/**
 * todo: split the router up to a setter and executer with the use of MS_requestHandler. MS_requestHandler will now set the request and the MS_router will assume it's there
 * @package MSmvc
 * @author  Maurice Schurink
 * @version 0.2
 */
require __DIR__ . '/../vendor/autoload.php';
use MSmvc\MS_start;
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
$MS_main = new MS_start();
$MS_main->boot();