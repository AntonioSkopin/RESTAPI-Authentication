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

    // Create user
    if (
        !empty($user->username) &&
        !empty($user->email) &&
        !empty($user->password) &&
        $user->create()
    ) {
        // Set response code to 200 (OK)
        http_response_code(200);

        // Display message: User is created
        echo json_encode(array("message" => "User is created."));
    }
    // Message if unable to create the user
    else {
        // Set response code to 400 (Bad request)
        http_response_code(400);

        // Display message: Unable to create the user
        echo json_encode(array("message" => "Unable to create the user."));
    }