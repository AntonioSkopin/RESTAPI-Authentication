<?php

    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // required to encode json web token
    include_once 'config/core.php';
    include_once 'libs/php-jwt-master/src/BeforeValidException.php';
    include_once 'libs/php-jwt-master/src/ExpiredException.php';
    include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
    include_once 'libs/php-jwt-master/src/JWT.php';
    use \Firebase\JWT\JWT;

    // files needed to connect to database
    include_once 'config/database.php';
    include_once 'objects/user.php';
    
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
    
    // instantiate user object
    $user = new User($db);

    // Get posted data
    $data = json_decode(file_get_contents("php://input"));

    // Get jwt
    $jwt = isset($data->jwt) ? $data->jwt : "";

    // Check if JWT is not empty
    if ($jwt) {
        // If decode succeed, show user details
        try {
            // Decode jwt
            $decoded = JWT::decode($jwt, $key, array('HS256'));

            // Set user property values
            $user->username = $data->username;
            $user->email = $data->email;
            $user->password = $data->password;
            $user->id = $decoded->data->id;

            // Update the user record
            if ($user->update()) {
                // Regenerate jwt will be here
            }
            // Message if unable to update user
            else {
                // Set response code to 401 (Unauthorized)
                http_response_code(401);

                // Show error message
                echo json_encode(array("message" => "Unable to update user."));
            }
        }
        // If decode fails, it means jwt is invalid
        catch (Exception $e) {
            // Set response code to 401 (Unauthorized)
            http_response_code(401);

            // Show error message
            echo json_encode(array(
                "message" => "Access denied.",
                "error" => $e->getMessage()
            ));
        }
    }
    // Error message if jwt is empty will be here