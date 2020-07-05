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

        // Method to create an user
        public function create() {
            // Insert query
            $query = "INSERT INTO ".$this->table_name." 
            SET username=:username, email=:email, password=:password";

            // Prepare the query
            $stmt = $this->conn->prepare($query);

            // Sanitize
            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->password = htmlspecialchars(strip_tags($this->password));

            // Bind valuess
            $stmt->bindParam("username", $this->username);
            $stmt->bindParam("email", $this->email);

            // Hash the password before saving it to the database
            $password_hashed = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam("password", $password_hashed);

            // Execute query & check if query was succesfull
            if ($stmt->execute()) {
                return true;
            }
            return false;
        }

        // Method to check if the username or email is taken
        function isTaken() {
            // Select username and email query
            $query = "SELECT username, email FROM " .$this->table_name. " 
            WHERE username=:username OR email=:email";

            // Prepare the query
            $stmt = $this->conn->prepare($query);

            // Sanitize
            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->email = htmlspecialchars(strip_tags($this->email));

            // Bind valuess
            $stmt->bindParam("username", $this->username);
            $stmt->bindParam("email", $this->email);

            // Execute query
            $stmt->execute();

            // Check if username or email is taken
            if ($stmt->rowCount() > 0) {
                return true;
            }
            return false;
        }
    }