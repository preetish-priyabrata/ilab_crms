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
$phpExcel->getActiveSheet()->setCellValue("R1", "SCHEDULE SET DATE");
$phpExcel->getActiveSheet()->setCellValue("S1", "SCHEDULE STATUS");
$phpExcel->getActiveSheet()->setCellValue("T1", "SCHEDULE CLOSURE DATE");
$phpExcel->getActiveSheet()->setCellValue("U1", "SCHEDULE TYPE");
$phpExcel->getActiveSheet()->setCellValue("V1", "SCHEDULE DETAIL");
$i = 2;
//$row['']
while ($row = mysqli_fetch_array($res)) {
    $phpExcel->getActiveSheet()->setCellValue("A$i", $companyName);
    $phpExcel->getActiveSheet()->setCellValue("B$i", $row['cont_name']);
    $phpExcel->getActiveSheet()->setCellValue("C$i", $row['cont_desg']);
    $phpExcel->getActiveSheet()->setCellValue("D$i", $row['cont_dept']);
    //$phpExcel->getActiveSheet()->setCellValue("E$i", $row['cont_type']);
    if ($_SESSION['contViewSetting'] == "No" && $empId != $row[22]) {
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
    $phpExcel->getActiveSheet()->setCellValue("K$i", $row['offi_type']);
    $phpExcel->getActiveSheet()->setCellValue("L$i", $row['offi_boardline']);
    $phpExcel->getActiveSheet()->setCellValue("M$i", $row['offi_rec']);
    $phpExcel->getActiveSheet()->setCellValue("N$i", $row['offi_address']);
    $phpExcel->getActiveSheet()->setCellValue("O$i", $row['offi_country']);
    $phpExcel->getActiveSheet()->setCellValue("P$i", $row['offi_city']);
    $phpExcel->getActiveSheet()->setCellValue("Q$i", $row['empl_name']);
    $phpExcel->getActiveSheet()->setCellValue("R$i", $row['todo_date']);
    $phpExcel->getActiveSheet()->setCellValue("S$i", $row['todo_status']);
    $phpExcel->getActiveSheet()->setCellValue("T$i", $row['todo_endDate']);
    $phpExcel->getActiveSheet()->setCellValue("U$i", $row['todo_type']);
    $phpExcel->getActiveSheet()->setCellValue("V$i", $row['todo_detail']);
    $i++;
}
$excelWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");
//$excelWriter->save("StudentData-UG.xls");
header('Content-type:application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="CorporateData-Schedule.xls"');
$excelWriter->save('php://output');
?>
