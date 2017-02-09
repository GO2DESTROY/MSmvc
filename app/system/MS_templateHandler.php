<?php
/**
 * Created by PhpStorm.
 * User: Maurice
 * Date: 05-Feb-17
 * Time: 13:07
 */

namespace App\system;
use App\system\models\MS_model;
use App\system\pipelines\MS_pipeline;

/**
 * Class MS_templateHandler
 * @package App\system
 */
class MS_templateHandler {
    private $template;
    private $content;

    function __construct(string $template = NULL) {
        $this->setTemplate($template);
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template = "migration-base") {
        $this->template = $template;
    }
    private function readTemplate(){
    	$file = new MS_pipeline();
    	$file->openDataHandler();
	}

	/**
	 * @param MS_model $model
	 */
	private function fillMigration(MS_model $model){
	}
}