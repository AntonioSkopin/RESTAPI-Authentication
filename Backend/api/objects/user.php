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

        // Method to update the user info
        public function update() {
            // If password needs to be updated
            $password_set = !empty($this->password) ? ", password = :password" : "";

            // If no posted password, do not update the password
            $query = "UPDATE ".$this->table_name. " 
            SET username =:username, email =:email, {$password_set} WHERE id =:id";

            // Prepare the query
            $stmt = $this->conn->prepare($query);

            // Sanitize
            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->email = htmlspecialchars(strip_tags($this->email));

            // Bind values from the form
            $stmt->bindParam("username", $this->username);
            $stmt->bindParam("email", $this->email);

            // Hash the password before savind to database
            if (!empty($this->password)) {
                $this->password = htmlspecialchars(strip_tags($this->password));
                $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
                $stmt->bindParam("password", $password_hash);
            }

            // Unique ID of record to be edited
            $stmt->bindParam("id", $this->id);

            // Execute the query
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

        // Method to check if the username exists
        public function usernameExists() {
            // Query to check if the username exists
            $query = "SELECT id, email, password FROM ".$this->table_name." 
            WHERE username = ? LIMIT 0,1";

            // Prepare the query
            $stmt = $this->conn->prepare($query);

            // Sanitize
            $this->username = htmlspecialchars(strip_tags($this->username));

            // Bind given username value
            $stmt->bindParam(1, $this->username);

            // Execute the query
            $stmt->execute();

            // Get number of rows
            $num = $stmt->rowCount();

            // If username exists, assign values to object properties for easy access and use for php sessions
            if ($num > 0) {
                // Get record values
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // Assign values to object properties
                $this->id = $row["id"];
                $this->email = $row["email"];
                $this->password = $row["password"];

                // return true because username exists in the database
                return true;
            }
            // Return false if username doesn't exist in the database
            return false;
        }
    }