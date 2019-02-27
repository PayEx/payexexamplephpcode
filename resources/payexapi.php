<?php

class payexapi
{
    public function logger($log, $type)
    {
        $newlog = PHP_EOL . date("Y-m-d h:i:s") . PHP_EOL;
        $newlog .= "LogLevel:" . $type . PHP_EOL;
        $newlog .= $log;
        $newlog .= "-------------------------";
        $folder = 'logs';
        if (!is_dir($folder)) {
            mkdir($folder, 0777);
        }
        try
        {
            file_put_contents($folder . '/log_' . date("Ymd") . '.txt', $newlog, FILE_APPEND);
        } catch (Exception $e) {
            //echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    public function payex_request($AuthorizationBearer, $httpmethod, $uri, $payload)
    {
        $payexapi = new payexapi();
        $settingsdata = include 'settings.php';

        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $uri);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $httpmethod);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer " . $AuthorizationBearer,
                "Content-Type: application/json; charset=utf-8",
                "Accept: application/json",
            ));

            $response = curl_exec($curl);
            $httpresponsecode = (curl_getinfo($curl, CURLINFO_HTTP_CODE));
            $jsonresponse = json_decode($response);
            $error = curl_error($curl);
            curl_close($curl);

            if ($error) {
                if ($settingsdata['logging'] == true) {
                    $log = "";
                    $log .= "CURL Error: " . $error . PHP_EOL;
                    $payexapi->logger($log, 'ERROR');
                }
            } else {
                if ($settingsdata['logging'] == true) {
                    $message = '';
                    $log = "";
                    $log .= "Request method: " . $httpmethod . PHP_EOL;
                    $log .= "Request URI: " . $uri . PHP_EOL;
                    $log .= "Request: " . $payload . PHP_EOL;
                    $log .= "Response: " . $response . PHP_EOL;
                    if ($httpresponsecode == '200' || $httpresponsecode == '201') {
                        $message = 'OK HTTP Request';
                    } elseif ($httpresponsecode == '400') {
                        $message = 'Bad Request';
                    } elseif ($httpresponsecode == '401') {
                        $message = 'OK HTTP Request, client Unauthorized';
                    } elseif ($httpresponsecode == '403') {
                        $message = 'OK HTTP Request, client request forbidden';
                    } elseif ($httpresponsecode == '404') {
                        $message = 'OK HTTP Request, but the resource you are looking for might have been removed, had its name changed, or is temporarily unavailable at URI ' . $uri;
                    }

                    $log .= "HTTP Code: " . $httpresponsecode . PHP_EOL;
                    $log .= 'Message: ' . $message . PHP_EOL;
                    $payexapi->logger($log, 'INFO');
                }
                return array
                    (
                    'response' => $jsonresponse,
                    'statuscode'=> $httpresponsecode
                );
                // response[0] = json response
                // response[1] = http code
            }
        } catch (Exception $e) {
            if ($settingsdata['logging'] == true) {
                $log = "";
                $log .= "Caught exception: " . $e->getMessage() . PHP_EOL;
                $payexapi->logger($log, 'ERROR');
            }
        }
    }
}
