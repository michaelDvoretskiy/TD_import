<?php
include "../db/db.php";
include "../general/constants.php";
include "../td/refLoader.php";
include "../td/brandLoader.php";

use \Model\Config;
use \Model\TD;
use \Model\Order;
use \Text\RefLoader;
use \Text\BrandLoader;

$order = new Order("work");
$td = new TD("local");

echo date('Y-m-d H:i:s') . "\r\n";

// $tdCharacts = $td->getCharactAttrList(); $i = 0;
// echo "readed " . count($tdCharacts) . " attributes ... \r\n";
// foreach ($tdCharacts as $charact) {
//     $order->addCharactAttr($charact);
//     $i++;
//     if($i%1000 == 0 && $i!=0) {
//         echo "   attributes - " . $i . " rows affected\r\n";
//     }
// }

$orderBrands = $order->getBrands("where id <> 130"); 
foreach ($orderBrands as $brand) {
	$added_count = 0; 
	$brand_id = $brand['id'];
	echo "processing ".$brand['name']." ... ";
    $tdCharacts = $td->getCharatsByBrand2($brand_id);
    echo "query finished ... ";

    if (count($tdCharacts)>0) {
        //clear old characts from order
        $order->clearCharactsByBrand($brand_id);

        foreach($tdCharacts as $tdChar) {
            $order->addCharactVal($tdChar['did'], $tdChar);
            $added_count++;
            if($added_count%10000 == 0 && $added_count!=0) {
                echo "   charact - " . $added_count . " rows affected\r\n";
            }
        }
    }    
	echo " added ".$added_count." characts<br>\r\n";		
}

echo date('Y-m-d H:i:s') . "\r\n";