<?php

namespace system\pipelines;

<<<<<<< HEAD:app/system/pipelines/MS_pipeline_push.php
/**
 * Class MS_pipeline_push
 * @package MSmvc\system\pipelines
 *          todo: split php and json file support to diffrent files and create an abstract master
 */
class MS_pipeline_push extends MS_pipeline {
    private $dataSet;
    private $dataToAdd;

    /**
     * todo: fix this method
     * todo: desired result we push lines to config files
     *
     * @param      $requestFile
     * @param      $content
     * @param bool $replace
     */
    public function addToConfig($requestFile, $content, $replace = FALSE) {
        $this->requestedDataSet = $requestFile;

        $this->requestTypeHandler = self::$dataSetsLocation['datasets'][$this->requestedDataSet];
        $this->connectToDataHandler();

        $this->pushToDataSet($content);
        self::$configCollections[$requestFile] = $this->getRequestedData();
    }

    public function closePushStream() {
        fclose($this->dataSet);
    }

    private function connectToDataHandler() {
        switch ($this->requestTypeHandler) {
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
        switch ($this->requestTypeHandler) {
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
        return fopen(self::$root . '/config/' . $this->requestedDataSet . '.php', 'a');
    }

    /**
     * @return mixed
     */
    protected function openJsonFile() {
        return json_decode(fopen(self::$root . '/config/' . $this->requestedDataSet . '.json','r+'), TRUE);
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
    }
=======
class MS_pipeline_push extends MS_pipeline
{
	private $dataSet;

	public function addToConfig($requestFile, $content, $replace = FALSE) {
		$this->requestedDataSet = $requestFile;

		$this->requestTypeHandler = self::$dataSetsLocation['datasets'][$this->requestedDataSet];
		$this->connectToDataHandler();

		$this->pushToDataSet($content);
		self::$configCollections[$requestFile] = $this->getRequestedData();
	}
	public function closePushStream() {
		fclose($this->dataSet);
	}

	private function connectToDataHandler() {
		switch($this->requestTypeHandler) {
			case 'php':
				$this->dataSet = $this->openPhpFile();
				break;
			case 'json':
				$this->dataSet = $this->openJsonFile();
				break;
			default:
				$this->dataSet = $this->openDataBaseFile();
		}
	}

	private function pushToDataSet($input) {
		switch($this->requestTypeHandler) {
			case 'php':
				$this->pushToPhpFile($input);
				break;
			case 'json':
				break;
			default:
				break;
		}
	}

	private function pushToPhpFile($input) {
		fwrite($this->dataSet,$input);
	}

	private function openPhpFile() {
		return fopen(self::$root . '/config/' . $this->requestedDataSet . '.php', 'a');
	}

	private function openJsonFile() {
		return json_decode(fopen(self::$root . '/config/' . $this->requestedDataSet . '.json','r+'), TRUE);
	}

	private function openDataBaseFile() {
		return 42; //no need to cache the dataConnecter since MS_database already does this
	}
>>>>>>> parent of 1d95c0b... query builder:app/MSmvc/system/pipelines/MS_pipeline_push.php
}