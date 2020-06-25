<?php

class DataBaseConnection {

    private $db_connection;

    function __construct() {
        $this->db_connection = new mysqli("remotemysql.com", "2cMB8HiJvS", "rcYF70B1fj", "2cMB8HiJvS");
        if ($this->db_connection->connect_errno) {
            echo "Failed to connect to MySQL: " . $mysqli->connect_error;
            exit();
        }
    }

    function getConnection() {
        return $this->db_connection;
    }

}
