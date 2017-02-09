<?php

namespace MSmvc\system\pipelines;
use App\system\pipelines\MS_pipeline;


/**
 * Class MS_pipeline_push
 * @package MSmvc\system\pipelines
 *          todo: split php and json file support to diffrent files and create an abstract master
 */
class MS_pipeline_push extends MS_pipeline {
    private $dataSet;
    private $dataToAdd;


    function __construct($requestData = NULL) {
        parent::__construct($requestData);
    }

    /**
     * todo: fix this method
     * todo: desired result we push lines to config files
     *
     * @param      $requestFile
     * @param      $content
     * @param bool $replace
     */
    public function addToConfig($content, $replace = FALSE) {
        $this->connectToDataHandler();
        $this->pushToDataSet($content);
    }

    public function closePushStream() {
        fclose($this->dataSet);
    }

    private function connectToDataHandler() {
        switch ($this->extension) {
            case 'php':
                $this->dataSet = $this->openPhpFile();
                break;
            case 'json':
                $this->dataSet = $this->openJsonFile();
                break;
            case'other':
            default:
                throw new \Exception("file type or structure is not unknown");
        }
    }

    /**
     * @param $input
     */
    private function pushToDataSet($input) {
        switch ($this->extension) {
            case 'php':
                $this->pushToPhpFile($input);
                break;
            case 'json':
                break;
            default:
                break;
        }
    }

    /**
     * @param $input
     */
    private function pushToPhpFile($input) {
        fwrite($this->dataSet, $input);
    }

    /**
     * @return mixed
     */
    protected function openPhpFile() {
        return fopen(self::$root . '/config/' . $this->getRequestedDataSet()["filename"] . '.php', 'a');
    }
//$lock ? LOCK_EX : 0
    /**
     * @return mixed
     */
    protected function openJsonFile() {
        return json_decode(fopen(self::$root . '/config/' . $this->getRequestedDataSet()["filename"] . '.json','r+'), TRUE);
    }

    private function addToLog($file, $line) {
        $fp = fopen(self::$root . $file, 'a');
        if (is_array($line)) {
            foreach ($line as $singleWord) {
                fwrite($fp, $singleWord . ' ');
            }
            fwrite($fp, PHP_EOL);
        } else {
            fwrite($fp, $line . PHP_EOL);
        }
        fclose($fp);
    }}