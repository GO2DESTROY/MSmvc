<?php
namespace system\generators;

class MS_generateController
{
	public $file;
	public $template;
	public $name;
	public $columns;
	public $keys;

	public function basicGenerate() {
		$this->createFile();
		$this->openTemplate();
		$this->writeFile();
	}

	public function generateFromDataSet() {
		$this->createFile();
		$this->openTemplate(TRUE);
		$this->writeFile(TRUE);
	}

	private function createFile() {
		$this->file = fopen('controllers/' . $this->name . '.php', 'w');
	}

	private function openTemplate($database = FALSE) {
		if($database === TRUE) {
			$this->template = file_get_contents('templates/controllerFromDatabase.txt', TRUE);
		}
		else {
			$this->template = file_get_contents('templates/controller.txt', TRUE);
		}
	}

	private function writeFile($database = FALSE) {
		$content = str_replace('$name$', $this->name, $this->template);

		if($database == TRUE) {
			$keyString = '';
			$requestDataSet='';
			foreach($this->keys as $key) {
				$keyString .= '$'.$key . ',';
			}
			foreach($this->columns as $column) {
				$requestDataSet .= '$_REQUEST["'.$column.'"],';
			}
			$content = str_replace('$requestDataSet$', rtrim($requestDataSet,','), $content);
			$content = str_replace('$keys$', rtrim($keyString, ','), $content);
		}

		fwrite($this->file, $content);
		fclose($this->file);
	}
}