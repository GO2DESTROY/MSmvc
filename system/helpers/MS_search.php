<?php
namespace system\helpers;

use blueprints\MS_mainInterface;

class MS_search
{

	public $dataSet; // array of values
	public $pronunciationMatch = false; // checks for the pronunciation of the words optimized for the english language
	public $acceptance         = 0;//lower values for optimized speed. 0 will disable this feature and give the best results

	function __construct() {
		$this->dataSet = ['apple', 'pineapple', 'banana', 'orange', 'radish', 'carrot', 'pea', 'bean', 'potato'];
	}


	public function returnClosest($input) {
		$difference = -1;
		foreach($this->dataSet as $dataRow) {
			if($this->pronunciationMatch == TRUE) {
				$lev = levenshtein(metaphone($input, $this->acceptance), metaphone($dataRow, $this->acceptance));
			}
			else {
				$lev = levenshtein($input, $dataRow);
			}
			var_dump($lev);
			if($lev == 0) {
				$closest    = $dataRow;
				$difference = 0;
				$exact      = TRUE;
				break;
			}
			elseif($lev <= $difference || $difference < 0) {
				$closest    = $dataRow;
				$difference = $lev;
				$exact      = FALSE;
			}
		}
		return ['result' => $closest, 'exact' => $exact, 'difference' => $difference];
	}

	public function returnOrdered($input, $reverse = FALSE) {
		foreach($this->dataSet as $dataRow) {
			if($this->pronunciationMatch == TRUE) {
				$temp_arr[] = levenshtein(metaphone($input, $this->acceptance), metaphone($dataRow, $this->acceptance));
			}
			else {
				$temp_arr[] = levenshtein($input, $dataRow);
			}
		}
		if($reverse == TRUE) {
			arsort($temp_arr);
		}
		else {
			asort($temp_arr);
		}
		foreach($temp_arr as $key => $value) {
			$sorted_arr[] = $this->dataSet[$key];
		}
		return $sorted_arr;
	}
}