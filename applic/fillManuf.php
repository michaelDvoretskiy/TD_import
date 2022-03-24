<?php
include "../db/db.php";
include "../general/constants.php";
include "../td/refLoader.php";

use \Model\Config;
use \Model\TD;
use \Text\RefLoader;

$td = new TD("local");

echo "get manufacturers ...\r\n";
RefLoader::loadManufData_100($td);
echo "updating descriptions ...\r\n";
$td->updateManufDescription();
echo "done\r\n";