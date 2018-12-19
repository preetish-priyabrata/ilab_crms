<?php
session_start();
if ($_GET['include'] == "style") {
    ?>
    <!-- 
    List of style sheets to be included
    -->
    <!-- CSS Reset -->
    <link rel="stylesheet" type="text/css" href="../../../reset.css" media="screen" />

    <!-- Fluid 960 Grid System - CSS framework -->
    <link rel="stylesheet" type="text/css" href="../../../grid.css" media="screen" />

    <!-- IE Hacks for the Fluid 960 Grid System -->
    <!--[if IE 6]><link rel="stylesheet" type="text/css" href="../../../ie6.css"  media="screen" /><![endif]-->
    <!--[if IE 7]><link rel="stylesheet" type="text/css" href="../../../ie.css" media="screen" /><![endif]-->

    <!-- Main stylesheet -->
    <link rel="stylesheet" type="text/css" href="../../../styles.css"  media="screen" />    
    <style type="text/css">
        table#myTable{
            table-layout: fixed;    
        }
        table#myTable td, table#myTable th{
            overflow: hidden;
            text-overflow: clip;
        }
    </style>
    <?php
}
include_once '../db.php';

$activityDate = $_GET['date'];
$timestamp = strtotime($activityDate);
$formatted_date = date("d-m-Y", $timestamp);
$employeeId = $_GET['empId'];

$columnHeader = '';
$tableBody = '';

$condition = " and tc.cont_id=tec.cont_id and te.empl_id=tec.empl_id and tcompany.comp_id=tec.comp_id ";
if (isset($_GET['date']) && isset($_GET['empId'])) {
    $condition.=" and tec.act_date='$activityDate' and tec.empl_id=$employeeId";
}
// if the user select the "All" option from the calendar drop down 
else {
    $condition.=" and tec.act_date='$activityDate'";
}
//$sql = "select * from tbl_contact tc, tbl_employee te, tbl_emp_contact tec where tc.cont_id=tec.cont_id and te.empl_id=tec.empl_id and tec.cont_id=$contactId";
$sql = "select * from tbl_contact tc, tbl_employee te, tbl_emp_contact tec, tbl_company tcompany where 1 " . $condition;
$res = mysqli_query($con, $sql);
?>
<div class="container_16">
    <div class="grid_12" style="margin-top: 10px;margin-right:0px;width:100%" >

        <div class="module" style="">
            <?php
            if (isset($_GET['empId'])) {
                ?>
                <h2><span>Activity Details on <i>Dt.<u><?php echo $formatted_date; ?></u></i> </span></h2>
                <?php
                $columnHeader = "<tr>
                                    <th style='width: 5%;'>#</th>
                                    <th style='width: 20%;'>Activity Details</th>                        
                                    <th style='width: 20%;'>Activity Type</th>
                                    <th style='width: 20%;'>Corporate Contact</th>
                                    <th style='width: 20%;'>Company Name</th>
                                    <th style='width: 15%;'>Action</th>                            
                                </tr>";
                $i = 1;
                if (mysqli_num_rows($res) > 0) {
                    while ($row = mysqli_fetch_array($res)) {

                        if ($i % 2 == 0) {
                            $rowType = 'class="even"';
                        } else {
                            $rowType = 'class="odd"';
                        }

                        $activityDetail = $row['act_detail'];
                        $activityType = $row['act_type'];
                        $activityContact = $row['cont_name'];
                        $activityCompany = $row['comp_name'];
                        $activity_id = $row['empl_cont_id'];
                        $empl_id = $row[14];

                        if ($_SESSION['empType'] == "Admin" || $_SESSION['empId'] == $empl_id) {
                            $tableBody .= "<tr $rowType>
                                            <td>$i</td>
                                            <td>$activityDetail</td>
                                            <td>$activityType</td>                                            
                                            <td>$activityContact</td>                        
                                            <td>$activityCompany</td>
                                            <td>
                                                <a href='ajax_editActivity.php?activityId=$activity_id'>
                                                    <img src='../../../edit-image.jpg' width='50' alt='Edit Activity Details' title='Edit Activity Details'>
                                                </a>
                                            </td>
                                    </tr>";
                        } else {
                            $tableBody .= "<tr $rowType>
                                            <td>$i</td>
                                            <td>$activityDetail</td>
                                            <td>$activityType</td>                                            
                                            <td>$activityContact</td>                        
                                            <td>$activityCompany</td>
                                            <td></td>
                                    </tr>";
                        }
                        $i++;
                    }
                } else {
                    $tableBody .= "<tr>
                                        <td colspan='5'>No activities found for this date</td>
                                </tr>";
                }
            }
            // if the user select the "All" option from the calendar drop down 
            else {
                ?>
                <h2><span>Activity Details of all CR Member on <i>Dt.<u><?php echo $formatted_date; ?></u></i> </span></h2>
                <?php
                $columnHeader .= "<tr>
                                    <th style='width: 5%;'>#</th>
                                    <th style='width: 20%;'>CR Team Member</th>
                                    <th style='width: 20%;'>Activity Details</th>                                    
                                    <th style='width: 20%;'>Activity Type</th>                                    
                                    <th style='width: 20%;'>Corporate Contact</th>
                                    <th style='width: 15%;'>Action</th>                                    
                                </tr>";
                $i = 1;
                if (mysqli_num_rows($res) > 0) {
                    while ($row = mysqli_fetch_array($res)) {
                        if ($i % 2 == 0) {
                            $rowType = 'class="even"';
                        } else {
                            $rowType = 'class="odd"';
                        }

                        $activityDetail = $row['act_detail'];
                        $employeeName = $row['empl_name'];
                        $activityType = $row['act_type'];
                        $activityContact = $row['cont_name'];
                        $activityCompany = $row['comp_name'];
                        $activity_id = $row['empl_cont_id'];
                        $empl_id = $row[14];

                        if ($_SESSION['empType'] == "Admin" || $_SESSION['empId'] == $empl_id) {
                            $tableBody .= "<tr $rowType>
                                            <td>$i</td>             
                                            <td>$employeeName</td>
                                            <td>$activityDetail</td>                                            
                                            <td>$activityType</td>                                            
                                            <td>$activityCompany<br>($activityContact)</td>
                                            <td>
                                                <a href='ajax_editActivity.php?activityId=$activity_id'>
                                                    <img src='../../../edit-image.jpg' width='50' alt='Edit Activity Details' title='Edit Activity Details'>
                                                </a>
                                            </td>
                                    </tr>";
                        } else {
                            $tableBody .= "<tr $rowType>
                                            <td>$i</td>             
                                            <td>$employeeName</td>
                                            <td>$activityDetail</td>                                            
                                            <td>$activityType</td>                                            
                                            <td>$activityCompany<br>($activityContact)</td>
                                            <td></td>
                                    </tr>";
                        }
                        $i++;
                    }
                } else {
                    $tableBody .= "<tr>
                        <td colspan='5'>No activities found for this date</td>
                    </tr>";
                }
            }
            ?>            
            <div class="module-table-body" style="display:block; ">
                <table id="myTable" class="tablesorter">
                    <?php
                    echo $columnHeader;
                    //echo $sql;
                    echo $tableBody;
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>