<?php
ini_set('memory_limit', '4096M');

include "../db/db.php";
include "../general/constants.php";
include "../td/refLoader.php";

use \Model\Config;
use \Model\TD;
use \Text\RefLoader;

$td = new TD("local");

// echo "get criterias 050 ...";
// RefLoader::loadCriterias_050($td);
echo "get key entries 052 ...";
RefLoader::loadKeyEntries_052($td);
echo "done\r\n";