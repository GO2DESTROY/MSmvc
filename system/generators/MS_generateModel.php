<?php
namespace system\generators;

class MS_generateModel extends MS_generate
{
	public $file;
	public $template;

	function __construct($name) {
		$this->createFile($name);
		$this->openTemplate();
		$this->writeFile($name);
	}

	private function createFile($name) {
		$this->file= fopen('models/'.$name.'.php','w');
	}

	private function openTemplate()
	{
		//:todo : file get contents alternative
		$this->template = file_get_contents('templates/model.txt',true);
	}

	private function writeFile($name)
	{
		$content = str_replace('$name$',$name,$this->template);
		fwrite($this->file,$content);
		fclose($this->file);
	}
}