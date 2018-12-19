<?php
include_once '../db.php';
session_start();
if (isset($_POST) && !empty($_POST)) {
    $startDate = mysqli_real_escape_string($con, $_POST['startDate']);
    $endDate = mysqli_real_escape_string($con, $_POST['endDate']);
    $destination = mysqli_real_escape_string($con, $_POST['destination']);
    $desc = mysqli_real_escape_string($con, $_POST['description']);
    $startTime = mysqli_real_escape_string($con, $_POST['startTime']);
    $endTime = mysqli_real_escape_string($con, $_POST['endTime']);    
    
    $start_date_time = $startDate." ".$startTime;
    $end_date_time = $endDate." ".$endTime;

    $empId = $_SESSION['empId'];

    //form date in mysql date format
    $frmtdStartDate = date("Y-m-d H:i:s", strtotime($start_date_time));

    //to date in mysql date format
    $frmtdEndDate = date("Y-m-d H:i:s", strtotime($end_date_time));            
    
    // if the selected time is less than current time
//    if (strtotime($start_date_time) < strtotime(date("Y-m-d H:i:s"))) {
//        echo "Wrong Start Date";
//    }
//    else if (strtotime($start_date_time) > strtotime($end_date_time)) {
    // if the end time is larger than start time display "Wrong Date"
    if (strtotime($start_date_time) >= strtotime($end_date_time)) {
        echo "Wrong Date";
    } else {
        $insertSql = "INSERT INTO `tbl_travel` (`trav_id`, `trav_start_date`, `trav_end_date`, `sett_id`, `trav_desc`, `empl_id`) VALUES (NULL, '$frmtdStartDate', '$frmtdEndDate', '$destination', '$desc', '$empId');";
        $insertRes = mysqli_query($con, $insertSql);
        if ($insertRes) {
            echo "Success";
        } else {
            echo "Fail";
        }
    }
}
