<?php
/**
 * todo: split the router up to a setter and executer with the use of MS_requestHandler. MS_requestHandler will now set the request and the MS_router will assume it's there
 * @package MSmvc
 * @author  Maurice Schurink
 * @version 0.2
 */
require __DIR__ . '/../vendor/autoload.php';

$MS_main = new MSmvc\system\MS_main();
$MS_main->index();
$MS_main->boot();