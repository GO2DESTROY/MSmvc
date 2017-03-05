<?php
/**
 * Copyright (c) 2017.
 * MSmvc
 *
 * An open source application development framework for PHP
 *
 * Copyright (c) 2017 Maurice Schurink, All Rights Reserved.
 *
 * NOTICE:  All information contained herein is, and remains the property of  Maurice Schurink. The intellectual and
 * technical concepts contained herein are proprietary to Maurice Schurink and may be covered by U.S. and Foreign
 * Patents, patents in process, and are protected by trade secret or copyright law. Dissemination of this information
 * or reproduction of this material is strictly forbidden unless prior written permission is obtained from  Maurice
 * Schurink.  Access to the source code contained herein is hereby forbidden to anyone except current  Maurice Schurink
 * employees, managers or contractors who have executed Confidentiality and Non-disclosure agreements explicitly
 * covering such access.
 *
 * The copyright notice above does not evidence any actual or intended publication or disclosure  of  this source code,
 * which includes information that is confidential and/or proprietary, and is a trade secret, of  Maurice Schurink. ANY
 * REPRODUCTION, MODIFICATION, DISTRIBUTION, PUBLIC  PERFORMANCE, OR PUBLIC DISPLAY OF OR THROUGH USE  OF THIS  SOURCE
 * CODE  WITHOUT  THE EXPRESS WRITTEN CONSENT OF Maurice Schurink IS STRICTLY PROHIBITED, AND IN VIOLATION OF
 * APPLICABLE LAWS AND INTERNATIONAL TREATIES.  THE RECEIPT OR POSSESSION OF  THIS SOURCE CODE AND/OR RELATED
 * INFORMATION DOES NOT CONVEY OR IMPLY ANY RIGHTS TO REPRODUCE, DISCLOSE OR DISTRIBUTE ITS CONTENTS, OR TO
 * MANUFACTURE, USE, OR SELL ANYTHING THAT IT  MAY DESCRIBE, IN WHOLE OR IN PART.
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * Except as contained in this notice, the name of MSmvc. shall not be used in advertising or otherwise to promote the
 * sale, use or other dealings in this Software without prior written authorization from Maurice Schurink.
 *
 */

namespace App\system;

/**
 * Class MS_pipeline
 * @package system\pipelines
 */
class MS_filesystem {


    private $path;
    /**
     * array filled with segments based on the path
     * @var array
     */
    private $segments;

    /**
     * the filename that is request only exists when targeting a file and not a directory
     * @var mixed string
     */
    private $fileTarget;

    /**
     * if subdirectories will be included true / false
     *
     * @var bool
     */
    private $includeSubDirectories;

    /**
     * a copy of this class in the regexIterator
     *
     * @var \RecursiveRegexIterator
     */
    private $RecursiveRegexIterator;

    /**
     * @var \RecursiveDirectoryIterator
     */
    private $RecursiveDirectoryIterator;

    /**
     * MS_pipeline constructor.
     * todo: make pipeline a filemanger and use change dir to change between diretories!
     * this is the file that is requested
     *
     * @param null|string $path
     */
    function __construct($path = NULL) {
        if (!is_null($path)) {
            $this->setPath($path);
            if (is_file($path)) {
                $this->RecursiveDirectoryIterator = new \RecursiveDirectoryIterator(str_replace(DIRECTORY_SEPARATOR . $this->segments["last"], "", $this->path), \RecursiveDirectoryIterator::SKIP_DOTS);
                $this->fileTarget = $this->segments["last"];
            } else {
                $this->RecursiveDirectoryIterator = new \RecursiveDirectoryIterator($this->path, \RecursiveDirectoryIterator::SKIP_DOTS);
            }

        }
    }

    /**
     * will set the all iterators to next
     */
    public function next() {
        if ($this->RecursiveRegexIterator instanceof \RecursiveRegexIterator) {
            $this->RecursiveRegexIterator->next();
        }
        $this->RecursiveDirectoryIterator->next();
    }

