<?php
include_once './db.php';
include_once '../excel/Classes/PHPExcel.php';
if (isset($_GET['empId']) && !empty($_GET['empId'])) {
    $empId = $_GET['empId'];
    $empName = $_GET['name'];
    $fromDateTS = $_GET['startDate'];
    $toDateTS = $_GET['endDate'];
    $phpExcel = new PHPExcel();
    $phpExcel->createSheet();

    $phpExcel->getActiveSheet()->setCellValue("A1", "DATE");
    $phpExcel->getActiveSheet()->setCellValue("B1", "LOGIN TIME");
    $phpExcel->getActiveSheet()->setCellValue("C1", "MEETING");
    $phpExcel->getActiveSheet()->setCellValue("D1", "TELEPHONE");
    $phpExcel->getActiveSheet()->setCellValue("E1", "EMAIL");
    $count = 0;
    for ($i = 2, $currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24), $i++, $count = $i) {
        $currentDateStr = date("Y-m-d", $currentDateTS);
        $currentDate = date("d-m-Y", $currentDateTS);
        //DATE 
        $phpExcel->getActiveSheet()->setCellValue("A" . $i, $currentDate);
        //LOGIN TIME
        $sqlTotalLoginTime = "SELECT sum(TIMEDIFF( `logout`,`login` )) AS loginTime FROM `tbl_login` WHERE `empl_id` =$empId and login_date = '$currentDateStr'";
        $resTotalLoginTime = mysqli_query($con, $sqlTotalLoginTime);
        $rowTotalLoginTime = mysqli_fetch_array($resTotalLoginTime);
        $loginTime = $rowTotalLoginTime[0];
        $totalLoginTime += $loginTime;
        $loginTime = gmdate("H:i:s", $loginTime);
        $phpExcel->getActiveSheet()->setCellValue("B" . $i, $loginTime);
        //MEETING
        $sqlTotalMeeting = "SELECT COUNT(*) FROM tbl_todo WHERE todo_type = 'Meeting' AND `empl_id` =$empId and todo_date = '$currentDateStr'";
        $resTotalMeeting = mysqli_query($con, $sqlTotalMeeting);
        $rowTotalMeeting = mysqli_fetch_array($resTotalMeeting);
        $meeting = $rowTotalMeeting[0];
        $totalMeeting += $meeting;
        $phpExcel->getActiveSheet()->setCellValue("C" . $i, $meeting);
        //TELEPHONE
        $sqlTotalPhone = "SELECT COUNT(*) FROM tbl_todo WHERE todo_type = 'Telephone / Conversation' AND `empl_id` =$empId and todo_date = '$currentDateStr'";
        $resTotalPhone = mysqli_query($con, $sqlTotalPhone);
        $rowTotalPhone = mysqli_fetch_array($resTotalPhone);
        $phone = $rowTotalPhone[0];
        $totalTelephone += $phone;
        $phpExcel->getActiveSheet()->setCellValue("D" . $i, $phone);
        //EMAIL
        $sqlTotalEmail = "SELECT COUNT(*) FROM tbl_todo WHERE todo_type = 'Email' AND `empl_id` =$empId and todo_date = '$currentDateStr'";
        $resTotalEmail = mysqli_query($con, $sqlTotalEmail);
        $rowTotalEmail = mysqli_fetch_array($resTotalEmail);
        $email = $rowTotalEmail[0];
        $totalEmail += $email;
        $phpExcel->getActiveSheet()->setCellValue("E" . $i, $email);
    }
    $totalLoginTime = gmdate("H:i:s", $totalLoginTime);
    $phpExcel->getActiveSheet()->setCellValue("A" . $count, "TOTAL:");
    $phpExcel->getActiveSheet()->setCellValue("B" . $count, $totalLoginTime);
    $phpExcel->getActiveSheet()->setCellValue("C" . $count, $totalMeeting);
    $phpExcel->getActiveSheet()->setCellValue("D" . $count, $totalTelephone);
    $phpExcel->getActiveSheet()->setCellValue("E" . $count, $totalEmail);


    $excelWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");
//$excelWriter->save("StudentData-UG.xls");
    header('Content-type:application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="EmployeePerformanceReport.xls"');
    $excelWriter->save('php://output');
}
?>