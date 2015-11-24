<?php
namespace system\generators;

class MS_generateModel
{
	public $file;
	public $template;
	public $name;

	public function generate($name)
	{
		$this->createFile($name);
		$this->openTemplate();
		$this->writeFile($name);
	}

	private function createFile($name) {
		$this->file= fopen('models/'.$name.'.php','w');
	}

	/**
	 *  we load the template and the our public template to the content of this file.
	 */
	private function openTemplate()
	{
		//:todo : file get contents alternative
		$this->template = file_get_contents('templates/model.txt',true);
	}

	/**
	 * we set the right model name within our generated file
	 * @param $name: the name of the new model
	 */
	private function writeFile($name)
	{
		$content = str_replace('$name$',$name,$this->template);
		fwrite($this->file,$content);
		fclose($this->file);
	}
}