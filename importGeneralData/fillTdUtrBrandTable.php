<?php
include "../db/db.php";

use \Model\Config;
use \Model\TD;

$td = new TD("local");

echo "fill with name equals ...";
$td->fillTdUtrBrandsEquals();
$td->fillTdUtrBrandsByAliases();
echo "done\r\n";