<?php

/*
 * todo: make an audio helper for audio streaming
 */

class MS_zip extends \system\MS_core implements blueprints\MS_compressInterface
{
	private $archiveFile;
	private $openZipFile;
	private $writeAble;
	private $archiveType;

	/**
	 * @param $name   : the key to use for the magic method
	 * @param $value : the value to use for the magic method
	 *
	 * @return mixed: the interface
	 */
	public function __set($name , $value) {
		// TODO: Implement __set() method.
	}

	/**
	 * @param $name : the key to use for the magic method
	 *
	 * @return mixed: the interface
	 */
	public function __get($name ) {
		// TODO: Implement __get() method.
	}

	/**
	 * @return mixed : we create a new archive
	 * @throws Exception: if cannot create an archive we throw an exception
	 */
	public function createNewArchive() {
		$file = tempnam("tmp", "zip");
		$this->__set('archiveFile', new ZipArchive());
		$this->__set('openZipFile', $this->zipFile->open($file, ZipArchive::OVERWRITE));
		$this->__set('writeAble', TRUE);
		if($this->__get('openZipFile') !== TRUE) {
			throw new \Exception("Failed to create a new zip file");
		}
	}

	/**
	 * @param $fileName : the file name to add
	 *
	 * @return mixed : the directory added to the archive
	 * @throws Exception:  if we cannot write to the archive we throw an exception
	 */
	public function createNewDirectory($fileName) {
		if($this->__get('writeAble') == TRUE) {
			$this->archiveFile->addEmptyDir($fileName);
		}
		else {
			throw new \Exception("File isn't a write able file");
		}
	}

	/**
	 * @param $archiveFile : the archive to open
	 *
	 * @return mixed: the archive opened or an exception if it failed
	 */
	public function openArchive($archiveFile) {
		// TODO: Implement openArchive() method.
	}

	/**
	 * @param      $file : the file to add to the archive
	 * @param null $name : the new name for the file to use
	 *
	 * @return mixed: the file added to the archive or an exception if it failed
	 */
	public function addFile($file, $name = NULL) {
		// TODO: Implement addFile() method.
	}

	/**
	 * @param $fileName : the name for the file to use
	 * @param $content  : the content for the file
	 *
	 * @return mixed: the file added to the archive or an exception if it failed
	 */
	public function addString($fileName, $content) {
		// TODO: Implement addString() method.
	}

	/**
	 * @param      $directory : the directory to use
	 * @param null $name      : the optional name to use
	 *
	 * @return mixed: the directory added to the archive or an exception if it failed
	 */
	public function addDirectory($directory, $name = NULL) {
		// TODO: Implement addDirectory() method.
	}

	/**
	 * @param $key : the key to use for deleting for to a file or directory from the archive
	 *
	 * @return mixed: the file deleted from the archive or an exception if it failed
	 */
	public function deleteFrom($key) {
		// TODO: Implement deleteFrom() method.
	}

	/**
	 * @return mixed: the content from the archive or an exception if it failed
	 */
	public function getContent() {
		// TODO: Implement getContent() method.
	}

	/**
	 * @param null $location : the location to use relative from /uploads
	 *
	 * @return mixed: the content from the archive or an exception if it failed
	 */
	public function extract($location = NULL) {
		// TODO: Implement extract() method.
	}

	/**
	 * @param $file : the file to check
	 *
	 * @return bool: true or false if the file depending if the file is write able
	 */
	public function checkWriteAble($file) {
		if(is_writable($file)) {
			$this->__set('writeAble', TRUE);
		}
		else {
			$this->__set('writeAble', FALSE);
		}
	}
}