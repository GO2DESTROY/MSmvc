<?php
/**
 * Copyright (c) 2017.
 * MSmvc
 * An open source application development framework for PHP
 * Copyright (c) 2017 Maurice Schurink, All Rights Reserved.
 * NOTICE:  All information contained herein is, and remains the property of  Maurice Schurink. The intellectual and
 * technical concepts contained herein are proprietary to Maurice Schurink and may be covered by U.S. and Foreign
 * Patents, patents in process, and are protected by trade secret or copyright law. Dissemination of this information
 * or reproduction of this material is strictly forbidden unless prior written permission is obtained from  Maurice
 * Schurink.  Access to the source code contained herein is hereby forbidden to anyone except current  Maurice Schurink
 * employees, managers or contractors who have executed Confidentiality and Non-disclosure agreements explicitly
 * covering such access.
 * The copyright notice above does not evidence any actual or intended publication or disclosure  of  this source code,
 * which includes information that is confidential and/or proprietary, and is a trade secret, of  Maurice Schurink. ANY
 * REPRODUCTION, MODIFICATION, DISTRIBUTION, PUBLIC  PERFORMANCE, OR PUBLIC DISPLAY OF OR THROUGH USE  OF THIS  SOURCE
 * CODE  WITHOUT  THE EXPRESS WRITTEN CONSENT OF Maurice Schurink IS STRICTLY PROHIBITED, AND IN VIOLATION OF
 * APPLICABLE LAWS AND INTERNATIONAL TREATIES.  THE RECEIPT OR POSSESSION OF  THIS SOURCE CODE AND/OR RELATED
 * INFORMATION DOES NOT CONVEY OR IMPLY ANY RIGHTS TO REPRODUCE, DISCLOSE OR DISTRIBUTE ITS CONTENTS, OR TO
 * MANUFACTURE, USE, OR SELL ANYTHING THAT IT  MAY DESCRIBE, IN WHOLE OR IN PART.
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * Except as contained in this notice, the name of MSmvc. shall not be used in advertising or otherwise to promote the
 * sale, use or other dealings in this Software without prior written authorization from Maurice Schurink.
 */

namespace App\system;

use App\system\databases\MigrationBuilder;
use App\system\databases\MigrationHandler;

/**
 * Class MS_pipeline
 * @package system\pipelines
 */
class Filesystem implements \SeekableIterator, \RecursiveIterator {

    /**
     * this prepend the view path
     */
    const USE_VIEW_PATH = 1;

    /**
     * this prepend the view path
     */
    const USE_LAYOUT_PATH = 2;

    /**
     * @var Optionals
     */
    private $options;

    /**
     * @var string
     */
    private $path;
    /**
     * array filled with segments based on the path
     * @var array
     */
    private $segments;

    /**
     * @var int;
     */
    private $position = 0;

    /**
     * The current depth of the directory
     * @var int
     */
    private $depth = 0;

    /**
     * maximum depth to transverse to
     * @var int
     */
    private $maxDepth = 1;

    /**
     * array with all the fileobjects
     * @var array
     */
    private $collection;

    /**
     * array filled with data keys are variables and values data
     * @var array
     */
    private $localData;

    /**
     * @var array
     */
    private $fileContents;

    /**
     * this array contains all the filters indexed by depth and asterisk for all
     * @var array
     */
    private $filters;

    /**
     * the callback that will be used
     * @var array | string
     */
    private $callback;

    /**
     * Filesystem constructor.
     *
     * @param null|string $path
     */
    function __construct($path) {

        $this->options = new Optionals(func_get_args(), $path, TRUE);

        $this->setPath($path);
        if (is_file($this->getPath())) {
            $this->collection[] = new \SplFileInfo($this->getPath());
        } else {
            $glob = glob($this->getPath() . "*");
            foreach ($glob as $file) {
                $this->collection[] = new \SplFileInfo($file);
            }
        }
    }

