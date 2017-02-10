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
    /**
     * name of the template file
     * @var string
     */
    private $template;

    /**
     * file content
     * @var string
     */
    private $content;

    function __construct(string $template = "migration-base.php.mst") {
        $this->setTemplate($template);
    }

    /**
     * @param null $name
     * @param null $location
     */
    public function createFile($name = NULL, $location = NULL) {
        //todo: implement pipeline push
    }

    /**
     * @param array $replacements
     */
    public function applyReplacements(array $replacements) {
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template) {
        $this->template = $template;
    }

    /**
     * @return mixed
     */
    public function getTemplate() {
        return "app/system/templates/" . $this->template;
    }

    private function readTemplate($name) {
        $file = new MS_pipeline($this->getTemplate());
        $this->content = str_replace("templateClass", $name, $file->getDataSetFromRequest());
    }

    /**
     * @param MS_model $model
     */
    private function fillMigration(MS_model $model) {
    }
}