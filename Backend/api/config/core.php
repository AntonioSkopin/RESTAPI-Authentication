<?php
    // Show error reporting
    error_reporting(E_ALL);

    // Set your default timezone
    date_default_timezone_set('Europe/Amsterdam');

    // Variables used for jwt
    $key = "example_key";
    $iss = "http://example.org";
    $aud = "http://example.com";
    $iat = 1356999524;
    $nbf = 1357000000;