<?php

include_once '../db.php';
//empId=' + empId+'&startDate='+startDate+'&endDate='+endDate
if (isset($_GET['empId']) && !empty($_GET['empId'])) {
    $empId = $_GET['empId'];
    $empName = $_GET['name'];
    $fromDate = strtotime($_GET['startDate']);
    $toDate = strtotime($_GET['endDate']);
    if ($fromDate > $toDate) {
        echo "wrong date";
    } else {
        $fromDateTS = strtotime(date("Y-m-d", $fromDate));
        $toDateTS = strtotime(date("Y-m-d", $toDate));

        $body = "";
        $totalLoginTime = 0;
        $totalMeeting = 0;
        $totalTelephone = 0;
        $totalEmail = 0;
        for ($i = 1, $currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24), $i++) {
            if ($i % 2 == 0) {
                $rowColor = "class='even'";
            } else {
                $rowColor = "class='odd'";
            }
            $body .= "<tr $rowColor><td>$i</td>";
            $currentDateStr = date("Y-m-d", $currentDateTS);
            $currentDate = date("d-m-Y", $currentDateTS);
            $body .= "<td>$currentDate</td>";
            $sqlTotalLoginTime = "SELECT sum(TIMEDIFF( `logout`,`login` )) AS loginTime FROM `tbl_login` WHERE `empl_id` =$empId and login_date = '$currentDateStr'";
            $resTotalLoginTime = mysqli_query($con, $sqlTotalLoginTime);
            $rowTotalLoginTime = mysqli_fetch_array($resTotalLoginTime);
            $loginTime = $rowTotalLoginTime[0];
            $totalLoginTime += $loginTime;
            if ($loginTime == 0)
                $body .= "<td>" . gmdate("H:i:s", $loginTime) . " hr.</td>";
            else
                $body .= "<td style='font-weight: bold;'>" . gmdate("H:i:s", $loginTime) . " hr.</td>";
            $sqlTotalMeeting = "SELECT COUNT(*) FROM tbl_todo WHERE todo_type = 'Meeting' AND `empl_id` =$empId and todo_date = '$currentDateStr'";
            $resTotalMeeting = mysqli_query($con, $sqlTotalMeeting);
            $rowTotalMeeting = mysqli_fetch_array($resTotalMeeting);
            $meeting = $rowTotalMeeting[0];
            $totalMeeting += $meeting;
            if ($meeting == 0)
                $body .= "<td>$meeting</td>";
            else
                $body .= "<td style='font-weight: bold;'>$meeting</td>";
            $sqlTotalPhone = "SELECT COUNT(*) FROM tbl_todo WHERE todo_type = 'Telephone / Conversation' AND `empl_id` =$empId and todo_date = '$currentDateStr'";
            $resTotalPhone = mysqli_query($con, $sqlTotalPhone);
            $rowTotalPhone = mysqli_fetch_array($resTotalPhone);
            $phone = $rowTotalPhone[0];
            $totalTelephone += $phone;
            if ($phone == 0)
                $body .= "<td>$phone</td>";
            else
                $body .= "<td style='font-weight: bold;'>$phone</td>";
            $sqlTotalEmail = "SELECT COUNT(*) FROM tbl_todo WHERE todo_type = 'Email' AND `empl_id` =$empId and todo_date = '$currentDateStr'";
            $resTotalEmail = mysqli_query($con, $sqlTotalEmail);
            $rowTotalEmail = mysqli_fetch_array($resTotalEmail);
            $email = $rowTotalEmail[0];
            $totalEmail += $email;
            if ($email == 0)
                $body .= "<td>$email</td></tr>";
            else
                $body .= "<td style='font-weight: bold;'>$email</td></tr>";
        }
        $body .= "<tr style='font-weight: bold;border-top: 1px solid #d9d9d9;'><td></td><td style='text-align: right;'>Total:</td><td>" . gmdate("H:i:s", $totalLoginTime) . " hr.</td><td>$totalMeeting</td><td>$totalTelephone</td><td>$totalEmail</td></tr>";
        echo "<div class='module'>
            <h2><span>$empName - Performance Report</span></h2>
            <div class='module-table-body' style='display: block; height: 300px; overflow-y: auto;'>        
                    <table id='myTable' class='tablesorter'>
                        <thead>
                            <tr>
                                <th style='width:5%'>#</th><th style='width:20%'>Date</th><th style='width:18.75%'>Login Time</th><th style='width:18.75%'>Meeting</th><th style='width:18.75%'>Telephone</th><th style='width:18.75%'>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                        $body
                        </tbody>
                    </table>        
                </div>                 
            </div>
            <form method='get' action='includes/php/downloadEmpPerfExcelReport.php'>
                <input type='hidden' name='empId' value='$empId' />
                <input type='hidden' name='name' value='$empName' />
                <input type='hidden' name='startDate' value='$fromDateTS' />
                <input type='hidden' name='endDate' value='$toDateTS' />
                <center><input class='submit-green' type='submit' name='btnDownload' value='Download' /></center>"
        . "</form>";
    }
}
?>