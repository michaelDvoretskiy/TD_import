<?php
ini_set('memory_limit', '4096M');

include "../db/db.php";
include "../general/constants.php";
include "../td/refLoader.php";

use \Model\Config;
use \Model\TD;
use \Text\RefLoader;

$td = new TD("local");
echo "engines start loading " . date('Y-m-d H:i:s') . "...\r\n";
// RefLoader::loadEngines_155($td);
RefLoader::loadEngineCars_125($td);
RefLoader::loadEngineCars_537($td);

echo "engines loading finished " . date('Y-m-d H:i:s') . "\r\n";