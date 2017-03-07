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

/**
 * Class MS_pipeline
 * @package system\pipelines
 */
class MS_filesystem implements \SeekableIterator {

	/**
	 * this prepend the view path
	 */
	const FILE_PATH_AS_VIEW = 1;

	/**
	 * this prepend the view path
	 */
	const TEST_TEST = 2;

	/**
	 * @var MS_optionals
	 */
	private $options;

	private $path;
	/**
	 * array filled with segments based on the path
	 * @var array
	 */
	private $segments;

	/**
	 * @var int
	 */
	private $position = 0;
	/**
	 * array with all the fileobjects
	 * @var array
	 */
	private $collection;

	/**
	 * if subdirectories will be included true / false
	 * @var bool
	 */
	private $includeSubDirectories;

	/**
	 * MS_filesystem constructor.
	 *
	 * @param null|string $path
	 */
	function __construct($path) {
		$this->options = new MS_optionals(func_get_args(),$path,true);

		$this->setPath($path);
		if (is_file($path)) {
			$this->collection[] = new \SplFileInfo($path);
		} else {
			$glob = glob($this->path);
			foreach ($glob as $file) {
				$this->collection[] = new \SplFileInfo($file);
			}
		}
	}

/*	private function checkOverloadedOption(array $options,$){

	}
*/
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
	 * Determine if a file exists.
	 * @return bool
	 */
	public function exists() {
		return file_exists($this->path);
	}

	/**
	 * @param $file
	 *
	 * @return string
	 */
	public static function returnViewFilePath($file) {
		return 'resources/views/' . $file . '.php';
	}

	/**
	 * todo: fix it in a none static way
	 *
	 * @param $file
	 *
	 * @return string
	 */
	public static function returnLayoutFilePath($file) {
		return self::$root . 'resources/views/layouts/' . $file . '.php';
	}

	/**
	 * @return string
	 */
	public function getFileContent() {
		return file_get_contents($this->current());
	}

	/**
	 * the callback foreach file that is found
	 *
	 * @param                $callbackMethod
	 * @param null           $callbackObject
	 */
	private function fileAction($callbackMethod, $callbackObject = NULL) {
		if ($callbackObject !== NULL) {
			while ($this->valid()) {
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


	/**
	 * @param \SplFileInfo $target
	 */
	private function includeTarget(\SplFileInfo $target) {
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
	 * todo: fix it without a iterator
	 *
	 * @param $regex
	 */
	public function regexFilter($regex) {
		$test = new \RegexIterator($this, $regex);
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
		return isset($this->collection[$this->position]) ? true : false;
	}

	/**
	 * Rewind the Iterator to the first element
	 * @link  http://php.net/manual/en/iterator.rewind.php
	 * @return void Any returned value is ignored.
	 * @since 5.0.0
	 */
	public function rewind() {
		reset($this->collection);
	}

	/**
	 * Seeks to a position
	 * @link  http://php.net/manual/en/seekableiterator.seek.php
	 *
	 * @param int $position <p>
	 *                      The position to seek to.
	 *                      </p>
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
}