    /**
     * this method will set the segments for the directory
     *
     * @param string $path
     */
    public function setSegments(string $path) {
        $segments = array_filter(explode(DIRECTORY_SEPARATOR, $path));
        $segments["last"] = end($segments);
        $segments["first"] = reset($segments);
        $segments["full"] = $path;
        $this->segments = $segments;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path) {
        if ($this->options->checkExists(self::USE_VIEW_PATH)) {
            $path = "App/resources/views/$path";
        } elseif ($this->options->checkExists(self::USE_LAYOUT_PATH)) {
            $path = "App/resources/views/layouts/$path";
        }

        if (!is_file($path)) {
            if ($this->endsWith($path, DIRECTORY_SEPARATOR === FALSE)) {
                $path = $path . DIRECTORY_SEPARATOR;
            }
        }
        $this->path = $this->cleanPath($path);
        $this->setSegments($this->path);
    }

    /**
     * @param $FullStr
     * @param $needle
     *
     * @return bool
     */
    private function endsWith($FullStr, $needle) {
        $StrLen = strlen($needle);
        $FullStrEnd = substr($FullStr, strlen($FullStr) - $StrLen);
        return $FullStrEnd == $needle;
    }

    /**
     * @return string
     */
    public function getPath() {
        return $this->path;
    }


