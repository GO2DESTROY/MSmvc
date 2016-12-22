<?php
namespace MSmvc\system\databases;

use MSmvc\system\models\MS_model;
use MSmvc\system\models\properties\MS_property;

/**
 * Class MS_modelQueryBuilder: this class will build the queries based on the models
 * @package MSmvc\system\databases
 */
class  MS_modelQueryBuilder {
	private $model;
	private $table;
	private $selectFields;
	private $addFields;
	private $primaryKeys;
	private $query;
	private $whereFields;
	private $type; //SELECT CREATE TABLE

	function __construct(MS_model $model) {
		$this->model = $model->getModelStructure();
		$modelInformation = new \ReflectionClass($model);
		$this->table = rtrim($modelInformation->getShortName(), 'Model');
	}

	/**
	 * this will set the select based on the model
	 *
	 * @param string $select : this is the select statement here you can select the field csv
	 *
	 * @return $this
	 */
	public function select(string $select = '*') {
		$select = explode(',', $select);
		if ($select == '*') {
			foreach ($this->model['fields'] as $field) {
				$this->selectFields[] = $field->name;
			}
		} else {
			foreach ($select as $item) {
				$this->selectFields[] = $item;
			}
		}
		$this->type = 'SELECT';
		return $this;
	}

	/**
	 * @return $this
	 */
	public function modelToTable() {
		$this->type = 'CREATE TABLE';
		return $this;
	}

	/**
	 * @param $name :the field name
	 * @param $type : the field type
	 * @param $options : other options
	 *
	 * @return $this
	 */
	public function addField($name, $type, $options) {
		$this->addFields[] = ['name' => $name, 'type' => $type, 'options' => $options];
		return $this;
	}

	/**
	 * @return mixed
	 */
	private function getPrimaryKeysString() {
		return "PRIMARY KEY(".implode(",",$this->primaryKeys).")";
	}

	/**
	 * @param MS_property $property
	 * @internal param mixed $primaryKeys
	 */
	public function setPrimaryKeys(MS_property $property) {
		if($property->isPrimaryKey() == true) {
			$this->primaryKeys[] = $property->name;
		}
	}

	/**
	 * @param        $key
	 * @param string $value
	 *
	 * @return \MSmvc\system\databases\MS_modelQueryBuilder
	 */
	public function where($key, $value = '?') {
		return $this->where_filter($key, $value, 'AND ');
	}

	/**
	 * @param        $key
	 * @param string $value
	 *
	 * @return \MSmvc\system\databases\MS_modelQueryBuilder
	 */
	public function where_or($key, $value = '?') {
		return $this->where_filter($key, $value, 'OR ');
	}

	/**
	 * @param        $key
	 * @param string $value
	 * @param string $type
	 *
	 * @return $this
	 */
	private function where_filter($key, $value = '?', $type = 'AND ') {
		$this->whereFields[] = ['key' => $key, 'value' => $value, 'type' => $type];
		return $this;
	}

	/**
	 * @param \MSmvc\system\models\properties\MS_property $property
	 *
	 * @return string
	 */
	private function propertyToField(MS_property $property) {
		return rtrim("$property->name $property->type ($property->length) " . $property->getAutoIncrement(), " ") . ", ";
	}

	/**
	 * this method will build the query based on the set values
	 */
	private function buildQuery() {
		switch ($this->type) {
			case 'SELECT':
				$query = "SELECT ";
				foreach ($this->selectFields as $field) {
					$query .= $field . ',';
				}
				$this->query = rtrim($query, ',') . ' FROM ' . $this->table;
				break;
			case 'CREATE TABLE':
				$query = "CREATE table $this->table (";
				foreach ($this->model['fields'] as $field) {
					//todo: remove if statement from the loop
					$query .= $this->propertyToField($field);
					$this->setPrimaryKeys($field);
				}
				$query .= $this->getPrimaryKeysString();
				$this->query = $query.")";
				break;
		}
		if (!empty($this->whereFields)) {
			$query = ' WHERE ';
			foreach ($this->whereFields as $field) {
				if ($this->check_operator($field['key']) == FALSE) {
					$query .= "$field[key] = $field[value] $field[type]";
				} else {
					$query .= "$field[key] $field[value] $field[type]";
				}

			}
			$this->query .= rtrim($query, end($this->whereFields)['type']);
		}
	}

	/**
	 * @param string $str : string to be checked for sql operators
	 *
	 * @return int: 1 for success 0 for no and false for error
	 */
	private function check_operator(string $str) {
		return preg_match('/(<|>|!|=|\sIS NULL|\sIS NOT NULL|\sEXISTS|\sBETWEEN|\sLIKE|\sIN\s*\(|\s)/i', trim($str));
	}

	/**
	 * @param array|NULL $values : the values to use for the prepare statement
	 *
	 * @return mixed: the query results
	 */
	public function execute(array $values = NULL) {

		$this->buildQuery();
		$call = new MS_db($this->model['database']);
		var_dump($this->query);
		return $call->query($this->query, $values);
	}
}