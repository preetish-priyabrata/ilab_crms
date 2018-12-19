<?php

include_once './db.php';
include_once '../excel/Classes/PHPExcel.php';
session_start();
$status = $_POST['status_list'];
$phpExcel = new PHPExcel();
$phpExcel->createSheet();

if ($_POST['chkStream'] && !empty($_POST['chkStream'])) {
    $stream = $_POST['chkStream'];
    $str = implode("', '", $stream);
    $sql = "select comp_name, comp_type,act_statusComment,empl_name from tbl_company tco,tbl_emp_contact tec, tbl_employee te where
          tco.comp_id=tec.comp_id and tec.cont_id in (SELECT distinct(`cont_id`) FROM `tbl_stream` WHERE stream in ('$str')) and
          tec.act_newStatus='$status' and tec.empl_id=te.empl_id order by comp_name asc";
} else {
    $sql = "select comp_name, comp_type,act_statusComment,empl_name 
            from tbl_company tco,tbl_emp_contact tec, tbl_employee te 
            where tco.comp_id=tec.comp_id 
            and tec.act_newStatus='$status' "
            . "and tec.empl_id=te.empl_id "
            . "order by comp_name asc";
}
//echo $sql;

$phpExcel->getActiveSheet()->setCellValue("A1", "COMPANY NAME");
$phpExcel->getActiveSheet()->setCellValue("B1", "COMPANY TYPE");
$phpExcel->getActiveSheet()->setCellValue("C1", "EXPECTED DATE/COMMENTS");
$phpExcel->getActiveSheet()->setCellValue("D1", "EMPLOYEE");

$res = mysqli_query($con, $sql);
$i = 2;
$companyLst = array();
if (isset($_POST['year']) && isset($_POST['month']) && $_POST['month'] != 'Select a Month' && $_POST['year'] != 'Select a Year') {
    $year = $_POST['year'];
    $month = $_POST['month'];
    while ($row = mysqli_fetch_array($res)) {
        $comment = $row['act_statusComment'];
        $date = explode(",", $comment);
        $companyEmp = $row['comp_name'] . $row['empl_name'];
        if ($date[1] == $year) {
            if ($date[0] == $month) {
                if (!in_array($companyEmp, $companyLst)) {
                    $phpExcel->getActiveSheet()->setCellValue("A" . $i, $row['comp_name']);
                    $phpExcel->getActiveSheet()->setCellValue("B" . $i, $row['comp_type']);
                    $phpExcel->getActiveSheet()->setCellValue("C" . $i, $row['act_statusComment']);
                    $phpExcel->getActiveSheet()->setCellValue("D" . $i, $row['empl_name']);
                    array_push($companyLst, $companyEmp);
                    $i++;
                }                
            }
        }
    }
} else {
    while ($row = mysqli_fetch_array($res)) {
        $companyYear = $row['comp_name'] . $row['empl_name'] . $row['act_statusComment'];
        if (!in_array($companyYear, $companyLst)) {
            $phpExcel->getActiveSheet()->setCellValue("A" . $i, $row['comp_name']);
            $phpExcel->getActiveSheet()->setCellValue("B" . $i, $row['comp_type']);
            $phpExcel->getActiveSheet()->setCellValue("C" . $i, $row['act_statusComment']);
            $phpExcel->getActiveSheet()->setCellValue("D" . $i, $row['empl_name']);
            array_push($companyLst, $companyYear);
            $i++;
        }        
    }
}
$excelWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");
//$excelWriter->save("StudentData-UG.xls");
header('Content-type:application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="CorporateDataByStatus.xls"');
$excelWriter->save('php://output');
?>