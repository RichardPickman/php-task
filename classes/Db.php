<?php

class DataBase {
    private $db;

    public function __construct() {
        $server = "localhost:3306/";
        $database = "products";
        $username = "root";
        $password = "kurwa123";
        $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password, array(PDO::ATTR_TIMEOUT => 5));
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->db = $conn;
    }

    public function getDb() {
        return $this->db;
    }
}

?>
