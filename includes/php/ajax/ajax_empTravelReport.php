<?php

include_once '../db.php';
//empId=' + empId+'&startDate='+startDate+'&endDate='+endDate
if (isset($_GET['empId']) && !empty($_GET['empId'])) {
    $empId = $_GET['empId'];
    $empName = $_GET['name'];
    $fromDate = $_GET['startDate'];
    $toDate = $_GET['endDate'];
    $startTime = $_GET['startTime'];
    $endTime = $_GET['endTime'];

    $from_date_time = $fromDate . " " . $startTime;
    $to_date_time = $toDate . " " . $endTime;

    $fromDateTS = date("Y-m-d H:i:s", strtotime($from_date_time));
    $toDateTS = date("Y-m-d H:i:s", strtotime($to_date_time));

    if (strtotime($from_date_time) >= strtotime($to_date_time)) {
        echo "wrong date";
    } else {
        $sqlTravel = "SELECT `trav_start_date`,`trav_end_date`,`sett_value`,DATEDIFF( `trav_end_date` , `trav_start_date` ) total_day FROM `tbl_travel` tt, tbl_setting ts WHERE `empl_id`=$empId and (((`trav_start_date` between '$fromDateTS' and '$toDateTS') or (`trav_end_date` between '$fromDateTS' and '$toDateTS')) or (('$fromDateTS' between `trav_start_date` and `trav_end_date`) or ('$toDateTS' between `trav_start_date` and `trav_end_date`))) and tt.`sett_id`=ts.`sett_id`";
        $resTravel = mysqli_query($con, $sqlTravel);
        $i = 1;
        $body = "";
        $download_button = "";
        $totalDay = 0;
        if (mysqli_num_rows($resTravel) > 0) {
            while ($row = mysqli_fetch_array($resTravel)) {
                if ($i % 2 == 0) {
                    $rowColor = "class='even'";
                } else {
                    $rowColor = "class='odd'";
                }
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

                $body .= "<tr $rowColor><td>$i</td>"
                        . "<td>$row[2]</td>"
                        . "<td>$startDate<br>($startTime)</td>"
                        . "<td>$endDate<br>($endTime)</td>"
                        . "<td>$interval</td>";
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

            $body .= "<tr style='font-weight: bold;border-top: 1px solid #d9d9d9;'><td></td><td></td><td></td><td>Total Travel Time:</td><td>$total_interval</td></tr>";
            $download_button = "<form method='get' action='includes/php/downloadTravelExcelReport.php'>
                                    <input type='hidden' name='empId' value='$empId' />
                                    <input type='hidden' name='name' value='$empName' />
                                    <input type='hidden' name='startDate' value='$fromDateTS' />
                                    <input type='hidden' name='endDate' value='$toDateTS' />
                                    <center><input class='submit-green' type='submit' name='btnDownload' value='Download' /></center>"
                            .  "</form>";
        } else {
            $body .= "<tr style='font-weight: bold;border-top: 1px solid #d9d9d9;'><td colspan='5'>No Travel Plan available.</td></tr>";
            $download_button = "";
        }
        echo "<div class='module'>
            <h2><span>$empName - Travel Report</span></h2>
            <div class='module-table-body' style='display: block; height: 300px; overflow-y: auto;'>        
                    <table id='myTable' class='tablesorter'>
                        <thead>
                            <tr>
                                <th style='width:5%'>#</th><th style='width:25%'>Destination</th><th style='width:25%'>Start Date</th><th style='width:25%'>End Date</th><th style='width:20%'>Travel Time</th>
                            </tr>
                        </thead>
                        <tbody>
                        $body
                        </tbody>
                    </table>        
                </div>                 
            </div>
            $download_button;
            ";
    }
}


