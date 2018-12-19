<?php

include_once './db.php';
include_once '../excel/Classes/PHPExcel.php';
session_start();
if (isset($_POST['txtCompSearchBox']) && !empty($_POST['txtCompSearchBox'])) {
    $keyword = $_POST['txtCompSearchBox'];    
    $phpExcel = new PHPExcel();
    $phpExcel->createSheet();
    $file_name = "";

    $sqlContactDetail = "SELECT * , (SELECT GROUP_CONCAT( DISTINCT (SELECT te.empl_name FROM tbl_employee te WHERE te.empl_id = tec.empl_id) ) FROM tbl_emp_contact tec WHERE tec.cont_id = tc.cont_id) AS touch, comp_name FROM tbl_contact tc, tbl_office tof, tbl_company tcom WHERE tc.offi_id = tof.offi_id and tc.comp_id = tcom.comp_id AND tc.cont_id IN (select distinct(cont_id) from tbl_contact where cont_name like '%$keyword%' union select distinct(cont_id) from tbl_contact where `cont_email` like '%$keyword%' union select distinct(cont_id) from tbl_contact where `cont_mobile` like '%$keyword%' union select distinct(cont_id) from tbl_contact where `cont_direct` like '%$keyword%' union select distinct(cont_id) from tbl_contact where `cont_ext` like '%$keyword%' union select distinct(cont_id) from tbl_contact where `cont_desg` like '%$keyword%' union select distinct(cont_id) from tbl_contact l left join tbl_company r on l.comp_id=r.comp_id where r.comp_name like '%$keyword%' union select distinct(cont_id) from tbl_contact l left join tbl_company r on l.comp_id=r.comp_id where r.comp_name in (SELECT comp_name FROM `tbl_company` WHERE comp_type='$keyword') union select distinct(cont_id) from tbl_contact l left join tbl_company r on l.comp_id=r.comp_id where r.comp_website like '%$keyword%' union select distinct(cont_id) from tbl_contact l left join tbl_office r on l.offi_id=r.offi_id where r.offi_boardline like '%$keyword%' union select distinct(cont_id) from tbl_contact l left join tbl_office r on l.offi_id=r.offi_id where r.offi_address like '%$keyword%' union select distinct(cont_id) from tbl_contact l left join tbl_office r on l.offi_id=r.offi_id where r.offi_city like '%$keyword%')";

    $contactRes = mysqli_query($con, $sqlContactDetail);

    $phpExcel->getActiveSheet()->setCellValue("A1", "NAME");
    $phpExcel->getActiveSheet()->setCellValue("B1", "COMPANY NAME");
    $phpExcel->getActiveSheet()->setCellValue("C1", "STATUS");
    $phpExcel->getActiveSheet()->setCellValue("D1", "EMAIL");
    $phpExcel->getActiveSheet()->setCellValue("E1", "MOBILE");
    $phpExcel->getActiveSheet()->setCellValue("F1", "DIRECT");
    $phpExcel->getActiveSheet()->setCellValue("G1", "EXTENSION");
    $phpExcel->getActiveSheet()->setCellValue("H1", "DESIGNATION");
    $phpExcel->getActiveSheet()->setCellValue("I1", "DEPARTMENT");
    $phpExcel->getActiveSheet()->setCellValue("J1", "OFFICE TYPE");
    $phpExcel->getActiveSheet()->setCellValue("K1", "OFFICE BOARD LINE");
    $phpExcel->getActiveSheet()->setCellValue("L1", "OFFICE ADDRESS");
    $phpExcel->getActiveSheet()->setCellValue("M1", "RECRUITMENT CALL STATUS");
    $phpExcel->getActiveSheet()->setCellValue("N1", "STREAM");
    $phpExcel->getActiveSheet()->setCellValue("O1", "COMPANY TYPE");
    $phpExcel->getActiveSheet()->setCellValue("P1", "WEB SITE");
    $phpExcel->getActiveSheet()->setCellValue("Q1", "IN TOUCH");


    $i = 2;
    while ($row = mysqli_fetch_array($contactRes)) {
        $name = $row['cont_name'];
        $company_name = $row['comp_name'];
        $cont_id = $row['cont_id'];
        $sqlStatus = "select act_newStatus from tbl_emp_contact where empl_cont_id = (SELECT MAX( empl_cont_id ) FROM tbl_emp_contact where cont_id=$cont_id )";
        $resStatus = mysqli_query($con, $sqlStatus);
        $status = mysqli_fetch_array($resStatus);
        $cont_status = $status[0];
        $email = $row['cont_email'];
        $mobile = $row['cont_mobile'];
        $direct = $row['cont_direct'];
        $extension = $row['cont_ext'];
        $designation = $row['cont_desg'];
        $dept = $row['cont_dept'];
        $offi_type = $row['offi_type'];
        $offi_boardline = $row['offi_boardline'];
        $pin = '';
        if ($row['offi_pin'] == "") {
            $pin = "";
        } else {
            $pin = ", PIN - " . $row['offi_pin'];
        }
        $offi_address = $row['offi_address'] . ", " . $row['offi_city'] . ", " . $row['offi_country'] . $pin;
        $offi_rec = $row['offi_rec'];
        $stream = '';
        $stream_sql = "SELECT stream FROM `tbl_stream` WHERE `cont_id` =$cont_id order by stream";
        $stream_res = mysqli_query($con, $stream_sql);
        $j = 1;
        while ($stream_row = mysqli_fetch_array($stream_res)) {
            if ($j == 1) {
                $stream .= $stream_row[0];
            } else {
                $stream .= ", " . $stream_row[0];
            }
            $j++;
        }
        $company_type = $row['comp_type'];
        $company_website = $row['comp_website'];
        $touch = $row['touch'];

        $phpExcel->getActiveSheet()->setCellValue("A" . $i, $name);
        $phpExcel->getActiveSheet()->setCellValue("B" . $i, $company_name);
        $phpExcel->getActiveSheet()->setCellValue("C" . $i, $cont_status);
        $phpExcel->getActiveSheet()->setCellValue("D" . $i, $email);
        $phpExcel->getActiveSheet()->setCellValue("E" . $i, $mobile);
        $phpExcel->getActiveSheet()->setCellValue("F" . $i, $direct);
        $phpExcel->getActiveSheet()->setCellValue("G" . $i, $extension);
        $phpExcel->getActiveSheet()->setCellValue("H" . $i, $designation);
        $phpExcel->getActiveSheet()->setCellValue("I" . $i, $dept);
        $phpExcel->getActiveSheet()->setCellValue("J" . $i, $offi_type);
        $phpExcel->getActiveSheet()->setCellValue("K" . $i, $offi_boardline);
        $phpExcel->getActiveSheet()->setCellValue("L" . $i, $offi_address);
        $phpExcel->getActiveSheet()->setCellValue("M" . $i, $offi_rec);
        $phpExcel->getActiveSheet()->setCellValue("N" . $i, $stream);
        $phpExcel->getActiveSheet()->setCellValue("O" . $i, $company_type);
        $phpExcel->getActiveSheet()->setCellValue("P" . $i, $company_website);
        $phpExcel->getActiveSheet()->setCellValue("Q" . $i, $touch);
        $i++;
    }

    $file_name = 'Content-Disposition: attachment; filename="' . $keyword . '-Contact-Details.xls"';
    $excelWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");
    //$excelWriter->save("StudentData-UG.xls");
    header('Content-type:application/vnd.ms-excel');
    header($file_name);
    $excelWriter->save('php://output');
}
?>