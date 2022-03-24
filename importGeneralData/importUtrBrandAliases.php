<?php
include "../db/db.php";

use \Model\Config;
use \Model\Order;
use \Model\TD;

$order = new Order("work");
$td = new TD("local");

echo "start import utr brand aliases ...";
foreach ($order->getBrandAliases() as $alias) {
    $td->addUtrBrandAlias($alias);
}
echo "done\r\n";