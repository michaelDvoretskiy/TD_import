<?php
include "../db/db.php";

use \Model\Config;
use \Model\Order;
use \Model\TD;

$order = new Order("work");
$td = new TD("local");

echo "start import utr brands ...";
foreach ($order->getBrands() as $brand) {
    $td->addUtrBrand($brand);
}
echo "done\r\n";