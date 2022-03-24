<?php

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

// $manList = $td->getManufList();
// foreach ($manList as $man) {
//     $res = $order->addManuf($man);
// }

// $modelList = $td->getModelList();
// foreach ($modelList as $model) {
//     $order->addModel($model);
// }

// $carList = $td->getCarList();
// echo "list is ready. Total count " . count($carList) . "\r\n";
// $index = 0;
// foreach ($carList as $car) {
//     $order->addCar($car);
//     $index++;
//     if ($index % 1000 == 0) {
//         echo $index . " processed\r\n";
//     }
// }

// $engineList = $td->getEngineList();
// echo "list is ready. Total count " . count($engineList) . "\r\n";
// $index = 0;
// foreach ($engineList as $engine) {
//     $order->addEngine($engine);
//     $index++;
//     if ($index % 1000 == 0) {
//         echo $index . " processed\r\n";
//     }
// }

// $engineCarList = $td->getEngineCarList();
// echo "list is ready. Total count " . count($engineCarList) . "\r\n";
// $index = 0;
// foreach ($engineCarList as $engineCar) {
//     $order->addEngineCar($engineCar);
//     $index++;
//     if ($index % 1000 == 0) {
//         echo $index . " processed\r\n";
//     }
// }

echo date('Y-m-d H:i:s') . "\r\n";