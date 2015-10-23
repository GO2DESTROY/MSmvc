<?php
namespace system\generators;

class MS_generateController extends MS_generate
{
	public $file;
	public $template;

	function __construct($name) {
		$this->createFile($name);
		$this->openTemplate();
		$this->writeFile($name);
	}

	private function createFile($name) {
	$this->file= fopen('controllers/'.$name.'.php','w');
	}

	private function openTemplate()
	{
		//:todo : file get contents alternative
		$this->template = file_get_contents('templates/controller.txt',true);
	}

	private function writeFile($name)
	{
		$content = str_replace('$name$',$name,$this->template);
		fwrite($this->file,$content);
		fclose($this->file);
	}
}