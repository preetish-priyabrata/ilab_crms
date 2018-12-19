<?php

include_once './db.php';
include_once '../excel/Classes/PHPExcel.php';
session_start();

$phpExcel = new PHPExcel();
$phpExcel->createSheet();
$file_name = "";

if (isset($_POST['hidReportFor']) && $_POST['hidReportFor'] == "employee") {
    if (isset($_POST['hidEmlpId']) && !empty($_POST['hidEmlpId'])) {
        $empId = $_POST['hidEmlpId'];
        $empl_id = $_POST['cmbEmpLst'];
        $empl_sql = "select empl_name from tbl_employee where empl_id=$empl_id";
        $empl_res = mysqli_query($con, $empl_sql);
        $empl_array = mysqli_fetch_array($empl_res);
        $empl_name = $empl_array[0];
    } else {
        $empId = $_SESSION['empId'];
    }
    $comp_id = $_POST['cmbCompLst'];
    if ($comp_id == "all") {
        $contactSql = "SELECT * FROM tbl_contact tc, tbl_office tof, tbl_company tcom WHERE tc.offi_id = tof.offi_id and tc.comp_id = tcom.comp_id and `empl_id`=$empId order by cont_name asc";
        $contactRes = mysqli_query($con, $contactSql);

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
        $phpExcel->getActiveSheet()->setCellValue("Q1", "EVENT");

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
            $event = $row['cont_conclave'];
            if ($event == "") {
                $event = "No Event";
            }
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
            $phpExcel->getActiveSheet()->setCellValue("Q" . $i, $event);
            $i++;
        }
        if (isset($_POST['hidEmlpId']) && !empty($_POST['hidEmlpId'])) {
            $file_name = 'Content-Disposition: attachment; filename="' . $empl_name . '-Contact List-All Companies.xls"';
        } else {
            $file_name = 'Content-Disposition: attachment; filename="Contact List-All Companies.xls"';
        }
    } else {
        $contactSql = "SELECT * , comp_name FROM tbl_contact tc, tbl_office tof, tbl_company tcom WHERE tc.offi_id = tof.offi_id and tc.comp_id = tcom.comp_id and `empl_id`= $empId AND tc.comp_id = $comp_id order by cont_name asc";
        $contactRes = mysqli_query($con, $contactSql);

        $name = $row['cont_name'];
        $phpExcel->getActiveSheet()->setCellValue("A1", "NAME");
        $phpExcel->getActiveSheet()->setCellValue("B1", "STATUS");
        $phpExcel->getActiveSheet()->setCellValue("C1", "EMAIL");
        $phpExcel->getActiveSheet()->setCellValue("D1", "MOBILE");
        $phpExcel->getActiveSheet()->setCellValue("E1", "DIRECT");
        $phpExcel->getActiveSheet()->setCellValue("F1", "EXTENSION");
        $phpExcel->getActiveSheet()->setCellValue("G1", "DESIGNATION");
        $phpExcel->getActiveSheet()->setCellValue("H1", "DEPARTMENT");
        $phpExcel->getActiveSheet()->setCellValue("I1", "OFFICE TYPE");
        $phpExcel->getActiveSheet()->setCellValue("J1", "OFFICE BOARD LINE");
        $phpExcel->getActiveSheet()->setCellValue("K1", "OFFICE ADDRESS");
        $phpExcel->getActiveSheet()->setCellValue("L1", "RECRUITMENT CALL STATUS");
        $phpExcel->getActiveSheet()->setCellValue("M1", "STREAM");
        $phpExcel->getActiveSheet()->setCellValue("N1", "COMPANY TYPE");
        $phpExcel->getActiveSheet()->setCellValue("O1", "COMPANY WEBSITE");
        $phpExcel->getActiveSheet()->setCellValue("P1", "EVENT");
        $i = 2;
        $company_name = "";
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
            $event = $row['cont_conclave'];
            if ($event == "") {
                $event = "No Event";
            }
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

            $phpExcel->getActiveSheet()->setCellValue("A" . $i, $name);
            $phpExcel->getActiveSheet()->setCellValue("B" . $i, $cont_status);
            $phpExcel->getActiveSheet()->setCellValue("C" . $i, $email);
            $phpExcel->getActiveSheet()->setCellValue("D" . $i, $mobile);
            $phpExcel->getActiveSheet()->setCellValue("E" . $i, $direct);
            $phpExcel->getActiveSheet()->setCellValue("F" . $i, $extension);
            $phpExcel->getActiveSheet()->setCellValue("G" . $i, $designation);
            $phpExcel->getActiveSheet()->setCellValue("H" . $i, $dept);
            $phpExcel->getActiveSheet()->setCellValue("I" . $i, $offi_type);
            $phpExcel->getActiveSheet()->setCellValue("J" . $i, $offi_boardline);
            $phpExcel->getActiveSheet()->setCellValue("K" . $i, $offi_address);
            $phpExcel->getActiveSheet()->setCellValue("L" . $i, $offi_rec);
            $phpExcel->getActiveSheet()->setCellValue("M" . $i, $stream);
            $phpExcel->getActiveSheet()->setCellValue("N" . $i, $company_type);
            $phpExcel->getActiveSheet()->setCellValue("O" . $i, $company_website);
            $phpExcel->getActiveSheet()->setCellValue("P" . $i, $event);
            $i++;
        }
        $pieces = explode(" ", $company_name);
        $fist_three_word = implode(" ", array_splice($pieces, 0, 3));
        $primary_name = str_replace(".", " ", $fist_three_word);
        if (isset($_POST['hidEmlpId']) && !empty($_POST['hidEmlpId'])) {
            $file_name = 'Content-Disposition: attachment; filename="' . $empl_name . '-Contact List-' . $primary_name . '"';
        } else {
            $file_name = 'Content-Disposition: attachment; filename="Contact List-' . $primary_name . '"';
        }
    }
} 
else if (isset($_POST['hidReportFor']) && $_POST['hidReportFor'] == "company") {
    $company_id = $_POST['hidCompanyId'];

    $contactSql = "SELECT * , comp_name FROM tbl_contact tc, tbl_office tof, tbl_company tcom WHERE tc.offi_id = tof.offi_id and tc.comp_id = tcom.comp_id and tc.comp_id=$company_id order by cont_name asc";
    $contactRes = mysqli_query($con, $contactSql);

    $phpExcel->getActiveSheet()->setCellValue("A1", "NAME");
    $phpExcel->getActiveSheet()->setCellValue("B1", "STATUS");
    $phpExcel->getActiveSheet()->setCellValue("C1", "EMAIL");
    $phpExcel->getActiveSheet()->setCellValue("D1", "MOBILE");
    $phpExcel->getActiveSheet()->setCellValue("E1", "DIRECT");
    $phpExcel->getActiveSheet()->setCellValue("F1", "EXTENSION");
    $phpExcel->getActiveSheet()->setCellValue("G1", "DESIGNATION");
    $phpExcel->getActiveSheet()->setCellValue("H1", "DEPARTMENT");
    $phpExcel->getActiveSheet()->setCellValue("I1", "OFFICE TYPE");
    $phpExcel->getActiveSheet()->setCellValue("J1", "OFFICE BOARD LINE");
    $phpExcel->getActiveSheet()->setCellValue("K1", "OFFICE ADDRESS");
    $phpExcel->getActiveSheet()->setCellValue("L1", "RECRUITMENT CALL STATUS");
    $phpExcel->getActiveSheet()->setCellValue("M1", "STREAM");
    $phpExcel->getActiveSheet()->setCellValue("N1", "COMPANY TYPE");
    $phpExcel->getActiveSheet()->setCellValue("O1", "COMPANY WEBSITE");
    $phpExcel->getActiveSheet()->setCellValue("P1", "EVENT");

    $i = 2;
    $company_name = "";
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
        $event = $row['cont_conclave'];
        if ($event == "") {
            $event = "No Event";
        }
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

        $phpExcel->getActiveSheet()->setCellValue("A" . $i, $name);
        $phpExcel->getActiveSheet()->setCellValue("B" . $i, $cont_status);
        $phpExcel->getActiveSheet()->setCellValue("C" . $i, $email);
        $phpExcel->getActiveSheet()->setCellValue("D" . $i, $mobile);
        $phpExcel->getActiveSheet()->setCellValue("E" . $i, $direct);
        $phpExcel->getActiveSheet()->setCellValue("F" . $i, $extension);
        $phpExcel->getActiveSheet()->setCellValue("G" . $i, $designation);
        $phpExcel->getActiveSheet()->setCellValue("H" . $i, $dept);
        $phpExcel->getActiveSheet()->setCellValue("I" . $i, $offi_type);
        $phpExcel->getActiveSheet()->setCellValue("J" . $i, $offi_boardline);
        $phpExcel->getActiveSheet()->setCellValue("K" . $i, $offi_address);
        $phpExcel->getActiveSheet()->setCellValue("L" . $i, $offi_rec);
        $phpExcel->getActiveSheet()->setCellValue("M" . $i, $stream);
        $phpExcel->getActiveSheet()->setCellValue("N" . $i, $company_type);
        $phpExcel->getActiveSheet()->setCellValue("O" . $i, $company_website);
        $phpExcel->getActiveSheet()->setCellValue("P" . $i, $event);
        $i++;
    }
    $file_name = 'Content-Disposition: attachment; filename="' . $company_name . '-Contact List-Company Wise.xls"';
} 
else if (isset($_POST['hidReportFor']) && $_POST['hidReportFor'] == "stream") {
    $stream_name = $_POST['hidStreamId'];

    $contactSql = "SELECT * , comp_name FROM tbl_contact tc, tbl_office tof, tbl_company tcom, tbl_stream ts WHERE tc.offi_id = tof.offi_id and tc.comp_id = tcom.comp_id and tc.cont_id=ts.cont_id and ts.stream='$stream_name' order by tc.cont_id asc";
    $contactRes = mysqli_query($con, $contactSql);

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
    $phpExcel->getActiveSheet()->setCellValue("Q1", "EVENT");

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
        $event = $row['cont_conclave'];
        if ($event == "") {
            $event = "No Event";
        }
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
        $phpExcel->getActiveSheet()->setCellValue("Q" . $i, $event);
        $i++;
    }
    $file_name = 'Content-Disposition: attachment; filename="' . $stream_name . '-Contact List-Stream Wise.xls"';
} 
else if (isset($_POST['hidReportFor']) && $_POST['hidReportFor'] == "companyStream") {
    $company_id = $_POST['hidContactCompanyId'];
    $stream_name = $_POST['hidContactStreamId'];

    $contactSql = "SELECT * , comp_name FROM tbl_contact tc, tbl_office tof, tbl_company tcom, tbl_stream ts WHERE tc.offi_id = tof.offi_id and tc.comp_id = tcom.comp_id and tc.cont_id=ts.cont_id and ts.stream='$stream_name' and tc.comp_id=$company_id order by tc.cont_id asc";
    $contactRes = mysqli_query($con, $contactSql);

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
    $phpExcel->getActiveSheet()->setCellValue("Q1", "EVENT");

    $i = 2;
    $company_name = "";
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
        $event = $row['cont_conclave'];
        if ($event == "") {
            $event = "No Event";
        }
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
        $phpExcel->getActiveSheet()->setCellValue("Q" . $i, $event);
        $i++;
    }
    $file_name = 'Content-Disposition: attachment; filename="' . $company_name . '-Contact List-' . $stream_name . '.xls"';
} 
else if (isset($_POST['hidReportFor']) && $_POST['hidReportFor'] == "event") {
    $event_value = $_POST['hidEventValue'];
//hidEventValue, event
    $contactSql = "SELECT *, comp_name FROM tbl_contact tc, tbl_office tof, tbl_company tcom WHERE tc.offi_id = tof.offi_id and tc.comp_id = tcom.comp_id and `cont_conclave` LIKE '$event_value'";
    $contactRes = mysqli_query($con, $contactSql);

    $phpExcel->getActiveSheet()->setCellValue("A1", "NAME");
    $phpExcel->getActiveSheet()->setCellValue("B1", "STATUS");
    $phpExcel->getActiveSheet()->setCellValue("C1", "EMAIL");
    $phpExcel->getActiveSheet()->setCellValue("D1", "MOBILE");
    $phpExcel->getActiveSheet()->setCellValue("E1", "DIRECT");
    $phpExcel->getActiveSheet()->setCellValue("F1", "EXTENSION");
    $phpExcel->getActiveSheet()->setCellValue("G1", "DESIGNATION");
    $phpExcel->getActiveSheet()->setCellValue("H1", "DEPARTMENT");
    $phpExcel->getActiveSheet()->setCellValue("I1", "OFFICE TYPE");
    $phpExcel->getActiveSheet()->setCellValue("J1", "OFFICE BOARD LINE");
    $phpExcel->getActiveSheet()->setCellValue("K1", "OFFICE ADDRESS");
    $phpExcel->getActiveSheet()->setCellValue("L1", "RECRUITMENT CALL STATUS");
    $phpExcel->getActiveSheet()->setCellValue("M1", "STREAM");
    $phpExcel->getActiveSheet()->setCellValue("N1", "COMPANY TYPE");
    $phpExcel->getActiveSheet()->setCellValue("O1", "COMPANY WEBSITE");
    $phpExcel->getActiveSheet()->setCellValue("P1", "EVENT");

    $i = 2;
    $company_name = "";
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
        $event = $row['cont_conclave'];
        if ($event == "") {
            $event = "No Event";
        }
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

        $phpExcel->getActiveSheet()->setCellValue("A" . $i, $name);
        $phpExcel->getActiveSheet()->setCellValue("B" . $i, $cont_status);
        $phpExcel->getActiveSheet()->setCellValue("C" . $i, $email);
        $phpExcel->getActiveSheet()->setCellValue("D" . $i, $mobile);
        $phpExcel->getActiveSheet()->setCellValue("E" . $i, $direct);
        $phpExcel->getActiveSheet()->setCellValue("F" . $i, $extension);
        $phpExcel->getActiveSheet()->setCellValue("G" . $i, $designation);
        $phpExcel->getActiveSheet()->setCellValue("H" . $i, $dept);
        $phpExcel->getActiveSheet()->setCellValue("I" . $i, $offi_type);
        $phpExcel->getActiveSheet()->setCellValue("J" . $i, $offi_boardline);
        $phpExcel->getActiveSheet()->setCellValue("K" . $i, $offi_address);
        $phpExcel->getActiveSheet()->setCellValue("L" . $i, $offi_rec);
        $phpExcel->getActiveSheet()->setCellValue("M" . $i, $stream);
        $phpExcel->getActiveSheet()->setCellValue("N" . $i, $company_type);
        $phpExcel->getActiveSheet()->setCellValue("O" . $i, $company_website);
        $phpExcel->getActiveSheet()->setCellValue("P" . $i, $event);
        $i++;
    }
    $file_name = 'Content-Disposition: attachment; filename="' . $event_value . '-Contact List-Event Wise.xls"';
}

$excelWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");
//$excelWriter->save("StudentData-UG.xls");
header('Content-type:application/vnd.ms-excel');
header($file_name);
$excelWriter->save('php://output');
?>