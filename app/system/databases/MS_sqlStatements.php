<?php
/**
 * Created by PhpStorm.
 * User: Maurice
 * Date: 12-Jan-17
 * Time: 23:34
 */

namespace App\system\databases;


/**
 * Class MS_sqlStatements
 * @package App\system\databases
 */
trait MS_sqlStatements {
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
     * UPDATE statement
     * @var string
     */
    private $sql_update = "UPDATE";

    /**
     * VALUES statement
     * @var string
     */
    private $sql_values = "VALUES";

    /**
     * SET statement
     * @var string
     */
    private $sql_set = "SET";

    /**
     * SHOW statement;
     * @var string
     */
    private $sql_show = "SHOW";

    /**
     * SHOW statement;
     * @var string
     */
    private $sql_full = "FULL";
}