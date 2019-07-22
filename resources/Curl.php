<?php

namespace resources;

require_once 'Logger.php';
use \resources\Logger;

class Curl
{
    /**
     * Curl Request.
     *
     * @param string $authorizationBearer
     * @param string $httpMethod
     * @param string $uri
     * @param string $payload
     *
     * @return array
     * response['response'] = decoded JSON response data.
     * response['statusCode'] = HTTP Code.
     */
    public function curlRequest($authorizationBearer, $httpMethod, $uri, $payload)
    {
        $logger = new Logger();
        $settingsData = include 'settings.php';

        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $uri);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($curl, CURLOPT_SSLVERSION, 6); // TLS 1,2
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $httpMethod);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer " . $authorizationBearer,
                "Content-Type: application/json; charset=utf-8",
                "Accept: application/json",
            ));

            $response = curl_exec($curl);
            $httpResponseCode = (curl_getinfo($curl, CURLINFO_HTTP_CODE));
            $jsonResponse = json_decode($response);
            $errno = curl_errno($curl);
            $curlMessage = curl_error($curl);
            curl_close($curl);
        } catch (Exception $e) {
            if ($settingsData['logging'] == true) {
                $log = "";
                $log .= "Caught exception: " . $e->getMessage() . PHP_EOL;
                $logger->createLog('API', $log, 'ERROR');
            }
        }

        if ($errno == 0) {
            if ($settingsData['logging'] == true) {
                $message = '';
                $log = "";
                $log .= "Request method: " . $httpMethod . PHP_EOL;
                $log .= "Request URI: " . $uri . PHP_EOL;
                $log .= "Request body: " . $payload . PHP_EOL;
                $log .= "Response body: " . $response . PHP_EOL;
                if ($httpResponseCode == '200' || $httpResponseCode == '201') {
                    $message = 'OK HTTP Request';
                } elseif ($httpResponseCode == '400') {
                    $message = 'Bad Request';
                } elseif ($httpResponseCode == '401') {
                    $message = 'OK HTTP Request, client Unauthorized';
                } elseif ($httpResponseCode == '403') {
                    $message = 'OK HTTP Request, client request forbidden';
                } elseif ($httpResponseCode == '404') {
                    $message = 'OK HTTP Request, but the resource you are looking for might have been removed, had its name changed, or is temporarily unavailable at URI ' . $uri;
                }

                $log .= "HTTP Code: " . $httpResponseCode . PHP_EOL;
                $log .= 'Message: ' . $message . PHP_EOL;
                $logger->createLog('API', $log, 'INFO');
            }
            return array(
                'response' => $jsonResponse,
                'statusCode' => $httpResponseCode,
            );
        } else {
            if ($settingsData['logging'] == true) {
                $log = "";
                $log .= "Curl error number: " . $errno . PHP_EOL;
                $log .= "Curl message: " . $curlMessage . PHP_EOL;
                $logger->createLog('API', $log, 'ERROR');
            }
        }
    }
}
