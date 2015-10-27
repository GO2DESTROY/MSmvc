<?php

/*
 * todo: add directories support
 * todo: add rar support
 */
namespace system\helpers;

use blueprints\MS_mainInterface;
use system\MS_core;

class MS_compress implements MS_mainInterface
{
	private $archiveFile;
	private $openZipFile;
	private $writeAble;
	private $archiveType;

	function __construct($type) {
		parent::__construct();

		if($type == 'zip') {
			$this->createNewZip();
		}
		elseif($type == 'rar') {
			$this->createNewRar();
		}
	}

	public function __set($name, $value) {
		$this->$name = $value;
	}

	public function __get($name) {
		return $this->$name;
	}

	/**
	 * @throws \Exception: if we cannot create a zip file we throw an exception
	 */
	public function createNewZip() {
		$this->archiveFile = new MS_zip();
	}

	public function createNewRar() {
		$this->archiveFile = new MS_rar();
	}

	/**
	 * @param $name : the name of the new directory
	 *
	 * @throws \Exception: if we cannot write to the archive we throw an exception
	 */
	public function createNewDirectory($name) {
		if($this->__get('writeAble') == TRUE) {
			$this->archiveFile->addEmptyDir($name);
		}
		else {
			throw new \Exception("File isn't a write able file");
		}
	}

	/**
	 * @param $file : the zip file to use
	 *
	 * @throws \Exception : if we cannot open the zip file we throw an exception
	 */
	public function openArchive($file) {
		$this->__set('archiveFile', new ZipArchive());
		$this->__set('openZipFile', $this->archiveFile->open($file, ZipArchive::CREATE));
		if($this->__get('openZipFile') !== TRUE) {
			throw new \Exception("Failed to open the zip file");
		}
		$this->checkWriteAble($file);
	}

	/**
	 * @param      $file : the file to add to the archive
	 * @param null $name : the new name for the file
	 *
	 * @throws \Exception: if we cannot write to the archive we throw an exception
	 */
	public function addFile($file, $name = NULL) {
		if($this->__get('writeAble') == TRUE) {
			if(is_null($name)) {
				$this->archiveFile->addFile($file);
			}
			else {
				$this->archiveFile->addFile($file, $name);
			}
		}
		else {
			throw new \Exception("File isn't a write able file");
		}

	}

	/**
	 * @param $fileName
	 * @param $content
	 *
	 * @throws \Exception
	 */
	public function addString($fileName, $content) {
		if($this->__get('writeAble') == TRUE) {
			$this->archiveFile->addFromString($fileName, $content);
		}
		else {
			throw new \Exception("File isn't a write able file");
		}
	}

	public function addDirectory() {

	}

	/**
	 * @param $key : the key to use to delete from the archive
	 */
	public function deleteFrom($key) {
		if(is_int($key)) {
			$this->archiveFile->deleteIndex($key);
		}
		else {
			$this->archiveFile->deleteName($key);
		}
	}

	public function getContent() {
	}

	/**
	 * @param $password : the password to be used to open the archive
	 */
	public function usePassword($password) {
		$this->archiveFile->setPassword($password);
	}

	/**
	 * @param null $location : the location relative to /uploads
	 */
	public function extract($location = NULL) {
		if(is_null($location)) {
			$this->archiveFile->extractTo('./uploads');
		}
		else {
			$this->archiveFile->extractTo('./uploads/' . $location);
		}
	}

	/**
	 * we close the zip file all the changes will now be written to the archive
	 */
	public function closeZipFile() {
		$this->archiveFile->close();
	}

	/**
	 * @return mixed: we get the status error's and such
	 */
	public function getStatusString() {
		return $this->archiveFile->getStatusString();
	}

	/**
	 * @param $file : we check if we can write to the file
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