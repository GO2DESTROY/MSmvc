<?php
use App\system\MS_response;
echo 312321;
if (!function_exists('view')) {
    /**
     * @param      $view       : the view to send
     * @param null $data       : the data to use for the view
     * @param null $masterView : master to overwrite
     */
    function view($view, $data = NULL, $masterView = NULL) {
        MS_response::addViewToCollection($view, $data);
        MS_response::view();
        if ($masterView !== NULL) {
            MS_response::overwriteMasterView($masterView);
        }
    }
}
if (!function_exists('dd')) {
    /**
     * @param $data : the data to dump
     */
    function dd($data) {
        array_map(function ($x) {
            //todo: check if we are in CLI or HTTP to dump the value
            //(new Dumper)->dump($x);
            var_dump($x);
        }, func_get_args());

        die(1);
    }
}
if (!function_exists('download')) {
    /**
     * we will start the download of the file with the right headers
     *
     * @param $file : the file to download
     */
    function download($file) {
        MS_response::download($file);
    }
}
if (!function_exists('json')) {
    /**
     *  we will setup the json response the right header and then we encode the message
     *
     * @param $data : the json data to return
     */
    function json($data) {
        MS_response::json($data);
    }
}
if (!function_exists('renderBody')) {
    /**
     * This method will be used to render the body inside the layout
     */
    function renderBody() {
        echo App\system\MS_view::$viewHtml;
    }
}
if (!function_exists('setLayout')) {
    /**
     * This method is used to identify the correct layout
     *
     * @param string $layoutName : layout to use
     */
    function setLayout(string $layoutName) {
        App\system\MS_view::$layout = $layoutName;
    }
}
if (!function_exists('partial')) {
    function partial(string $viewName) {
        App\system\MS_view::loadPartial('partials' . DIRECTORY_SEPARATOR . $viewName);
    }
}
if (!function_exists('isAssoc')) {
    /**
     * @param array $arr
     *
     * @return bool
     */
    function isAssoc($arr) {
        if ([] === $arr || !is_array($arr)) return FALSE;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
if (!function_exists("string")) {
    /**
     * @param string $name
     *
     * @return \App\system\models\properties\varchar
     */
    function string(string $name) {
        $string = new \App\system\models\properties\varchar();
        $string->name = $name;
        return $string;
    }
}
if (!function_exists("int")) {
    /**
     * @param string $name
     *
     * @return \App\system\models\properties\integer
     */
    function int(string $name) {
        $int = new \App\system\models\properties\integer();
        $int->name = $name;
        return $int;
    }
}
if (!function_exists("includeWholeDirectory")) {
    /**
     * @param        $directory
     * @param string $pattern
     * @param string $extension
     */
    function includeWholeDirectory($directory, $pattern = "/*", $extension = ".php", $subdirectories = FALSE) {

        foreach (glob(\App\system\MS_filesystem::$root . DIRECTORY_SEPARATOR . $directory . $pattern . $extension) as $filename) {
            if ($subdirectories === TRUE) {

            }
            $includeFile = new \App\system\MS_filesystem($filename);
            $includeFile->getDataSetFromRequest();
        }
    }
}

if (!function_exists("showWholeDirectory")) {
    /**
     * @param        $directory
     * @param string $pattern
     * @param string $extension
     *
     * @return array
     */
    function showWholeDirectory($directory,$pattern="/*", $subdirectories = FALSE) {
        $files = new \App\system\MS_filesystem($directory);
        exit;
        /*
        $files = glob(\App\system\pipelines\MS_pipeline::$root . DIRECTORY_SEPARATOR . $directory."/*" );
        var_dump($files);
        var_dump(\App\system\pipelines\MS_pipeline::$root . DIRECTORY_SEPARATOR . $directory );
        if ($subdirectories == TRUE) {
            var_dump(glob(\App\system\pipelines\MS_pipeline::$root . DIRECTORY_SEPARATOR . $directory . $pattern  , GLOB_ONLYDIR | GLOB_NOSORT));
            foreach (glob(\App\system\pipelines\MS_pipeline::$root . DIRECTORY_SEPARATOR . $directory . $pattern , GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
                $files = array_merge($files, showWholeDirectory($dir, "*", TRUE));

            }
        }
        */
        return $files;
    }
}