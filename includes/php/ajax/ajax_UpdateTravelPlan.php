<?php
include_once '../db.php';
session_start();
if (isset($_POST) && !empty($_POST)) {    
    $startDate = mysqli_real_escape_string($con, $_POST['startDate']);
    $endDate = mysqli_real_escape_string($con, $_POST['endDate']);
    $destination = mysqli_real_escape_string($con, $_POST['destination']);
    $desc = mysqli_real_escape_string($con, $_POST['description']);
    $travelId = ($_POST['travelId']); 
    $startTime = mysqli_real_escape_string($con, $_POST['startTime']);
    $endTime = mysqli_real_escape_string($con, $_POST['endTime']);    
    
    $start_date_time = $startDate." ".$startTime;
    $end_date_time = $endDate." ".$endTime;

    //form date in mysql date format
    $frmtdStartDate = date("Y-m-d H:i:s", strtotime($start_date_time));

    //to date in mysql date format
    $frmtdEndDate = date("Y-m-d H:i:s", strtotime($end_date_time));            
    
    // this if statement check wheather the start time is less than the current time
//    if (strtotime($startDate) < strtotime(date("Y-m-d H:i:s"))) {
//        echo "Wrong Start Date";
//    }
//    else if (strtotime($startDate) > strtotime($endDate)) {
    if (strtotime($start_date_time) >= strtotime($end_date_time)) {
        echo "Wrong Date";
    } else {
        $updateSql = "UPDATE `tbl_travel` SET `trav_start_date` = '$frmtdStartDate', `trav_end_date` = '$frmtdEndDate', `sett_id` = '$destination', `trav_desc` = '$desc' WHERE `tbl_travel`.`trav_id` = $travelId;";
        $updateRes = mysqli_query($con, $updateSql);
        if (mysqli_affected_rows($con)>0) {
            echo "Success";
        } else {
            echo "Fail";
        }
    }
}
