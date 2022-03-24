<?php

namespace Text;

class RefLoader {
    public static function loadManufData_100($td) {
        $file_name = \Constants::$input_files_root_path . "ref\\100.dat";
        
        $array = explode("\n", file_get_contents($file_name));
        $i = 0;
        foreach($array as $row) {
            $manuf['id'] = \substr($row, 7, 6);
            $manuf['pc'] = \substr($row, 32, 1);
            $manuf['cv'] = \substr($row, 33, 1);
            $manuf['lcv'] = \substr($row, 38, 1);  
            $manuf['descr_numb'] = \substr($row, 23, 9);  
            $manuf['short_name'] = \substr($row, 13, 10);                                    
            $td->addManufData_100($manuf);
        }
    }

    public static function loadDescriptions_012($td) {
        $file_name = \Constants::$input_files_root_path . "ref\\012.dat";
        
        $array = explode("\n", file_get_contents($file_name));
        $i = 0;
        echo "start loading ... \r\n";
        foreach($array as $row) {
            $i++;
            if($i%10000 == 0 && $i!=0) {
                echo "   descriptions - " . $i . " rows affected\r\n";
            }
            // if ($i<1649900) continue;
            $descr['descr'] = trim(\mb_substr($row, 44, 60));
            $descr['number'] = trim(\substr($row, 29, 9));
            $descr['country'] = trim(\substr($row, 38, 3));
            $descr['lang'] = trim(\substr($row, 41, 3));                        
            $td->addDescription_012($descr);  
        }
    }

    public static function loadDescriptions_030($td) {  
        $file_name = \Constants::$input_files_root_path . "ref\\030.dat";

        $array = explode("\n", file_get_contents($file_name));
        $i = 0;
        echo "start loading ... \r\n";
        foreach($array as $row) {
            $i++;
            if($i%10000 == 0 && $i!=0) {
                echo "   descriptions-030 - " . $i . " rows affected\r\n";
            }
            $descr['descr'] = trim(\mb_substr($row, 41, 60));
            $descr['number'] = trim(\substr($row, 29, 9));
            $descr['lang'] = trim(\substr($row, 38, 3));   
            $td->addDescription030($descr);  
        }
    }

    public static function loadCriterias_050($td) {
        $file_name = \Constants::$input_files_root_path . "ref\\050.dat";
        
        $array = explode("\n", file_get_contents($file_name));
        $i = 0;
        echo "start loading creterias-050 ... \r\n";
        foreach($array as $row) {
            $i++;
            if($i%10000 == 0 && $i!=0) {
                echo "   creterias-030 - " . $i . " rows affected\r\n";
            }
            $crit['descr_numb'] = trim(\substr($row, 11, 9));
            $crit['number'] = trim(\substr($row, 7, 4));
            $crit['typ'] = trim(\substr($row, 20, 1));
            if ($crit['typ']=='K') {
                $crit['tabnr'] = \substr($row, 24, 3);
            } else {
                $crit['tabnr'] = 0;
            }
            
            $td->addCreteria050($crit);  
        }
    }

    public static function loadKeyEntries_052($td) {
        $file_name = \Constants::$input_files_root_path . "ref\\052.dat";
        
        $array = explode("\n", file_get_contents($file_name));
        $i = 0;
        echo "start loading key-entries-052 ... \r\n";
        foreach($array as $row) {
            $i++;
            if($i%10000 == 0 && $i!=0) {
                echo "   key-entries-052 - " . $i . " rows affected\r\n";
            }
            $ent['tabnr'] = \substr($row, 29, 3);
            $ent['keynr'] = trim(\substr($row, 32, 3));
            $ent['descr_numb'] = trim(\substr($row, 35, 9));            
            $td->addKeyEntry052($ent);  
        }
    }

    public function loadModels_110($td) {
        $file_name = \Constants::$input_files_root_path . "ref\\110.dat";
        
        $array = explode("\n", file_get_contents($file_name));
        $i = 0;
        echo "start loading car-models-110 ... \r\n";
        foreach($array as $row) {
            $i++;
            if($i%10000 == 0 && $i!=0) {
                echo "   car-models-110 - " . $i . " rows affected\r\n";
            }
            $manuf['model'] = \substr($row, 7, 5);
            $manuf['manuf'] = \substr($row, 21, 6);
            $manuf['descr_numb'] = trim(\substr($row, 12, 9));            
            $td->addModel_110($manuf);  
        }
    }

