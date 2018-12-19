<?php
include_once './db.php';
include_once '../excel/Classes/PHPExcel.php';
session_start();
$companyId=$_POST['txtCompSearchBox'];
$status=$_POST['status_list'];
$memberList=$_POST['memberList'];
$memberQuery="";
//echo sizeof($memberList);
for($i=0;$i<sizeof($memberList);$i++){
    if($i==0)
        $memberQuery .= "$memberList[$i]";
    else
        $memberQuery .= ", $memberList[$i]";
}
    
$reportType=$_POST['reportType'];
$phpExcel = new PHPExcel();
$phpExcel->createSheet();

if($reportType=="activity" || $reportType=="connection"){
    $sql="select * from tbl_company tco,tbl_office tof,tbl_contact tc,tbl_emp_contact tec,tbl_employee te where
          tco.comp_id=tof.comp_id and
          tof.offi_id=tc.offi_id and
          tc.cont_id=tec.cont_id and
          te.empl_id = tec.empl_id and 1 ";
    if($memberQuery != "")
          $sql .="and tec.empl_id IN ($memberQuery) ";
    if($status != "All")
          $sql .="and tc.cont_status='$status' ";
    if($companyId != "All")
          $sql.="and tco.comp_id=$companyId ";
    $phpExcel->getActiveSheet()->setCellValue("A1","COMPANY NAME");
    $phpExcel->getActiveSheet()->setCellValue("B1","CONTACT NAME");
    $phpExcel->getActiveSheet()->setCellValue("C1","CONTACT JOB POSITION");
    $phpExcel->getActiveSheet()->setCellValue("D1","CONTACT STATUS");
    $phpExcel->getActiveSheet()->setCellValue("E1","OFFICE DETAILS");
    $phpExcel->getActiveSheet()->setCellValue("F1","OFFICE CITY");
    $phpExcel->getActiveSheet()->setCellValue("G1","ACTIVITY DATE");
    $phpExcel->getActiveSheet()->setCellValue("H1","ACTIVITY TYPE");
    $phpExcel->getActiveSheet()->setCellValue("I1","ACTIVITY DETAIL");
    
    $res=  mysqli_query($con, $sql);
    $i=2;
    while ($row = mysqli_fetch_array($res)) {
    $phpExcel->getActiveSheet()->setCellValue("A".$i,$row['comp_name']);
    $phpExcel->getActiveSheet()->setCellValue("B".$i,$row['cont_name']);
    $phpExcel->getActiveSheet()->setCellValue("C".$i,$row['cont_dept']." , ".$row['cont_desg']);
    $phpExcel->getActiveSheet()->setCellValue("D".$i,$row['cont_status']);
    $phpExcel->getActiveSheet()->setCellValue("E".$i,$row['offi_type']." , ".$row['offi_address']);
    $phpExcel->getActiveSheet()->setCellValue("F".$i,$row['offi_city']);
    $phpExcel->getActiveSheet()->setCellValue("G".$i,$row['act_date']);
    $phpExcel->getActiveSheet()->setCellValue("H".$i,$row['act_type']);
    $phpExcel->getActiveSheet()->setCellValue("I".$i,$row['act_detail']);
    $i++;
    }
    $excelWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");
//$excelWriter->save("StudentData-UG.xls");
header('Content-type:application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="CorporateData.xls"');
$excelWriter->save('php://output');
    
    
}
if($reportType=="todo"){
    $sql="select * from tbl_company tco,tbl_office tof,tbl_contact tc,tbl_todo td,tbl_employee te where
          tco.comp_id=tof.comp_id and
          tof.offi_id=tc.offi_id and
          tc.cont_id=td.cont_id and
          te.empl_id = td.empl_id and 1 ";
    if($memberQuery != "")
          $sql .="and td.empl_id IN ($memberQuery) ";
    if($status != "All")
          $sql .="and tc.cont_status='$status' ";
    if($companyId != "All")
          $sql.="and tco.comp_id=$companyId ";
    //echo $sql;
}

?>