<?php

include_once '../db.php';
session_start();

// while adding travel plan this if section is executed because here "travelId" is not sent though the URL
if ((isset($_GET['startDate']) && !empty($_GET['startDate'])) && (!isset($_GET['travelId']))) {
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];
    $startTime = mysqli_real_escape_string($con, $_GET['startTime']);
    $endTime = mysqli_real_escape_string($con, $_GET['endTime']);
    $start_date_time = $startDate . " " . $startTime;
    $end_date_time = $endDate . " " . $endTime;

    $formattedStartDate = date("Y-m-d H:i:s", strtotime($start_date_time));
    $formattedEndDate = date("Y-m-d H:i:s", strtotime($end_date_time));
    $destination = $_GET['destination'];
    $empId = $_SESSION['empId'];

    $startDateSql = "SELECT `trav_id`,`trav_end_date` FROM `tbl_travel` WHERE '$formattedStartDate' between `trav_start_date` and `trav_end_date` and empl_id = $empId";
    $startDateRes = mysqli_query($con, $startDateSql);
    $startDateNumRow = mysqli_num_rows($startDateRes);
    if ($startDateNumRow == 0) {
        $endDateSql = "SELECT `trav_id`,`trav_start_date` FROM `tbl_travel` WHERE '$formattedEndDate' between `trav_start_date` and `trav_end_date` and empl_id = $empId";
        $endDateRes = mysqli_query($con, $endDateSql);
        $endDateNumRow = mysqli_num_rows($endDateRes);
        if ($endDateNumRow == 0) {
            $invalidDateRangeSql = "SELECT `trav_id` FROM `tbl_travel` WHERE `trav_start_date` between '$formattedStartDate' and '$formattedEndDate' and empl_id = $empId";
            $invalidDateRangeRes = mysqli_query($con, $invalidDateRangeSql);
            $invalidDateRangeNum = mysqli_num_rows($invalidDateRangeRes);
            if ($invalidDateRangeNum == 0) {
                $destSql = "SELECT `trav_id`, sett_value FROM `tbl_travel` as tt, `tbl_setting` as ts WHERE (('$formattedEndDate' between `trav_start_date` and `trav_end_date` or '$formattedStartDate' between `trav_start_date` and `trav_end_date`) or (`trav_start_date` between '$formattedStartDate' and '$formattedEndDate')) and tt.sett_id = $destination and tt.sett_id=ts.sett_id";
                $destRes = mysqli_query($con, $destSql);
                $destRow = mysqli_fetch_array($destRes);
                $destNumRows = mysqli_num_rows($destRes);
                if ($destNumRows > 0) {
                    if ($destNumRows == 1) {
                        echo "$destNumRows CR Member already associated with this travel plan to $destRow[1]. Are you sure to make this Travel Plan?";
                    } else {
                        echo "$destNumRows CR Members already associated with this travel plan to $destRow[1]. Are you sure to make this Travel Plan?";
                    }
                }
            } else {
                echo "Range Between";
            }
        } else {
            // this section is to check whether the start date time is equal to the entered start date time        
            $end_date_counter = 0;
            while ($endDateRow = mysqli_fetch_array($endDateRes)) {
                $end_date = $endDateRow[1];                
                if ($end_date == $formattedEndDate) {
                    $end_date_counter++;
                }
            }
            // if the end date counter is equal to zero then there is no row equal to the end date time so display the error message
            if ($end_date_counter == 0) {
                echo "End Date";
            }
        }
    } else {
        // this section is to check whether the start date time is equal to the entered start date time        
        $start_date_counter = 0;
        while ($startDateRow = mysqli_fetch_array($startDateRes)) {
            $start_date = $startDateRow[1];
            if ($start_date == $formattedStartDate) {
                $start_date_counter++;
            }
        }
        // if the start date counter is equal to zero then there is no row equal to the start date time so display the error message
        if ($start_date_counter == 0) {
            echo "Start Date";
        }
    }
}

