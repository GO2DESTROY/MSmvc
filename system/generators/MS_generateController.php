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
			foreach($this->keys as $key) {
				$keyString .= '$'.$key . ',';
			}
			$keyString = rtrim($keyString, ',');

			$content = str_replace('$keys$', $keyString, $content);
		}

		fwrite($this->file, $content);
		fclose($this->file);
	}
}