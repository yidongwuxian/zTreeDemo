<?php

class ExcelRead 
{
    public static function read($filename, $encode='utf-8')
    {
        include_once('PHPExcel/IOFactory.php');

        $objPHPExcel = PHPExcel_IOFactory::load($filename);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        
        return $sheetData;
    }  

}