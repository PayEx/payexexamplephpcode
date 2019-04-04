<?php

include 'resources/Curl.php';
$request = new Curl();
$settingsdata = include 'resources/settings.php';

// type of flow - initiate-consumer-session

$payloadConsumer = [
"operation" => "initiate-consumer-session",
    "msisdn" => "+4798765432",
    "email" => "olivia.nyhuus@example.com",
    "consumerCountryCode" => "NO",
    "nationalIdentifier" => ['socialSecurityNumber' => '26026708248', 'countryCode' => 'NO'],
];

$responseConsumer = $request->curlRequest(
    $settingsdata['AuthorizationBearer'],
    "POST",
    $settingsdata['baseuri'] . "/psp/consumers",
    json_encode($payloadConsumer)
);

if ($responseConsumer['statusCode'] == 200) {
    $operationsArray = $responseConsumer['response']->{'operations'};
    $rel = 'view-consumer-identification';
    $index = array_search($rel, array_column($operationsArray, 'rel'));

    if ($index == true) {
        $href = $operationsArray[$index]->{'href'};
        include 'templates/checkout.php';
        exit;
    }
}
