<?php
/**
 * @author  Maurice Schurink
 * @version 0.2
 */
include_once './system/MS_core.php';
include_once './system/MS_main.php';
$MS_main = new system\MS_main();
$MS_main->index();
//todo: use metaphone, similar_text and levenshtein for a search helper
//todo: use php trader for stock analysis