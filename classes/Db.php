<?php

class DataBase {
    private $db;

    public function __construct() {
        $server = $_ENV['SERVER'];
        $database = $_ENV['DATABASE'];
        $username = $_ENV['USERNAME'];
        $password = $_ENV['PASSWORD'];
        $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password, array(PDO::ATTR_TIMEOUT => 5));
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->db = $conn;
    }

    public function getDb() {
        return $this->db;
    }
}

?>
