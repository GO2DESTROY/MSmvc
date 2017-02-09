<?php
namespace App\system\databases;

use App\system\models\MS_model;
use App\system\models\properties\MS_property;

/**
 * Class MS_queryBuilder: this class will build the queries based on the models
 * @package MSmvc\system\databases
 */
class  MS_queryBuilder {

    /**
     * the model to be used
     * @var MS_model
     */
    protected $model;

    /**
     * the table to be used
     * @var string
     */
    protected $table;

    /**
     * fields to be selected
     * @var array
     */
    private $targetFields;

    /**
     * fields to add to the table
     * @var array
     */
    private $addFields;

    /**
     * fields that are marked as primary keys
     * @var array
     */
    protected $primaryKeys;

    /**
     * the SQL query that will be used
     * @var string
     */
    private $query;

    /**
     * fields that are used in the where statement
     * @var array
     */
    private $whereFields;

    /**
     * todo: merge insert and update to target data
     * data that will be used for insert
     * @var array
     */
    private $insertData;

    /**
     * data used for updates
     * key are fields and the data is the values
     * if a model is supplied in the constructor then the model will be used instead
     * @var array
     */
    private $updateData;

    /**
     * the database connection resource name to be used
     * @var string
     */
    private $databaseConnection;

    /**
     * query type that will be executed
     * SELECT | UPDATE | DELETE | INSERT INTO
     * @var string
     */
    protected $type;

    /**
     * real data that is used in the prepare statement
     * @var array
     */
    private $prepareData;

    use MS_sqlStatements;

