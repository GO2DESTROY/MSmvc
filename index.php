<?php
/**
 * todo: split the router up to a setter and executer with the use of MS_requestHandler. MS_requestHandler will now set the request and the MS_router will assume it's there
 * @author  Maurice Schurink
 * @version 0.2
 */
require './system/MS_core.php';
require './system/MS_main.php';
$MS_main = new system\MS_main();
$MS_main->index();
$MS_main->boot();