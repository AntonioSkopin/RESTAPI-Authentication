<?php

    // Required headers
    header("Access-Control-Allow-Origin: http://localhost/RESTAPI_Authentication/");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // required to decode jwt
    include_once 'config/core.php';
    include_once 'libs/php-jwt-master/src/BeforeValidException.php';
    include_once 'libs/php-jwt-master/src/ExpiredException.php';
    include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
    include_once 'libs/php-jwt-master/src/JWT.php';
    use \Firebase\JWT\JWT;

    // Get posted data
    $data = json_decode(file_get_contents("php://input"));

    // Get jwt
    $jwt = isset($data->jwt) ? $data->jwt : "";

    // If JWT is not empty
    if ($jwt) {
        // If decode succeed, show user details
        try {
            // Decode jwt
            $decoded = JWT::decode($jwt, $key, array('HS256'));

            // Set response code to 200 (OK)
            http_response_code(200);

            // Show user details
            echo json_encode(array(
                "message" => "Access granted.",
                "data" => $decoded->data
            ));
        }
        // If decode fails, it means jwt is invalid
        catch (Exception $e) {
            // Set response code to 401 (Unauthorized)
            http_response_code(401);

            // Tell the user that the access is denied & show error message
            echo json_encode(array(
                "message" => "Access denied.",
                "error" => $e->getMessage()
            ));
        }
    }
    // Show error if JWT is empty
    else {
        // Set response code to 401 (Unauthorized)
        http_response_code(401);

        // Tell the user that the access denied
        echo json_encode(array("message" => "Access denied."));
    }