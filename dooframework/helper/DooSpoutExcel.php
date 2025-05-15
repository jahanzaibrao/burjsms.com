<?php
set_include_path(dirname(__FILE__).'/Spout');
include('Spout/Autoloader/autoload.php');
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Common\Type;

class DooSpoutExcel extends ReaderEntityFactory {
    public static function getReader($type='xlsx'){
        if($type=='xlsx') return ReaderEntityFactory::createXLSXReader();
        if($type=='csv') return ReaderEntityFactory::createCSVReader();
    }

    public static function getSheetsColumns($filepath, $type='xlsx', $sheet_to_parse='all', $mobile_index=0){
        $sheets = array();
        $totalrows=0;
        $columns = array();
        $reader = DooSpoutExcel::getReader($type);
        $reader->open($filepath);

        foreach ($reader->getSheetIterator() as $sheet) {
            $sheetName = $sheet->getName();
            array_push($sheets, $sheetName);
            if(($sheet_to_parse == 'all' && $sheet->getIndex() === 0) || ($sheetName == $sheet_to_parse)){
                $n = 1;
                foreach ($sheet->getRowIterator() as $row) {
                    if($n==1){
                        $cells = $row->getCells();
                        $columns['A'] = $cells[0]->getValue();
                        if(isset($cells[1])) $columns['B'] = $cells[1]->getValue();
                        if(isset($cells[2])) $columns['C'] = $cells[2]->getValue();
                        if(isset($cells[3])) $columns['D'] = $cells[3]->getValue();
                        if(isset($cells[4])) $columns['E'] = $cells[4]->getValue();
                        if(isset($cells[5])) $columns['F'] = $cells[5]->getValue();
                        if(isset($cells[6])) $columns['G'] = $cells[6]->getValue();
                        if(isset($cells[7])) $columns['H'] = $cells[7]->getValue();
                        if(isset($cells[8])) $columns['I'] = $cells[8]->getValue();
                        if(isset($cells[9])) $columns['J'] = $cells[9]->getValue();
                        if(isset($cells[10])) $columns['K'] = $cells[10]->getValue();
                    }
                    if($n > 100000){
                        break;
                    }
                    $mobile = intval(trim($row->getCellAtIndex($mobile_index)->getValue()));
                    if($mobile!=0){
                        $totalrows++;
                    }
                    $n++;
                }
            }
        }
        $reader->close();
        return json_encode(array(
            'totalrows' => $totalrows,
            'cols' => $columns,
            'sheets' => $sheets
        ));
    }
}
?>
