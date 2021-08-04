<?php

//$new_file_name = "CcalcDealerUnlimNovikon.exe";
//$url = "http://files.adgroup.com.ua:4482/download/gIbg4Gj9bR";
//$uploaddir = 'files';

// if(!is_dir($uploaddir)) {
//     mkdir($uploaddir, 0777, true);
// }

function collect_file($message){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $message);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, false);
    curl_setopt($ch, CURLOPT_REFERER, "http://www.xcontest.org");
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $result = curl_exec($ch);
    curl_close($ch);
    return($result);
}

function write_to_file($text,$new_filename){
    $fp = fopen($new_filename, 'w');
    fwrite($fp, $text);
    fclose($fp);
}

$temp_file_contents = collect_file($message);
write_to_file($temp_file_contents, "files/CcalcDealerUnlimNovikon.exe");