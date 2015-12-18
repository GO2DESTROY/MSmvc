<?php

use system\helpers\MS_image;

class MS_imageLayer
{
	private $currentLayer;
	private $layerCollection;

	function __construct() {
		$this->currentLayer = new MS_image();
	}

	private function setCurrentLayer($image) {
		if($image instanceof MS_image) {
			$this->layerCollection[] = $image;
			$this->currentLayer = count($this->layerCollection);
		}
		elseif(is_int($image)) {
			$this->currentLayer = $image;
		}
	}
}