    public function loadCars_120($td) {
        $file_name = \Constants::$input_files_root_path . "ref\\120.dat";
        
        $array = explode("\n", file_get_contents($file_name));
        $i = 0;
        echo "start loading cars-120 ... \r\n";
        foreach($array as $row) {
            $i++;
            if ($i%10000 == 0 && $i!=0) {
                echo "   cars-120- " . $i . " rows affected\r\n";
            }

            $car['car'] = \substr($row, 7, 9);
            $car['tbl'] = 120;
            $car['model'] = \substr($row, 25, 5);            
            $car['descr_numb'] = trim(\substr($row, 16, 9));
            $car['d_from'] = trim(\substr($row, 32, 6));
            $car['d_to'] = trim(\substr($row, 38, 6));
            $car['cap_l'] = trim(\substr($row, 57, 5));
            $car['cap_hp'] = trim(\substr($row, 48, 4));
            $car['cap_kw'] = trim(\substr($row, 44, 4)); 
            $car['sort_nr'] = trim(\substr($row, 30, 2));            
            $td->addCar_120_532($car);  
        }
	}
	
    public function loadCars_532($td) {
        $file_name = \Constants::$input_files_root_path . "ref\\532.dat";
        
        $array = explode("\n", file_get_contents($file_name));
        $i = 0;
        echo "start loading cars-532 ... \r\n";
        foreach($array as $row) {
            $i++;
            if ($i%10000 == 0 && $i!=0) {
                echo "   cars-532 - " . $i . " rows affected\r\n";
            }

            $car['car'] = \substr($row, 29, 9);
            $car['tbl'] = 532;
            $car['model'] = \substr($row, 38, 5);            
            $car['descr_numb'] = trim(\substr($row, 47, 9));
            $car['d_from'] = trim(\substr($row, 56, 6));
            $car['d_to'] = trim(\substr($row, 62, 6));
            $car['cap_l'] = trim(\substr($row, 90, 5));
            $car['cap_hp'] = trim(\substr($row, 82, 4));
            $car['cap_kw'] = trim(\substr($row, 74, 4));   
            $car['sort_nr'] = trim(\substr($row, 43, 4));                     
            $td->addCar_120_532($car);  
        }
    }

    public static function loadEngines_155($td) {
        $file_name = \Constants::$input_files_root_path . "ref\\155.dat";
        
        $array = explode("\n", file_get_contents($file_name));
        $i = 0;
        echo "start loading ... \r\n";
        foreach($array as $row) {
            $i++;
            if($i%10000 == 0 && $i!=0) {
                echo "   engines - " . $i . " rows affected\r\n";
            }
            $engine['id'] = \substr($row, 13, 5);
            $engine['code'] = trim(\mb_substr($row, 18, 60));
            $td->addEngine_155($engine);  
        }
    }

    public static function loadEngineCars_125($td) {
        $file_name = \Constants::$input_files_root_path . "ref\\125.dat";
        
        $array = explode("\n", file_get_contents($file_name));
        $i = 0;
        echo "start loading ... \r\n";
        foreach($array as $row) {
            $i++;
            if($i%10000 == 0 && $i!=0) {
                echo "   engine-cars-125 - " . $i . " rows affected\r\n";
            }
            $engineCar['car'] = \substr($row, 29, 9);
            $engineCar['engine_id'] = trim(\mb_substr($row, 41, 5));
            $engineCar['tbl'] = 120;
            $td->addEngineCar_125_537($engineCar);  
        }
    }

    public static function loadEngineCars_537($td) {
        $file_name = \Constants::$input_files_root_path . "ref\\537.dat";
        
        $array = explode("\n", file_get_contents($file_name));
        $i = 0;
        echo "start loading ... \r\n";
        foreach($array as $row) {
            $i++;
            if($i%10000 == 0 && $i!=0) {
                echo "   engine-cars-537 - " . $i . " rows affected\r\n";
            }
            $engineCar['car'] = \substr($row, 29, 9);
            $engineCar['engine_id'] = trim(\mb_substr($row, 44, 5));
            $engineCar['tbl'] = 532;
            $td->addEngineCar_125_537($engineCar);  
        }
    }
}