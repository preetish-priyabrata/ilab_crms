<?php

include_once './db.php';
include_once '../excel/Classes/PHPExcel.php';
session_start();
$query = $_SESSION['queryDownload'];
$companyName = $_SESSION['companyName'];
$empId = $_SESSION['empId'];
$res = mysqli_query($con, $query);
$phpExcel = new PHPExcel();
$phpExcel->createSheet();
$phpExcel->getActiveSheet()->setCellValue("A1", "COMPANY NAME");
$phpExcel->getActiveSheet()->setCellValue("B1", "CONTACT NAME");
$phpExcel->getActiveSheet()->setCellValue("C1", "DESIGNATION");
$phpExcel->getActiveSheet()->setCellValue("D1", "DEPARTMENT");
//$phpExcel->getActiveSheet()->setCellValue("E1", "TYPE");
$phpExcel->getActiveSheet()->setCellValue("F1", "EMAIL");
$phpExcel->getActiveSheet()->setCellValue("G1", "MOBILE");
$phpExcel->getActiveSheet()->setCellValue("H1", "DIRECT");
$phpExcel->getActiveSheet()->setCellValue("I1", "EXTENSION");
$phpExcel->getActiveSheet()->setCellValue("J1", "STATUS");
$phpExcel->getActiveSheet()->setCellValue("K1", "OFFICE TYPE");
$phpExcel->getActiveSheet()->setCellValue("L1", "BOARDLINE NUMBER");
$phpExcel->getActiveSheet()->setCellValue("M1", "RECRUITMENT CALLS TAKEN");
$phpExcel->getActiveSheet()->setCellValue("N1", "ADDRESS");
$phpExcel->getActiveSheet()->setCellValue("O1", "COUNTRY");
$phpExcel->getActiveSheet()->setCellValue("P1", "CITY");
$phpExcel->getActiveSheet()->setCellValue("Q1", "MEMBER NAME");
$phpExcel->getActiveSheet()->setCellValue("R1", "ACTIVITY DATE");
$phpExcel->getActiveSheet()->setCellValue("S1", "ACTIVITY TYPE");
$phpExcel->getActiveSheet()->setCellValue("T1", "ACTIVITY DETAIL");
$i = 2;
//$row['']
while ($row = mysqli_fetch_array($res)) {
    if($i==2){
        $sqlOffice="select * from tbl_office where offi_id=".$row['offi_id'];
        $resOffice=  mysqli_query($con, $sqlOffice);
        $rowOffice = mysqli_fetch_array($resOffice);
    }
    $phpExcel->getActiveSheet()->setCellValue("A$i", $companyName);
    $phpExcel->getActiveSheet()->setCellValue("B$i", $row['cont_name']);
    $phpExcel->getActiveSheet()->setCellValue("C$i", $row['cont_desg']);
    $phpExcel->getActiveSheet()->setCellValue("D$i", $row['cont_dept']);
    //$phpExcel->getActiveSheet()->setCellValue("E$i", $row['cont_type']);
    if ($_SESSION['contViewSetting'] == "No" && $empId != $row[21]) {
        $phpExcel->getActiveSheet()->setCellValue("F$i", "Not Authorized");
        $phpExcel->getActiveSheet()->setCellValue("G$i", "Not Authorized");
        $phpExcel->getActiveSheet()->setCellValue("H$i", "Not Authorized");
        $phpExcel->getActiveSheet()->setCellValue("I$i", "Not Authorized");
    } else {
        $phpExcel->getActiveSheet()->setCellValue("F$i", $row['cont_email']);
        $phpExcel->getActiveSheet()->setCellValue("G$i", $row['cont_mobile']);
        $phpExcel->getActiveSheet()->setCellValue("H$i", $row['cont_direct']);
        $phpExcel->getActiveSheet()->setCellValue("I$i", $row['cont_ext']);
    }
    $phpExcel->getActiveSheet()->setCellValue("J$i", $row['cont_status']);
    $phpExcel->getActiveSheet()->setCellValue("K$i", $rowOffice['offi_type']);
    $phpExcel->getActiveSheet()->setCellValue("L$i", $rowOffice['offi_boardline']);
    $phpExcel->getActiveSheet()->setCellValue("M$i", $rowOffice['offi_rec']);
    $phpExcel->getActiveSheet()->setCellValue("N$i", $rowOffice['offi_address']);
    $phpExcel->getActiveSheet()->setCellValue("O$i", $rowOffice['offi_country']);
    $phpExcel->getActiveSheet()->setCellValue("P$i", $rowOffice['offi_city']);
    $phpExcel->getActiveSheet()->setCellValue("Q$i", $row['empl_name']);
    $phpExcel->getActiveSheet()->setCellValue("R$i", $row['act_date']);
    $phpExcel->getActiveSheet()->setCellValue("S$i", $row['act_type']);
    $phpExcel->getActiveSheet()->setCellValue("T$i", $row['act_detail']);
    $i++;
}
$excelWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");
//$excelWriter->save("StudentData-UG.xls");
header('Content-type:application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="CorporateData-Activity.xls"');
$excelWriter->save('php://output');
?>
