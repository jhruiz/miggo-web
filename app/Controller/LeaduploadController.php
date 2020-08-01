<?php

App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel/PHPExcel.php'));
App::import('Vendor', 'PHPExcel_IOFactory', array('file' => 'PHPExcel'.DS.'PHPExcel'.DS.'IOFactory.php'));
App::import('Vendor', 'PHPExcel_IOFactory', array('file' => 'PHPExcel'.DS.'PHPExcel'.DS.'Style.php'));

class LeaduploadController extends AppController {

    public function exeldownload(){
        
        $this->autoRender = false;
        $this-> layout='ajax';

    $objPHPExcel = new PHPExcel();
    $serialnumber=0;
    $tmparray =array("Sr.Number","Employee ID","Employee Name");
    $sheet =array($tmparray);
    $tmparray =array();
    $serialnumber = $serialnumber + 1;
    array_push($tmparray,$serialnumber);
    $employeelogin = 'aa';
    array_push($tmparray,$employeelogin);
    $employeename = 'bb';
    array_push($tmparray,$employeename);   
    array_push($sheet,$tmparray);
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="file.xls"');
    $worksheet = $objPHPExcel->getActiveSheet();
    foreach($sheet as $row => $columns) {
        foreach($columns as $column => $data) {
            $worksheet->setCellValueByColumnAndRow($column, $row + 1, $data);
        }
    }
    $objPHPExcel->getActiveSheet()->getStyle("A1:I1")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output'); 

    }
}