    /**
     * todo: add support without a model and split up to a class that has a model
     * MS_modelQueryBuilder constructor.
     *
     * @param \App\system\models\MS_model $model
     */
    function __construct(MS_model $model = NULL) {
        if (!is_null($model)) {
            $this->model = $model;
            $this->setDatabaseConnection($this->model->getDataBaseConnection());
            $this->setTable($model);
        }
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
            $this->targetFields[] = $select;
        } else {
            foreach ($select as $item) {
                $this->targetFields[] = $item;
            }
        }
        $this->type = 'SELECT';
        return $this;
    }

    /**
     * This will set the query type to delete
     *
     * @param null $table
     *
     * @return $this
     */
    public function delete($table = NULL) {
        if (!empty($table)) {
            $this->setTable($table);

        }
        $this->type = 'DELETE';
        return $this;
    }

    /**
     * @param string $table
     *
     * @return $this
     */
    public function from($table) {
        $this->setTable($table);
        return $this;
    }

    /**
     * @param string $table
     *
     * @return $this
     */
    public function update($table) {
        $this->type = 'UPDATE';
        $this->setTable($table);
        return $this;
    }

    /**
     * MS_model or array
     *
     * @param $data
     *
     * @return $this
     * @throws \Exception
     */
    public function set(array $data = NULL) {
        if ($this->model instanceof MS_model) {
            /**
             * @var $field MS_property
             */
            foreach ($this->model->getFieldCollection() as $field) {
                $this->updateData[$field->name] = $field->getValue();
            }
        } elseif (isAssoc($data)) {
            foreach ($data as $field => $value) {
                $this->targetFields[] = $field;
                $this->prepareData[] = $value;
            }
            //data and values
            // $this->updateData = $data;
        } elseif (is_array($data)) {
            $this->targetFields = $data;
        } else {
            throw new \Exception("Format not supported");
        }
        return $this;
    }

    /**
     * @param mixed $databaseConnection
     *
     * @return $this
     */
    public function setDatabaseConnection(string $databaseConnection) {
        $this->databaseConnection = $databaseConnection;
        return $this;
    }

    /**
     * @param $name    :the field name
     * @param $type    : the field type
     * @param $options : other options
     *
     * @return $this
     */
    public function addField($name, $type, $options) {
        $this->addFields[] = ['name' => $name, 'type' => $type, 'options' => $options];
        return $this;
    }

    /**
     * @param        $key
     * @param string $value
     *
     * @return \App\system\databases\MS_queryBuilder
     */
    public function where($key, $value = '?') {
        return $this->where_filter($key, $value, 'AND ');
    }

    /**
     * @param string $field
     */
    private function addInsertField(string $field) {
        if (!in_array($field, $this->targetFields)) {
            $this->targetFields[] = $field;
        }
    }

    /**
     * @param $data
     *
     * @return $this
     */
    public function insert($data = NULL) {
        $this->type = "INSERT INTO";
        if ($this->model instanceof MS_model) {
            $dataToInsert = [];
            /**
             * @var $field MS_property
             */
            foreach ($this->model->getFieldCollection() as $field) {
                $this->addInsertField($field->name);
                $dataToInsert[] = $field->getValue();
            }
            $this->insertData[] = $dataToInsert;
        } elseif (is_array($data)) {
            $dataToInsert = [];
            if (isAssoc($data)) {
                foreach ($data as $field => $item) {
                    $this->addInsertField($field);
                    $dataToInsert[] = $item;
                }
            } else {
                $dataToInsert[] = $data;
            }
            $this->insertData[] = $dataToInsert;
        } else {
            new \Exception("format not supported");
        }
        return $this;
    }

    /**
     * @param $data
     *
     * @return $this
     */
    public function insertBulk($data) {
        foreach ($data as $item) {
            $this->insert($item);
        }
        return $this;
    }

    /**
     * @param $table
     *
     * @return $this
     */
    public function into($table) {
        $this->setTable($table);
        return $this;
    }

    /**
     * @param        $key
     * @param string $value
     *
     * @return \App\system\databases\MS_queryBuilder
     */
    public function where_or($key, $value = '?') {
        return $this->where_filter($key, $value, 'OR ');
    }

    /**
     * This will show all tables
     * @return $this
     */
    public function showTables(){
        $this->addStatementToQuery($this->sql_show." tables");
        return $this;
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
     * this method will build the query based on the set values
     */
    protected function buildQuery() {
        switch ($this->type) {
            case 'SELECT':
                $query = "$this->sql_select ";
                foreach ($this->targetFields as $field) {
                    $query .= $field . ',';
                }
                $this->addStatementToQuery(rtrim($query, ',') . " $this->sql_from $this->table");
                break;
            case 'DELETE':
                $this->addStatementToQuery("$this->sql_delete $this->table");
                break;
            case 'UPDATE':
                $this->addStatementToQuery("$this->sql_update $this->table $this->sql_set ");
                $this->addStatementToQuery($this->buildUpdateStatement());
                break;
            case 'INSERT INTO':
                $this->addStatementToQuery("$this->sql_insert $this->table ");
                $this->addStatementToQuery($this->buildInsertStatement());
                break;
            case 'NONE':
                break;
        }
        if (!empty($this->whereFields)) {
            $query = " $this->sql_where ";
            foreach ($this->whereFields as $field) {
                if ($this->check_operator($field['key']) == FALSE) {
                    $query .= "$field[key] = $field[value] $field[type]";
                } else {
                    $query .= "$field[key] $field[value] $field[type]";
                }
            }
            $this->addStatementToQuery(rtrim($query, end($this->whereFields)['type']));
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
        if (!is_null($values)) {
            $this->prepareData = $values;
        }
        $this->buildQuery();
        $call = new MS_db($this->databaseConnection);
        return $call->query($this->query, $this->prepareData);
    }

    /**
     * setter for the table
     *
     * @param $table MS_model | string
     */
    protected function setTable($table) {
        if ($table instanceof MS_model) {
            $this->model = $table;
            $modelInformation = new \ReflectionClass($table);
            $this->table = rtrim($modelInformation->getShortName(), 'Model');
        } else {
            $this->table = $table;
        }
    }

    /**
     * this method will add the statement to the query
     *
     * @param string $statement : sql query
     */
    protected function addStatementToQuery(string $statement) {
        $this->query .= $statement;
    }

    /**
     * sql statement for the insert fields and data
     * @return string
     */
    private function buildInsertStatement() {
        if (!is_null($this->targetFields)) {
            $this->addStatementToQuery("(" . implode(",", $this->targetFields) . ") ");
        }
        $query = "$this->sql_values ";
        foreach ($this->insertData as $insertDataCollection) {
            $query .= "(";
            foreach ($insertDataCollection as $insertData) {
                $query .= "?,";
                $this->prepareData[] = $insertData;
            }
            $query = rtrim($query, ",") . "),";
        }
        return rtrim($query, ",");
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function buildUpdateStatement() {
        if (!is_null($this->targetFields)) {
            return implode("=?,", $this->targetFields);
        } else {
            throw new \Exception("No fields targeted");
        }
    }
}