    /**
     * @return bool
     */
    public function valid() {
        return TRUE;
        if ($this->RecursiveDirectoryIterator->valid() || ($this->RecursiveRegexIterator instanceof \RecursiveRegexIterator && $this->RecursiveRegexIterator->accept())) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function current(){
        if ($this->RecursiveRegexIterator instanceof \RecursiveRegexIterator) {
            return $this->RecursiveRegexIterator->current();
        }else{
            return $this->RecursiveDirectoryIterator->current();
        }
    }


    /**
     * this method will set the segments for the directory
     *
     * @param string $path
     */
    public function setSegments(string $path) {
        $segments = explode(DIRECTORY_SEPARATOR, $path);
        $segments["last"] = end($segments);
        $segments["first"] = reset($segments);
        $segments["full"] = $path;
        $this->segments = $segments;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path) {
        $this->path = $this->cleanPath($path);
        $this->setSegments($this->path);
    }

    /**
     * @param $path
     *
     * @return mixed
     */
    public function cleanPath($path) {
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }


    private function includeAsDirectory() {
        //$this->requestedDataSet = new R
    }


    /**
     * @param            $file
     * @param array|NULL $data
     *
     * @return string
     */
    public static function executeAndReturnFileContent($file, array $data = NULL) {
        if (is_array($data)) {
            extract($data, EXTR_SKIP);
        }
        ob_start();
        include $file;
        return ob_get_clean();
    }

    /**
     * @param $file
     *
     * @return array
     */
    public static function getClassesWithinFile($file) {
        $php_code = file_get_contents($file);
        $classes = [];
        $tokens = token_get_all($php_code);
        $count = count($tokens);
        for ($i = 2; $i < $count; $i++) {
            if ($tokens[$i - 2][0] == T_CLASS && $tokens[$i - 1][0] == T_WHITESPACE && $tokens[$i][0] == T_STRING) {

                $class_name = $tokens[$i][1];
                $classes[] = $class_name;
            }
        }
        return $classes;
    }

    /**
     * Determine if a file exists.
     *
     * @param  string $path
     *
     * @return bool
     */
    public function exists($path) {
        return file_exists($path);
    }

    /**
     * @param $file
     *
     * @return string
     */
    public static function returnViewFilePath($file) {
        return self::$root . 'resources/views/' . $file . '.php';
    }

    /**
     * @param $file
     *
     * @return string
     */
    public static function returnLayoutFilePath($file) {
        return self::$root . 'resources/views/layouts/' . $file . '.php';
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public static function getFileContent(string $filename) {
        return file_get_contents(self::$root . $filename);
    }

    /**
     * the callback foreach file that is found
     *
     * @param                $callbackMethod
     * @param null|          $callbackObject
     */
    private function fileAction($callbackMethod, $callbackObject = NULL) {
        if (!is_null($this->fileTarget)) {
            if ($callbackObject !== NULL) {
                $callbackObject->$callbackMethod(new \SplFileInfo($this->path));
            } else {
                $callbackMethod(new \SplFileInfo($this->path));
            }
        } else {
            if ($callbackObject !== NULL) {
                while ($this->RecursiveDirectoryIterator->current()) {
                    $callbackObject->$callbackMethod($this->current());
                    $this->next();
                }
            } else {
                while ($this->valid()) {
                    $callbackMethod($this->current());
                    $this->next();
                }
            }

        }
    }


    /**
     * @param \SplFileInfo $target
     */
    private function includeTarget(\SplFileInfo $target) {
        var_dump($target);
        include $target->getPathname();

    }

    public function include () {
        $this->fileAction("includeTarget", $this);
    }

    /**
     * @return bool
     */
    public function isIncludeSubDirectories() {
        return $this->includeSubDirectories;
    }

    /**
     * @param bool $includeSubDirectories
     */
    public function setIncludeSubDirectories(bool $includeSubDirectories = TRUE) {
        $this->includeSubDirectories = $includeSubDirectories;
    }

    /**
     * @param $regex
     */
    public function regexFilter($regex) {
        $this->RecursiveRegexIterator = new \RecursiveRegexIterator($this->RecursiveDirectoryIterator, $regex);
        var_dump($this->RecursiveRegexIterator->accept());
        var_dump($this->RecursiveRegexIterator->current());
    }

    /**
     * @param      $extensions
     * @param bool $only : if you want to only return the files that have this extension or all the files except these
     */
    public function filterExtensions($extensions, bool $only = FALSE) {
        if (is_array($extensions)) {
            implode(array_map("ltrim", $extensions, "\x2E"), "|");
            // todo: fix this
        } else {
            $extensions = ltrim($extensions, "\x2E");
            //    var_dump(preg_match("/^.*\.($extensions)$/i",$this->getPathname()));
            $this->regexFilter("/^.*\.($extensions)$/i");
        }
    }


}

// todo: make a pipeline sublayer to interacte with data providers
// todo: database config files support
/*
	//todo: improve the include location
	//todo: add folder inclusion
// todo: add documentation
*/