    /**
     * @param $path
     *
     * @return mixed
     */
    public function cleanPath($path) {
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    /**
     * @return array
     */
    public function getFileContents(): array {
        return $this->fileContents;
    }

    /**
     * @return int
     */
    public function getMaxDepth(): int {
        return $this->maxDepth;
    }

    /**
     * @param int $maxDepth
     */
    public function setMaxDepth(int $maxDepth) {
        $this->maxDepth = $maxDepth;
    }

    /**
     * @return int
     */
    public function getDepth() {
        return $this->depth;
    }

    /**
     * @param mixed $fileContents
     */
    private function addFileContents($fileContents) {
        $this->fileContents[] = $fileContents;
    }

    /**
     * @param     $filter
     * @param int $depth
     */
    public function addFilter(FileFilter $filter, int $depth = NULL) {
        if ($depth === NULL) {
            $depth = "*";
        }
        $this->filters[$depth][] = $filter;
    }


    /**
     * @return array
     */
    public function getCurrentFilter() {
        return $this->getFilterByDepth($this->depth);
    }

    /**
     * @param $depth
     *
     * @return array
     */
    private function getFilterByDepth($depth) {
        $localFilters = [];
        if (!empty($this->filters[$depth])) {
            $localFilters = array_merge($localFilters, $this->filters[$depth]);
        }
        if (!empty($this->filters["*"])) {
            $localFilters = array_merge($localFilters, $this->filters["*"]);
        }
        return $localFilters;
    }

    /**
     * @param \SplFileInfo $file
     */
    private function executeAndReturnFileContent(\SplFileInfo $file) {
        if (is_array($this->getLocalData())) {
            extract($this->getLocalData(), EXTR_SKIP);
        }
        ob_start();
        include $file->getPathname();
        $this->addFileContents(ob_get_clean());
    }

    /**
     * Determine if a file exists.
     * @return bool
     */
    public function exists() {
        return file_exists($this->path);
    }

    /**
     * @return string
     */
    public function getFileContent() {
        return file_get_contents($this->current());
    }

    /**
     * the callback foreach file that is found
     * todo: ERROR IS FOUND !! het laatste char wordt getrimt als we hem clonen gebeurt in setpath
     *
     * @param string | array $callback
     */
    private function fileAction() {
        while ($this->valid()) {
            //trigger on dir and depth match todo: change so callback will only be called on valid fileobject filter todo: create interface for check!!
            //todo: problem with cloning and the customCallBack
            if ($this->passFilters() === TRUE) {
                if ($this->getDepth() <= $this->getMaxDepth() && $this->current()->isDir() === TRUE) {
                    $this->getChildren()->fileAction();
                } else {
                    call_user_func_array($this->callback, [$this->current()]);
                }
            }
            $this->next();
        }
        $this->rewind();
    }

    /**
     * this is the public interface of fileAction
     *
     * @param $callback
     */
    public function customCallback($callback) {
        $this->callback = $callback;
        $this->fileAction();
    }

    public function show() {
        $this->callback = "var_dump";
        $this->fileAction();
    }

    /**
     * @param \SplFileInfo $target
     */
    private function includeTarget(\SplFileInfo $target) {
        include $target->getPathname();
    }

    public function include () {
        $this->callback = [$this, "includeTarget"];
        $this->fileAction([$this, "includeTarget"]);
    }

    /**
     * @return array
     */
    public function executeAndReturn() {
        $this->callback = [$this, "executeAndReturnFileContent"];
        $this->fileAction();
        return $this->getFileContents();
    }

    /**
     * @return mixed
     */
    public function getLocalData() {
        return $this->localData;
    }

    /**
     * @param $regex
     */
    public function regexFilter($regex) {
        /**
         * @var $item \SplFileInfo
         */
        foreach ($this->collection as $key => $item) {
            if (!preg_match($regex, $item->getFilename())) {
                $this->unsetEntryByKey($key);
            }
        }
    }

    /**
     * @param $extensions
     *
     * @internal param bool $only : if you want to only return the files that have this extension or all the files
     *           except these
     */
    public function filterExtensions($extensions) {
        if (is_array($extensions)) {
            $extensions = implode(array_map("ltrim", $extensions, "\x2E"), "|");
            $this->regexFilter("/^.*\.($extensions)$/i");
        } else {
            $extensions = ltrim($extensions, "\x2E");
            $this->regexFilter("/^.*\.($extensions)$/i");
        }
    }

    /**
     * removes the entry of the given id
     *
     * @param int $key
     */
    public function unsetEntryByKey(int $key) {
        $this->seek($key);
        $this->unsetCurrentEntry();
    }

    /**
     * removes the current entry
     */
    public function unsetCurrentEntry() {
        unset($this->collection[$this->position]);
        $this->collection = array_values($this->collection);
    }

    public function __clone() {
        $this->collection = NULL;
        $this->rewind();
        ++$this->depth;
    }

    /**
     * Return the current element
     * @link  http://php.net/manual/en/iterator.current.php
     * @return \SplFileObject Can return any type.
     * @since 5.0.0
     */
    public function current() {
        return $this->collection[$this->position];
    }

    /**
     * Move forward to next element
     * @link  http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next() {
        ++$this->position;
    }

    /**
     * Return the key of the current element
     * @link  http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key() {
        return $this->position;
    }

    /**
     * Checks if current position is valid
     * @link  http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid() {
        return isset($this->collection[$this->position]) ? TRUE : FALSE;
    }

    /**
     * @return bool
     */
    private function passFilters() {
        /**
         * @var $filter FileFilter
         */
        foreach ($this->getCurrentFilter() as $filter) {
            if ($filter->filter($this->current()) === FALSE) {
                return FALSE;
            }
        }
        return TRUE;
    }


    /**
     * Rewind the Iterator to the first element
     * @link  http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind() {
        $this->position = 0;
    }

    /**
     * Seeks to a position
     * @link  http://php.net/manual/en/seekableiterator.seek.php
     *
     * @param int $position : The position to seek to.
     *
     * @return void
     * @since 5.1.0
     */
    public function seek($position) {
        if (!isset($this->collection[$position])) {
            throw new \OutOfBoundsException("invalid seek position ($position)");
        } else {
            $this->position = $position;
        }
    }

    /**
     * Returns if an iterator can be created for the current entry.
     * @link  http://php.net/manual/en/recursiveiterator.haschildren.php
     * @return bool true if the current entry can be iterated over, otherwise returns false.
     * @since 5.1.0
     */
    public function hasChildren() {
        return $this->current()->isDir() ? TRUE : FALSE;
    }

    /**
     * Returns an iterator for the current entry.
     * @link  http://php.net/manual/en/recursiveiterator.getchildren.php
     * @return Filesystem
     * @since 5.1.0
     */
    public function getChildren() {
        $child = clone $this;
        $child->__construct($this->current()->getPathname());
        return $child;
    }
}