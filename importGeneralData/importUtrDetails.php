<?php
include "../db/db.php";

use \Model\Config;
use \Model\Order;
use \Model\TD;

$order = new Order("work");
$td = new TD("local");

echo "Start loading details " . date('Y-m-d H:i:s') . "\r\n";

foreach ($order->getBrands() as $brand) {
    echo $brand['name'] . " ... ";
    $details = $order->getDetails($brand['id']);
    $details_count = count($details);
    $details_pross_count = 0;
    foreach ($details as $detail) {
        $td->addUtrDetail($detail);
        if($details_pross_count%1000 == 0 && $details_pross_count!=0) {
            echo "   ".$details_pross_count." details of " . $details_count . " processed\r\n";
        }
        $details_pross_count++;
    }
    echo "done \r\n";
}

echo "Finish loading details " . date('Y-m-d H:i:s') . "\r\n";