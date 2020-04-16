<?php

require_once 'resources/Curl.php';
use \resources\Curl;

$request = new Curl();
$settingsData = require_once 'resources/settings.php';

// type of flow - initiate-consumer-session

$payloadConsumer = [
"operation" => "initiate-consumer-session",
    "msisdn" => "+4798765432",
    "email" => "olivia.nyhuus@payex.com",
    "consumerCountryCode" => "NO",
    "nationalIdentifier" => ['socialSecurityNumber' => '26026708248', 'countryCode' => 'NO'],
];

$responseConsumer = $request->curlRequest(
    $settingsData['AuthorizationBearer'],
    "POST",
    $settingsData['baseuri'] . "/psp/consumers",
    json_encode($payloadConsumer)
);

if ($responseConsumer['statusCode'] == 200) {
    $operationsArray = $responseConsumer['response']->{'operations'};
    $rel = 'view-consumer-identification';
    $index = array_search($rel, array_column($operationsArray, 'rel'));

    if (isset($index)) {
        $href = $operationsArray[$index]->{'href'};
        include 'templates/checkout.php';
        exit();
    }
}
