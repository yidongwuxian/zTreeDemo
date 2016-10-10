<?php
class ExcelMaker{

	public static function savefile($filename,$datalist,$title,$titlelist)
	{

		$objExcel = new PHPExcel();

		$objWriter = new PHPExcel_Writer_Excel5($objExcel);


		$objProps = $objExcel->getProperties();
		$objProps->setCreator("tangguo");
		$objProps->setLastModifiedBy("糖果网络");
		$objProps->setTitle("糖果网络");
		$objProps->setSubject("糖果网络");
		$objProps->setDescription("糖果网络");
		$objProps->setKeywords("糖果网络");
		$objProps->setCategory("糖果网络");

		$objExcel->setActiveSheetIndex(0);
		$objActSheet = $objExcel->getActiveSheet();

		$objActSheet->setTitle($title);

		foreach ($titlelist as $key => $value) {
		    $x = $key / 26;
			if($x >= 1){
				$char = chr(64+intval($x)).chr(65+($key-intval($x)*26));
			}else{
				$char = chr(65+$key);
			}
			// $objActSheet->getColumnDimension($char)->setAutoSize(true);
			$objActSheet->getColumnDimension($char)->setWidth(14);
			$objActSheet->setCellValue($char.'1', $value);
		}
		foreach ($datalist as $key => $value) {

			$i=2+$key;
			$j=0;
		    	foreach ($value as $key_m => $value_m) {
		    		$y = $j / 26;
		    		if($y >= 1){
		    			$char = chr(64+intval($y)).chr(65+($j-intval($y)*26));
		    		}else{
		    			$char = chr(65+$j);
		    		}
		    		$objActSheet->setCellValueExplicit($char.$i, $value_m,PHPExcel_Cell_DataType::TYPE_STRING);
	
		    		$j++;
		    	}
		}

		ob_end_clean();//清除缓冲区,避免乱码
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$filename);
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}


	//csv
	public static function savefileCsv($filename,$datalist,$title,$titlelist)
	{
		$objExcel = new PHPExcel();

		$objWriter = new PHPExcel_Writer_CSV($objExcel);

		$objWriter->setEnclosure('');
		$objWriter->setLineEnding("\r\n");
		$objWriter->setSheetIndex(0);

		$objExcel->setActiveSheetIndex(0);
		$objActSheet = $objExcel->getActiveSheet();

		$objActSheet->setTitle($title);

		foreach ($titlelist as $key => $value) {
			$char = chr(65+$key);

			$objActSheet->getColumnDimension($char)->setAutoSize(true);
			$objActSheet->setCellValue($char.'1', $value);
		}
		foreach ($datalist as $key => $value) {

			$i=2+$key;
			$j=0;
			foreach ($value as $key_m => $value_m) {

				$char = chr(65+$j);
				$objActSheet->setCellValueExplicit($char.$i, $value_m,PHPExcel_Cell_DataType::TYPE_STRING);

				$j++;
			}
		}

		ob_end_clean();//清除缓冲区,避免乱码
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$filename);
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

	//csv
	public static function exportCsv($filename, $data)  
    {  
        header("Content-type:text/csv");  
        header("Content-Disposition:attachment;filename=".$filename);  
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');  
        header('Expires:0');  
        header('Pragma:public');  
        echo $data;  
    } 
}