<?php
/**
 * Interface for Connection class
 */
interface ConnectionInterface
{
    public function select($columns, $table, $where = null);
    public function insert($columns, $table, $values);
    public function update($table, $values, $where);
    public function delete($table, $where);
}
/**
 * Class PHP for SQL execution.
 */
class Connection implements ConnectionInterface
{

    private $mysqli;

    /**
     * @param string $server DATABASE hostname
     * @param string $username DATABASE username 
     * @param string|null $password DATABASE pasword 
     * @param string $database DATABASE name 
     * @param string $charset DATABASE charset  
     */

    public function __construct($host, $username, $password, $database, $charset = "utf8")
    {


        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->charset = $charset;

        $this->mysqli = new mysqli($this->host, $this->username, $this->password, $this->database);

        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

        $this->mysqli->set_charset($this->charset);
    }

    /**
     * Execute the SQL in DB.
     * @param string $sql The SQL code to be executed.
     * @return boolean Return the result from SQL execution.
     */
    private function execute($sql)
    {
        // echo $sql;
        $ret = $this->mysqli->query($sql);
        if (!$ret) {
            $ret = $this->mysqli->error . " Error in: $sql";
        }
        return $ret;
        $this->mysqli->close();
    }

    /**
     * Execute SELECT in DB.
     * @param string $columns The columns what the SELECT will return.
     * @param string $table The tables where the SELECT will be executed.
     * @param string $where Where clause for SELECT.
     * @return boolean True if the SELECT was executed, else, false.
     */
    public function select($columns, $table, $where = null)
    {
        $select = "SELECT $columns FROM $table";

        if (!is_null($where) && $where !== "") {
            $select .= " WHERE $where";
        }

        $result = $this->execute($select . ";");

        return $result;
    }

    /**
     * Execute INSERT in DB.
     * @param string $columns Columns that will receive the values.
     * @param string $table Table where the INSERT will be executed.
     * @param string $values Values to be inserted.
     * @return boolean True if the INSERT was executed, else, false.
     */
    public function insert($columns, $table, $values)
    {
        $insert = "INSERT INTO $table($columns) VALUES ($values);";

        $result = $this->execute($insert);

        return $result;
    }

    /**
     * Execute UPDATE in DB.
     * @param string $table Table where the UPDATE will be executed.
     * @param string $values Values to be updated.
     * @param string $where Where clause for UPDATE.
     * @return boolean True if the UPDATE was executed, else, false.
     */
    public function update($table, $values, $where)
    {
        $update = "UPDATE $table SET $values WHERE $where;";

        $result = $this->execute($update);

        return $result;
    }

    /**
     * Execute DELETE in DB.
     * @param string $table Table where the DELETE will be executed.
     * @param string $where Where clause for UPDATE.
     * @return boolean True if the DELETE was executed, else, false.
     */
    public function delete($table, $where)
    {
        if ($where !== "" && !is_null($where)) {
            $delete = "DELETE FROM $table WHERE $where";

            $result = $this->execute($delete);

            return $result;
        } else {
            die("WHERE is REQUIRED in DELETE, for safety.");
        }
    }
}
