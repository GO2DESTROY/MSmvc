<?php

namespace system;

use system\pipelines\MS_pipeline;

class MS_view
{
	public  $viewCollection;
	public  $viewDataCollection;
	public  $masterFile;
	private $bundleCollection;

	/**
	 * @return mixed: the view files is returned and filled with provided data
	 */
	public function loadMasterView() {
		$view = MS_pipeline::returnViewFilePath($this->masterFile['view']);
		if(is_array($this->masterFile['data'])) {
			extract($this->masterFile['data'], EXTR_SKIP);
		}
		include $view;
	}
}