<?php
ini_set('memory_limit', '4096M');

include "../db/db.php";
include "../general/constants.php";
include "../td/refLoader.php";

use \Model\Config;
use \Model\TD;
use \Text\RefLoader;

$td = new TD("local");
echo "models start loading 120 " . date('Y-m-d H:i:s') . "...\r\n";
RefLoader::loadCars_120($td);
echo "models start loading 532 " . date('Y-m-d H:i:s') . "...\r\n";
RefLoader::loadCars_532($td);
echo "updating descriptions...\r\n";
$td->updateCarDescription();

echo "models loading finished " . date('Y-m-d H:i:s') . "\r\n";