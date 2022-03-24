<?php
ini_set('memory_limit', '4096M');

include "../db/db.php";
include "../general/constants.php";
// include "../td/refLoader.php";
// include "../td/brandLoader.php";

use \Model\Config;
use \Model\TD;
use \Model\Order;
// use \Text\RefLoader;
// use \Text\BrandLoader;

$td = new TD("local");
$order = new Order("work");

echo date('Y-m-d H:i:s') . "\r\n";

foreach ($order->getBrands("where id<>55855 and name > 'BOSAL'") as $brand) {
	$added_count = 0; 
	$brand_id = $brand['id'];
	echo date('Y-m-d H:i:s') . " processing ".$brand['name']." (".$brand_id.") ... ";
    $tdApplics = $td->getApplicability($brand_id);
    echo "found " . count($tdApplics) . " applicabilities ... ";

    if (count($tdApplics)>0) {
        $workDBapplicCount = $order->getApplicCountByBrand($brand_id);
        echo "exists " . $workDBapplicCount;
        //clear old characts from order
        $order->clearApplicByBrand($brand_id);
        echo " cleared ... ";
        foreach($tdApplics as $applic) {
            $order->addApplic($applic);
            $added_count++;
        }
    } 
	echo " added ".$added_count."\r\n";		
}

echo date('Y-m-d H:i:s') . "\r\n";