<?php

namespace MSmvc\system;

use MSmvc\system\pipelines\MS_pipeline;

/**
 * Class MS_view
 * @package system
 */
class MS_view {
    /**
     * view: name of the view file
     * data: array with the variables
     * @var array
     */
    public static $view;

    /**
     * html content of the view file in case of a layout
     * @var string
     */
    public static $viewHtml;

    /**
     * the name of the layout
     * @var string
     */
    public static $layout;

    public function loadView() {
//todo: switch from static to singleton
//todo: make a template engine to store the view
        $viewFile = MS_pipeline::returnViewFilePath(self::$view['view']);
        if (is_array(self::$view['data'])) {
            extract(self::$view['data'], EXTR_SKIP);
        }
        self::$viewHtml = MS_pipeline::executeAndReturnFileContent($viewFile, self::$view['data']);
        if (self::$layout !== NULL) {
            include MS_pipeline::returnLayoutFilePath(self::$layout);
        } else {
            echo self::$viewHtml;
        }
        var_dump(self::$layout);
    }

    /**
     * @param string $viewName
     */
    public static function loadPartial(string $viewName) {
        $view = MS_pipeline::returnViewFilePath($viewName);
        include $view;
    }
}