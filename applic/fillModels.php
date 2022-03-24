<?php
ini_set('memory_limit', '4096M');

include "../db/db.php";
include "../general/constants.php";
include "../td/refLoader.php";

use \Model\Config;
use \Model\TD;
use \Text\RefLoader;

$td = new TD("local");
echo "models start loading " . date('Y-m-d H:i:s') . "...\r\n";
// RefLoader::loadModels_110($td);
$td->updateModelDescription();
echo "updating descriptions...\r\n";

echo "models loading finished " . date('Y-m-d H:i:s') . "\r\n";