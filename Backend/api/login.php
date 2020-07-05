<?php

    // Required headers
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

    // Set product property values
    $user->username = $data->username;
    $username_exists = $user->usernameExists();

    // generate json web token
    include_once 'config/core.php';
    include_once 'libs/php-jwt-master/src/BeforeValidException.php';
    include_once 'libs/php-jwt-master/src/ExpiredException.php';
    include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
    include_once 'libs/php-jwt-master/src/JWT.php';
    use \Firebase\JWT\JWT;

    // Check if username exists and if password is correct
    if ($username_exists && password_verify($data->password, $user->password)) {
        $token = array(
            "iss" => $iss,
            "aud" => $aud,
            "iat" => $iat,
            "nbf" => $nbf,
            "data" => array(
                "id" => $user->id,
                "username" => $user->username,
                "email" => $user->email
            )
        );

        // Set response code to 200 (OK)
        http_response_code(200);

        // Generate JWT
        $jwt = JWT::encode($token, $key);

        // Tell user login was succesful
        echo json_encode(array(
            "message" => "Succesful login.",
            "jwt" => $jwt
        ));
    }
    // Login failed
    else {
        // Set response code to 401 (Unauthorized client)
        http_response_code(401);

        // Tell the user login failed
        echo json_encode(array("message" => "Login failed."));
    }