<?php

include_once './db.php';
include_once '../excel/Classes/PHPExcel.php';
if (isset($_GET['empId']) && !empty($_GET['empId'])) {
    $empId = $_GET['empId'];
    $empName = $_GET['name'];
    $fromDateTS = $_GET['startDate'];
    $toDateTS = $_GET['endDate'];

    $sqlTravel = "SELECT `trav_start_date`,`trav_end_date`,`sett_value`,DATEDIFF(`trav_end_date` , `trav_start_date`) total_day, trav_desc "
            . "FROM `tbl_travel` tt, tbl_setting ts WHERE `empl_id`=$empId and "
            . "(((`trav_start_date` between '$fromDateTS' and '$toDateTS') or (`trav_end_date` between '$fromDateTS' and '$toDateTS')) or (('$fromDateTS' between `trav_start_date` and `trav_end_date`) or ('$toDateTS' between `trav_start_date` and `trav_end_date`))) "
            . "and tt.`sett_id`=ts.`sett_id`";
    $resTravel = mysqli_query($con, $sqlTravel);

    $phpExcel = new PHPExcel();
    $phpExcel->createSheet();

    $phpExcel->getActiveSheet()->setCellValue("A1", "DESTINATION");
    $phpExcel->getActiveSheet()->setCellValue("B1", "START DATE");
    $phpExcel->getActiveSheet()->setCellValue("C1", "START DATE");
    $phpExcel->getActiveSheet()->setCellValue("D1", "END DATE");
    $phpExcel->getActiveSheet()->setCellValue("E1", "START DATE");
    $phpExcel->getActiveSheet()->setCellValue("F1", "TRAVEL TIME");
    $phpExcel->getActiveSheet()->setCellValue("G1", "DESCRIPTION");

    $i = 2;
    $totalDay = 0;
    while ($row = mysqli_fetch_array($resTravel)) {
        $startDate = date("d-m-Y", strtotime($row[0]));
        $startTime = date("h:i A", strtotime($row[0]));
        $endDate = date("d-m-Y", strtotime($row[1]));
        $endTime = date("h:i A", strtotime($row[1]));

        // create the datetime object
        $startTimeObj = date_create($row[0]);
        $endTimeObj = date_create($row[1]);

        //Below comment section only work on PHP 5.3 or above
        //get time interval using date_diff function
        $time_interval = date_diff($startTimeObj, $endTimeObj);

        // get time interval using intval function
        $seconds = intval($time_interval->format('%s'));
        $minutes = intval($time_interval->format('%i'));
        $hours = intval($time_interval->format('%H'));
        $days = intval($time_interval->format('%d'));
        $months = intval($time_interval->format('%M'));
        $years = intval($time_interval->format('%Y'));

        // this section displays the time interval in a proper format
        $interval = "";
        if ($years < 1) {
            if ($months < 1) {
                if ($days < 1) {
                    if ($hours < 1) {
                        if ($minutes == 1) {
                            $interval = "$minutes minute";
                        } else {
                            $interval = "$minutes minutes";
                        }
                    } else {
                        if ($hours == 1) {
                            $interval = "$hours hour $minutes minutes";
                        } else {
                            $interval = "$hours hours $minutes minutes";
                        }
                    }
                } else {
                    if ($days == 1) {
                        $interval = "$days day $hours hours $minutes minutes";
                    } else {
                        $interval = "$days days $hours hours $minutes minutes";
                    }
                }
            } else {
                if ($months == 1) {
                    $interval = "$months month $days days $hours hours $minutes minutes";
                } else {
                    $interval = "$months months $days days $hours hours $minutes minutes";
                }
            }
        } else {
            if ($years == 1) {
                $interval = "$years year $months month $days days $hours hours $minutes minutes";
            } else {
                $interval = "$years years $months month $days days $hours hours $minutes minutes";
            }
        }

        $phpExcel->getActiveSheet()->setCellValue("A" . $i, $row['sett_value']);
        $phpExcel->getActiveSheet()->setCellValue("B" . $i, $startDate);
        $phpExcel->getActiveSheet()->setCellValue("C" . $i, $startTime);
        $phpExcel->getActiveSheet()->setCellValue("D" . $i, $endDate);
        $phpExcel->getActiveSheet()->setCellValue("E" . $i, $endTime);
        $phpExcel->getActiveSheet()->setCellValue("F" . $i, $interval);
        $phpExcel->getActiveSheet()->setCellValue("G" . $i, $row['trav_desc']);
        $i++;
        $second_interval = strtotime($row[1]) - strtotime($row[0]);
        $total_time_second += $second_interval;
    }
    // $total_time_second to year, month, day, hour, minute, second calculator
    $secondsInAMinute = 60;
    $secondsInAnHour = 60 * $secondsInAMinute;
    $secondsInADay = 24 * $secondsInAnHour;
    $secondsInAMonth = 30 * $secondsInADay;
    $secondsInAYear = 12 * $secondsInAMonth;

    $total_years = floor($total_time_second / $secondsInAYear);

    $monthSeconds = $total_time_second % $secondsInAYear;
    $total_months = floor($monthSeconds / $secondsInAMonth);

    $daySeconds = $monthSeconds % $secondsInAMonth;
    $total_days = floor($daySeconds / $secondsInADay);

    $hourSeconds = $daySeconds % $secondsInADay;
    $total_hours = floor($hourSeconds / $secondsInAnHour);

    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $total_minutes = floor($minuteSeconds / $secondsInAMinute);

    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $total_seconds = ceil($remainingSeconds);
    // this section displays the total time interval in a proper format
    $total_interval = "";
    if ($total_years < 1) {
        if ($total_months < 1) {
            if ($total_days < 1) {
                if ($total_hours < 1) {
                    if ($total_minutes == 1) {
                        $total_interval = "$total_minutes minute";
                    } else {
                        $total_interval = "$total_minutes minutes";
                    }
                } else {
                    if ($total_hours == 1) {
                        $total_interval = "$total_hours hour $total_minutes minutes";
                    } else {
                        $total_interval = "$total_hours hours $total_minutes minutes";
                    }
                }
            } else {
                if ($total_days == 1) {
                    $total_interval = "$total_days day $total_hours hours $total_minutes minutes";
                } else {
                    $total_interval = "$total_days days $total_hours hours $total_minutes minutes";
                }
            }
        } else {
            if ($total_months == 1) {
                $total_interval = "$total_months month $total_days days $total_hours hours $total_minutes minutes";
            } else {
                $total_interval = "$total_months months $total_days days $total_hours hours $total_minutes minutes";
            }
        }
    } else {
        if ($total_years == 1) {
            $total_interval = "$total_years year $total_months month $total_days days $total_hours hours $total_minutes minutes";
        } else {
            $total_interval = "$total_years years $total_months month $total_days days $total_hours hours $total_minutes minutes";
        }
    }
    $phpExcel->getActiveSheet()->setCellValue("E" . $i, "Total Travel Time:");
    $phpExcel->getActiveSheet()->setCellValue("F" . $i, $total_interval);

    $excelWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");
    $file_name = "$empName - TravelReport.xls";
    //$excelWriter->save("StudentData-UG.xls");
    header('Content-type:application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename=' . $file_name);
    $excelWriter->save('php://output');
}

