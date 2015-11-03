<?php
namespace system\helpers;

class MS_search
{

	public $dataSet; // array of values
	public $pronunciationMatch = FALSE; // checks for the pronunciation of the words optimized for the english language
	public $acceptance         = 0;//lower values for optimized speed. 0 will disable this feature and give the best results

	/**
	 * @param string $input : the input value to search on
	 *
	 * @return array: the search result will be returned
	 */
	public function returnClosest($input) {
		$difference = -1;
		foreach($this->dataSet as $dataRow) {
			if($this->pronunciationMatch === TRUE) {
				$lev = levenshtein(metaphone($input, $this->acceptance), metaphone($dataRow, $this->acceptance));
			}
			else {
				$lev = levenshtein($input, $dataRow);
			}
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

	/**
	 * @param string $input   : the value to be searched on
	 * @param bool   $reverse : if you want to return the searches on reversed order set it to true
	 *
	 * @return array
	 */
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