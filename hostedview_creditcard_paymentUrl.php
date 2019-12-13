<?php

require_once 'resources/Curl.php';
use \resources\Curl;

$request = new Curl();
$settingsData = require_once 'resources/settings.php';

$cookie_name = 'paymentid_creditcard';

if (!isset($_COOKIE[$cookie_name])) {
    echo "Cookie named '" . $cookie_name . "' is not set!";
} else {
    try {
        $response = $request->curlRequest(
            $settingsData['AuthorizationBearer'],
            "GET",
            $settingsData['baseuri'] . $_COOKIE[$cookie_name], //payment
        '' // payload content not needed, but empty string must be present because of the method parameter
        );
    
        if ($response['statusCode'] == 200) {
            $operationsArray = $response['response']->{'operations'};
            $index = array_search('view-payment', array_column($operationsArray, 'rel'));
            
            if (isset($index)) {
                $href = $operationsArray[$index]->{'href'};
                include 'templates/creditcard.php';
                exit();
            }
        }
    } catch (Exception $e) {
        // Exception handling
    }
}
