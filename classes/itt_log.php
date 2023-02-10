<?php

class ITT_Log
{
    public static function write($msg) {
        $fp = fopen(FT_DIR.'logs/'.date('Ymd').'.txt', 'a');
        fwrite($fp, date('y-m-j h:i:s       ').$msg.PHP_EOL);  
        fclose($fp);  
    }
}