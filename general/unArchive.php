<?php
include_once('constants.php');
$path = Constants::$input_files_root_path . Constants::$input_data_suff;
echo $path;
$arh_path = Constants::$raw_files_root_path . Constants::$raw_data_suff;
$d=dir($path);
//initialise the output array
$ret=Array();
//loop, reading until there's no more to read
while (false!==($e=$d->read())) {
    if (($e==".")||($e=="..")) continue;
    echo "archiving " . $e . " ... ";

    $command = "7z a -tzip ".$arh_path . $e . ".zip " . $path . $e;          
    exec($command);
    echo " done\r\n";
}
echo "finished\r\n";
