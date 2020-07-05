<?php

    // Required header
    header("Access-Control-Allow-Origin: http://localhost/RESTAPI_Authentication/");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // Files needed for connection to the database
    include_once "config/database.php";
    include_once "objects/user.php";

    // Get database connection
    $database = new Database();
    $db = $database->getConnection();

    // Instantiate user object
    $user = new User($db);

    // Get posted data
    $data = json_decode(file_get_contents("php://input"));

    // Set user property values
    $user->username = $data->username;
    $user->email = $data->email;
    $user->password = $data->password;