// while updating the travel plan this if section is executed because here in the URL the "travelId" parameter is sent along with the URL
if ((isset($_GET['startDate']) && !empty($_GET['startDate'])) && (isset($_GET['travelId']) && !empty($_GET['travelId']))) {
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];
    $startTime = mysqli_real_escape_string($con, $_GET['startTime']);
    $endTime = mysqli_real_escape_string($con, $_GET['endTime']);
    $start_date_time = $startDate . " " . $startTime;
    $end_date_time = $endDate . " " . $endTime;

    $formattedStartDate = date("Y-m-d", strtotime($start_date_time));
    $formattedEndDate = date("Y-m-d", strtotime($end_date_time));
    $destination = $_GET['destination'];
    $empId = $_SESSION['empId'];
    $travel_id = $_GET['travelId'];

    $startDateSql = "SELECT `trav_id`,`trav_end_date` FROM `tbl_travel` WHERE '$formattedStartDate' between `trav_start_date` and `trav_end_date` and empl_id = $empId and `trav_id`!=$travel_id;";
    $startDateRes = mysqli_query($con, $startDateSql);
    $startDateNumRow = mysqli_num_rows($startDateRes);
    if ($startDateNumRow == 0) {
        $endDateSql = "SELECT `trav_id`,`trav_start_date` FROM `tbl_travel` WHERE '$formattedEndDate' between `trav_start_date` and `trav_end_date` and empl_id = $empId and `trav_id`!=$travel_id;";
        $endDateRes = mysqli_query($con, $endDateSql);
        $endDateNumRow = mysqli_num_rows($endDateRes);
        if ($endDateNumRow == 0) {
            $invalidDateRangeSql = "SELECT `trav_id` FROM `tbl_travel` WHERE `trav_start_date` between '$formattedStartDate' and '$formattedEndDate' and empl_id = $empId and `trav_id`!=$travel_id;";
            $invalidDateRangeRes = mysqli_query($con, $invalidDateRangeSql);
            $invalidDateRangeNum = mysqli_num_rows($invalidDateRangeRes);
            if ($invalidDateRangeNum == 0) {
                $destSql = "SELECT `trav_id`, sett_value FROM `tbl_travel` as tt, `tbl_setting` as ts WHERE (('$formattedEndDate' between `trav_start_date` and `trav_end_date` or '$formattedStartDate' between `trav_start_date` and `trav_end_date`) or (`trav_start_date` between '$formattedStartDate' and '$formattedEndDate')) and tt.sett_id = $destination and tt.sett_id=ts.sett_id and `trav_id`!=$travel_id;";
                $destRes = mysqli_query($con, $destSql);
                $destRow = mysqli_fetch_array($destRes);
                $destNumRows = mysqli_num_rows($destRes);
                if ($destNumRows > 0) {
                    if ($destNumRows == 1) {
                        echo "$destNumRows CR Member already associated with this travel plan to $destRow[1]. Are you sure to make this Travel Plan?";
                    } else {
                        echo "$destNumRows CR Members already associated with this travel plan to $destRow[1]. Are you sure to make this Travel Plan?";
                    }
                }
            } else {
                echo "Range Between";
            }
        } else {
           // this section is to check whether the start date time is equal to the entered start date time        
            $end_date_counter = 0;
            while ($endDateRow = mysqli_fetch_array($endDateRes)) {
                $end_date = $endDateRow[1];
                if ($end_date == $formattedEndDate) {
                    $end_date_counter++;
                }
            }
            // if the end date counter is equal to zero then there is no row equal to the end date time so display the error message
            if ($end_date_counter == 0) {
                echo "End Date";
            }
        }
    } else {
        // this section is to check whether the start date time is equal to the entered start date time        
        $start_date_counter = 0;
        while ($startDateRow = mysqli_fetch_array($startDateRes)) {
            $start_date = $startDateRow[1];
            if ($start_date == $formattedStartDate) {
                $start_date_counter++;
            }
        }
        // if the start date counter is equal to zero then there is no row equal to the start date time so display the error message
        if ($start_date_counter == 0) {
            echo "Start Date";
        }
    }
}
