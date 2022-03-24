<?php
ini_set('memory_limit', '4096M');

include "../db/db.php";
include "../general/constants.php";
include "../td/refLoader.php";
include "../td/brandLoader.php";

use \Model\Config;
use \Model\TD;
use \Model\Order;
use \Text\RefLoader;
use \Text\BrandLoader;

$td = new TD("local");
$order = new Order("work");
echo "applicability start loading 400 " . date('Y-m-d H:i:s') . "...\r\n";

// $utrBrands = $order->getBrands();
$utrBrands = $order->getBrands("where name > 'HELLA'");
$table_index = "_2";

foreach ($utrBrands as $utrBrand) {
    $tdBrands = $td->getBrandsByUtrId($utrBrand['id']);
    if (count($tdBrands)>0) {
        echo "Urt brand " . $utrBrand['name'] . " ... \r\n";
        foreach($tdBrands as $tdBrand) {
            echo "TD brand " . $tdBrand['id'] . " " . $tdBrand['name'] . " ... \r\n";
            $brandLoader = new BrandLoader($tdBrand['id']);
            $brandLoader->loadApplicData_400($td, $table_index);
        } 
        echo "done \r\n";
    }
}

echo "applicability loading finished " . date('Y-m-d H:i:s') . "\r\n";