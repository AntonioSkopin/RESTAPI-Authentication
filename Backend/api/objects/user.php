<?php
    
    class User {
        private $conn;
        private $table_name = "users";

        // Object properties
        public $id;
        public $username;
        public $email;
        public $password;
        public $created;
        public $modified;

        // Constructor method $db is database connection
        public function __construct($db) {
            $this->conn = $db;
        }
    }