<?php
/**
 * Created by PhpStorm.
 * User: Maurice
 * Date: 05-Feb-17
 * Time: 13:07
 */

namespace App\system;
/**
 * Class MS_templateHandler
 * @package App\system
 */
class MS_templateHandler {
    private $template;
    private $content;

    function __construct(string $template = NULL) {
        $this->setTemplate($template);
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template = "empty") {
        $this->template = $template;
    }
}