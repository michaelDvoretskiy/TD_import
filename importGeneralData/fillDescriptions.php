<?php
ini_set('memory_limit', '4096M');

include "../db/db.php";
include "../general/constants.php";
include "../td/refLoader.php";

use \Model\Config;
use \Model\TD;
use \Text\RefLoader;

$td = new TD("local");

// echo "get descriptions 012 ...";
// RefLoader::loadDescriptions_012($td);
echo "get descriptions 030 ...";
RefLoader::loadDescriptions_030($td);
echo "done\r\n";