<?php
class DBConn
{

    private $DBHOST = 'localhost:3306';
    private $DBUSER = 'root';
    private $DBPASS = '';
    private $DBNAME = 'smarthome';
    public $conn;

    public function __construct()
    {
        try {
            $this->conn = mysqli_connect($this->DBHOST, $this->DBUSER, $this->DBPASS, $this->DBNAME);
            if (!$this->conn) {
                throw new Exception('Connection failed to establish');
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }

    public function validate($string)
    {
        $string_vali = mysqli_real_escape_string($this->conn, $string);
        return $string_vali;
    }

    public function insert($tableName, $field) #currently unused, repurpose as a template later
    {

        $command = "";

        foreach ($field as $key => $val) {
            $command = $command . "$key='$val',";
        }
        $command = rtrim($command, ",");

        $query = "INSERT INTO $tableName SET $command";
        $insert_fire = mysqli_query($this->conn, $query);
        if ($insert_fire) {
            return $insert_fire;
        } else {
            return false;
        }
    }

    public function selectNRows($tableName, $field, $numGet, $order = "DESC")
    {

        $select = "SELECT * FROM $tableName ORDER BY $field $order LIMIT $numGet";
        $query = mysqli_query($this->conn, $select);
        if (mysqli_num_rows($query) > 0) {
            $select_fetch = mysqli_fetch_all($query, MYSQLI_ASSOC);
            if ($select_fetch) {
                return $select_fetch;
            } else return false;
        } else return false;
    }
}
