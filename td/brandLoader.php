<?php

namespace Text;

class BrandLoader {
    private $strId;
    private $intId;

    public function __construct($id) {
        $this->intId = $id;
        $this->strId = str_pad($id, 4, '0', STR_PAD_LEFT);
    }

    public function loadApplicData_400($td, $table_index = "") {
        $file_name = \Constants::$input_files_root_path . \Constants::$input_data_suff . $this->strId . "\\400." . $this->strId;
        $array = explode("\n", file_get_contents($file_name));
        $i = 0;
        foreach($array as $row) {
            $appl['article'] = trim(\substr($row, 0, 22));
            $appl['suppl'] = \substr($row, 22, 4);
            $appl['car'] = \substr($row, 37, 9);
            $tbl_type = \substr($row, 34, 3);
            $appl['tbl_type'] = 0;
            if ($tbl_type == 2) {
                $appl['tbl_type'] = 120;
            }
            if ($tbl_type == 16) {
                $appl['tbl_type'] = 532;
            }

            $appl['gen_art_nr'] = \substr($row, 29, 5);
            $appl['vkn_ziel_art'] = $tbl_type;
            $appl['lfd_nr'] = \substr($row, 46, 9);
            
            if ($appl['tbl_type']) {
                $td->addApplic_400($appl, $table_index);
                $i++;
            }
            
            if($i%10000 == 0 && $i!=0) {
                echo "   applicability - " . $i . " rows affected\r\n";
            }
        }
    }

    public function loadCharactData($td, $table_index) {
        $file_name = \Constants::$input_files_root_path . \Constants::$input_data_suff . $this->strId . "\\210." . $this->strId;
        $array = explode("\n", file_get_contents($file_name));
        $i = 0;
        foreach($array as $row) {
            $charact['article'] = trim(\substr($row, 0, 22));
            $charact['suppl'] = \substr($row, 22, 4);
            $charact['sort_nr'] = \substr($row, 37, 3);
            $charact['numb'] = \substr($row, 40, 4);
            $charact['descr'] = trim(\substr($row, 44, 20));

            $td->addArticleCharact($charact);
            
            $i++;
            if($i%10000 == 0 && $i!=0) {
                echo "   characts - " . $i . " rows affected\r\n";
            }
        }
    }
}