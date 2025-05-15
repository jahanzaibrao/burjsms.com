<?php

    /**************************
     * Author: Gabriel Gilini *
     **************************/
    /*****************************************************
    // Usage example:

    $columns = array('A', 'C', 'J', 'K', 'M', 'N', 'O');

    $filename = '/tmp/example.xls';
    $fileType = PHPExcel_IOFactory::identify($filename);
    $reader = PHPExcel_IOFactory::createReader($fileType);

    $startRow = 4;
    $rowStep = 1000;

    $reader->setReadDataOnly(true);
    while(true)
    {
        $reader->setReadFilter(
            new PartialReader(
                $startRow,
                $startRow + $rowStep - 1,
                $columns
            )
        );
        $phpExcel = $reader->load($filename);
        $sheet = $phpExcel->getActiveSheet();
        $collection = $sheet->getCellCollection();
        unset($phpExcel, $sheet);

        if(empty($collection))
        {
            break;
        }
        $startRow += $rowStep;
    
        // Do something with the collection
    }
    *****************************************************/

    require_once('PHPExcel.php');
    class PartialReader implements PHPExcel_Reader_IReadFilter
    {
        public function __construct($minRow = 0, $maxRow = 65536, $columns = array())
        {
            $this->minRow = $minRow;
            $this->maxRow = $maxRow;

            if(empty($columns))
            {
                $this->allColumns = true;
            }
            else
            {
                $this->allColumns = false;
                $this->columns = $columns;
            }
        }

        public function readCell($column, $row, $worksheetName = '')
        {
            if (
                $row >= $this->minRow
                && $row <= $this->maxRow
                && (
                    $this->allColumns
                    || in_array(
                            $column,
                            $this->columns
                        )
                )
            )
            {
                return true;
            }

            return false;
        }
    }
