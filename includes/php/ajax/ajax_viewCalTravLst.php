
<!-- CSS Reset -->
<link rel="stylesheet" type="text/css" href="../../../reset.css" media="screen" />

<!-- Fluid 960 Grid System - CSS framework -->
<link rel="stylesheet" type="text/css" href="../../../grid.css" media="screen" />

<!-- IE Hacks for the Fluid 960 Grid System -->
<!--[if IE 6]><link rel="stylesheet" type="text/css" href="../../../ie6.css"  media="screen" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" href="../../../ie.css" media="screen" /><![endif]-->

<!-- Main stylesheet -->
<link rel="stylesheet" type="text/css" href="../../../styles.css"  media="screen" />
<?php
include_once '../db.php';
$tableBody = '';
$tableHeading = '';

$empId = $_GET['empId'];
$travelDate = $_GET['date'];

$travel_date_day_num_start_time = "$travelDate 00:05:00";
$travel_date_day_num_end_time = "$travelDate 23:55:55";

$travId = $_GET['travelId'];

$emplSql = "SELECT `empl_name` FROM `tbl_employee` WHERE `empl_id`=$empId ";
$emplRes = mysqli_query($con, $emplSql);
$emplRow = mysqli_fetch_array($emplRes);
$emplName = $emplRow[0];
$headingDate = date("d-m-Y", strtotime($travelDate));

if (isset($_GET['travelId']) && !empty($_GET['travelId']) && isset($_GET['empId']) && !empty($_GET['empId'])) {
    $whereClause = " trav_id = $travId";
    $tableHeading = "<h2><span>Travel Plan of <i><u>$emplName</u></i></span></h2>";
    $orderByClause = "";
    $headingLst = "<th style='width:5%'>#</th>
                    <th style='width:17%'>Start Date</th>
                    <th style='width:17%'>End Date</th>
                    <th style='width:21%'>Destination</th>
                    <th style='width:40%'>Description</th>";
} else if (isset($_GET['empId']) && !empty($_GET['empId']) && isset($_GET['date']) && !empty($_GET['date'])) {
    $whereClause = " (((`trav_start_date` between '$travel_date_day_num_start_time' and '$travel_date_day_num_end_time') or (`trav_end_date` between '$travel_date_day_num_start_time' and '$travel_date_day_num_end_time')) or (('$travel_date_day_num_start_time' between `trav_start_date` and `trav_end_date`) or ('$travel_date_day_num_end_time' between `trav_start_date` and `trav_end_date`))) and tt.empl_id = $empId";
    $tableHeading = "<h2><span>Travel Plan on <i><u>$headingDate</u></i> of <i><u>$emplName</u></i></span></h2>";
    $orderByClause = "";
    $headingLst = "<th style='width:5%'>#</th>
                    <th style='width:17%'>Start Date</th>
                    <th style='width:17%'>End Date</th>
                    <th style='width:21%'>Destination</th>
                    <th style='width:40%'>Description</th>";
} else {
    $whereClause = " (((`trav_start_date` between '$travel_date_day_num_start_time' and '$travel_date_day_num_end_time') or (`trav_end_date` between '$travel_date_day_num_start_time' and '$travel_date_day_num_end_time')) or (('$travel_date_day_num_start_time' between `trav_start_date` and `trav_end_date`) or ('$travel_date_day_num_end_time' between `trav_start_date` and `trav_end_date`)))";
    $orderByClause = " order by ts.sett_value asc";
    $tableHeading = "<h2><span>Travel Plan on <i><u>$headingDate</u></i></span></h2>";
    $headingLst = "<th style='width:5%'>#</th>
                    <th style='width:20%'>Employee Name</th>
                    <th style='width:15%'>Start Date</th>
                    <th style='width:15%'>End Date</th>
                    <th style='width:20%'>Destination</th>
                    <th style='width:25%'>Description</th>";
}
$sql = "select trav_id, trav_start_date, trav_end_date, sett_value, trav_desc, empl_name from tbl_travel as tt, tbl_setting as ts, tbl_employee as te where $whereClause and tt.sett_id=ts.sett_id and tt.empl_id=te.empl_id";
$res = mysqli_query($con, $sql);
?>
<div class="container_16">
    <div class="grid_12" style="margin-top: 10px;margin-right:0px;width:100%" >

        <div class="module" style="">                        
            <?php
            echo $tableHeading;
            $i = 1;
            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_array($res)) {
                    if ($i % 2 == 0) {
                        $rowType = 'class="even"';
                    } else {
                        $rowType = 'class="odd"';
                    }

                    $travStartDate = date("d-m-Y", strtotime($row['trav_start_date']));
                    $travEndDate = date("d-m-Y", strtotime($row['trav_end_date']));
                    $travStartTime = date("h:i A",  strtotime($row['trav_start_date']));
                    $travEndTime = date("h:i A",  strtotime($row['trav_end_date']));
                    
                    $travDestination = $row['sett_value'];
                    $travDescription = $row['trav_desc'];
                    $employeeName = $row['empl_name'];
                    if (isset($_GET['travelId']) && !empty($_GET['travelId']) || isset($_GET['empId']) && !empty($_GET['empId'])) {
                        $tableBody .= "<tr $rowType>
                                            <td>$i</td>
                                            <td>$travStartDate<br>($travStartTime)</td>
                                            <td>$travEndDate<br>($travEndTime)</td>
                                            <td>$travDestination</td>
                                            <td>$travDescription</td>
                                    </tr>";
                    } else {
                        $tableBody .= "<tr $rowType>
                                            <td>$i</td>
                                            <td>$employeeName</td>
                                            <td>$travStartDate</td>
                                            <td>$travEndDate</td>
                                            <td>$travDestination</td>
                                            <td>$travDescription</td>
                                    </tr>";
                    }
                    $i++;
                }
            } else {
                if (isset($_GET['travelId']) && !empty($_GET['travelId']) || isset($_GET['empId']) && !empty($_GET['empId']))
                    $tableBody .= "<tr>
                        <td colspan='5'>No Travelling Plan found for this date</td>
                    </tr>";
                else
                    $tableBody .= "<tr>
                        <td colspan='6'>No Travelling Plan found for this date</td>
                    </tr>";
            }
            ?>            
            <div class="module-table-body" style="display:block; ">
                <table id="myTable" class="tablesorter">
                    <tr>
                        <?php
                        echo $headingLst;
                        ?>
                    </tr>
                    <?php
                    echo $tableBody;
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>