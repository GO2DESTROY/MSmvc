<?php
namespace MSmvc\system\databases;

use MSmvc\system\models\MS_model;
use MSmvc\system\models\properties\MS_property;

/**
 * Class MS_queryBuilder: this class will build the queries based on the models
 * @package MSmvc\system\databases
 */
class  MS_queryBuilder {

    /**
     * the model to be used
     * @var MS_model
     */
    private $model;

    /**
     * the table to be used
     * @var string
     */
    private $table;

    /**
     * fields to be selected
     * @var array
     */
    private $selectFields;

    /**
     * fields to add to the table
     * @var array
     */
    private $addFields;

    /**
     * fields that are marked as primary keys
     * @var array
     */
    private $primaryKeys;

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
     * fields that will be inserted into
     * @var array
     */
    private $insertFields = [];

    /**
     * data that will be used for insert
     * @var array
     */
    private $insertData;

    /**
     * the database connection resource name to be used
     * @var string
     */
    private $databaseConnection;

    /**
     * query type that will be executed
     * SELECT | CREATE TABLE | UPDATE | DELETE | INSERT INTO
     * @var string
     */
    private $type;

    /**
     * real data that is used in the prepare statement
     * @var array
     */
    private $prepareData;

    /**
     * SELECT statement
     * @var string
     */
    private $sql_select = "SELECT";

    /**
     * CREATE TABLE statement
     * @var string
     */
    private $sql_create_table = "CREATE TABLE";

    /**
     * FROM statement
     * @var string
     */
    private $sql_from = "FROM";

    /**
     * WHERE statement
     * @var string
     */
    private $sql_where = "WHERE";

    /**
     * DELETE FROM statement
     * @var string
     */
    private $sql_delete = "DELETE FROM";

    /**
     * INSERT INTO statement
     * @var string
     */
    private $sql_insert = "INSERT INTO";

    /**
     * VALUES statement
     * @var string
     */
    private $sql_values = "VALUES";


    /**
     * todo: add support without a model and split up to a class that has a model
     * MS_modelQueryBuilder constructor.
     *
     * @param \MSmvc\system\models\MS_model $model
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
            foreach ($this->model->getFieldCollection() as $field) {
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
     * @param mixed $databaseConnection
     *
     * @return $this
     */
    public function setDatabaseConnection(string $databaseConnection) {
        $this->databaseConnection = $databaseConnection;
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
     * @return mixed
     */
    private function getPrimaryKeysString() {
        return "PRIMARY KEY(" . implode(",", $this->primaryKeys) . ")";
    }

    /**
     * @param MS_property $property
     *
     * @internal param mixed $primaryKeys
     */
    public function setPrimaryKeys(MS_property $property) {
        if ($property->isPrimaryKey() == TRUE) {
            $this->primaryKeys[] = $property->name;
        }
    }

    /**
     * @param        $key
     * @param string $value
     *
     * @return \MSmvc\system\databases\MS_queryBuilder
     */
    public function where($key, $value = '?') {
        return $this->where_filter($key, $value, 'AND ');
    }

    private function addInsertField(string $field) {
        if (!in_array($field, $this->insertFields)) {
            $this->insertFields[] = $field;
        }
    }

    /**
     * @param $data
     *
     * @return $this
     */
    public function insert($data) {
        $this->type = "INSERT INTO";
        if ($data instanceof MS_model) {
            $this->setTable($data);
            $this->model = $data;
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
     * @return \MSmvc\system\databases\MS_queryBuilder
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
     * this function will set all the properties for a single field
     *
     * @param \MSmvc\system\models\properties\MS_property $property
     *
     * @return string
     */
    private function propertyToField(MS_property $property) {
        return rtrim("$property->name $property->type ($property->length)" . $property->getAutoIncrement() . $property->getNotNull(), " ") . ", ";
    }

    /**
     * this method will build the query based on the set values
     */
    private function buildQuery() {
        switch ($this->type) {
            case 'SELECT':
                $query = "$this->sql_select ";
                foreach ($this->selectFields as $field) {
                    $query .= $field . ',';
                }
                $this->addStatementToQuery(rtrim($query, ',') . " $this->sql_from $this->table");
                break;
            case 'CREATE TABLE':
                $query = "$this->sql_create_table $this->table (";
                foreach ($this->model->getFieldCollection() as $field) {
                    //todo: remove if statement from the loop
                    $query .= $this->propertyToField($field);
                    $this->setPrimaryKeys($field);
                }
                $query .= $this->getPrimaryKeysString();
                $this->addStatementToQuery($query . ")");
                break;
            case 'DELETE':
                $this->addStatementToQuery("$this->sql_delete $this->table");
                break;
            case 'UPDATE':
                break;
            case 'INSERT INTO':
                $this->addStatementToQuery("$this->sql_insert $this->table ");
                $this->addStatementToQuery($this->buildInsertStatement());
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
     *
     */
    private function setTable($table) {
        if ($table instanceof MS_model) {
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
    private function addStatementToQuery(string $statement) {
        $this->query .= $statement;
    }

    /**
     * sql statement for the insert fields and data
     * @return string
     */
    private function buildInsertStatement() {
        if (!is_null($this->insertFields)) {
            $this->addStatementToQuery("(" . implode(",", $this->insertFields) . ") ");
        }
        $query = "$this->sql_values ";
        foreach ($this->insertData as $insertDataCollection) {
            $query.="(";
            foreach ($insertDataCollection as $insertData) {
                $query .= "?,";
                $this->prepareData[] = $insertData;
            }
            $query = rtrim($query, ",") . "),";
        }
        return rtrim($query, ",");
    }
}