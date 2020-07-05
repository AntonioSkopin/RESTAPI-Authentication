<?php

    class Database {
        // Properties:
        private $host = "localhost";
        private $db_name = "cogito_beta";
        private $username = "root";
        private $password = "";
        public $conn;

        // Method to get the database connection
        public function getConnection() {
            $this->conn = null;

            try {
                $this->conn = 
                new PDO("mysql:host=".$this->host.";dbname=".$this->db_name, $this->username, $this->password);
            } catch (PDOException $exception) {
                echo "Connection error: " . $exception->getMessage();
            }

            return $this->conn;
        }
    }