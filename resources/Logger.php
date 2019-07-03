<?php

namespace resources;

class Logger
{
    /**
    * Logging method.
    *
    * @param string $folder folder to log to.
    * @param string $file name of log.
    * @param string $logdata content to log.
    * @param string $type LogLevel type.
    *
    * @return void
    */
    public function createLog($file, $logdata, $type)
    {
        $folder = __DIR__.'/../logs';
        $filepath = $folder.'/'.$file.'_' . date("Ymd") . '.txt';
        
        if (is_dir($folder) == false) {
            mkdir($folder, 0777);
        }

        if (is_file($filepath) == false) {
            $newlog = date("Y-m-d h:i:s") . PHP_EOL;
        } else {
            $newlog = PHP_EOL . date("Y-m-d h:i:s") . PHP_EOL;
        }
        
        $newlog .= "LogLevel: " . $type . PHP_EOL;
        $newlog .= $logdata;
        $newlog .= "-------------------------";

        try {
            file_put_contents($filepath, $newlog, FILE_APPEND);
        } catch (Exception $e) {
            //echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}
