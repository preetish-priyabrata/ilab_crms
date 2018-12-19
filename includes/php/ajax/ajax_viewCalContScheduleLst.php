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
    <link rel="stylesheet" type="text/css" media="all" href="../../../css/jsDatePick_ltr.min.css" />    
    <!-- Datepicker JavaScript -->
    <script type="text/javascript" src="../../../js/jsDatePick.min.1.3.js"></script>
    <script type="text/javascript">
        var showDatePicker = function(field) {
            //alert("Mayank");
            new JsDatePick({
                useMode: 2,
                target: field,
                dateFormat: "%d-%m-%Y"
            });
        };
    </script> 
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
$todoEndDate = $_GET['date'];
$employeeId = $_GET['empId'];

// If the user select a employee except the "All" option
if (isset($_GET['empId'])) {
    $condition = '';
    if (isset($_GET['date']) && !isset($_GET['type'])) {
        $condition .= " and todo.todo_endDate='$todoEndDate' and todo.empl_id=$employeeId and (todo_type='Email' or todo_type='Telephone / Conversation')";
    }
    if (isset($_GET['date']) && isset($_GET['type'])) {
        $condition .= " and todo.todo_endDate='$todoEndDate' and todo.empl_id=$employeeId and todo_type='Meeting'";
    }
    $compName = $_GET['compName'];
    $sql = "SELECT * "
            . "FROM tbl_todo AS todo "
            . "LEFT OUTER JOIN tbl_contact AS cnt ON todo.cont_id = cnt.cont_id "
            . "LEFT OUTER JOIN tbl_company AS cmp ON cnt.comp_id = cmp.comp_id "
            . "WHERE 1 $condition ";
    $res = mysqli_query($con, $sql);
    ?>
    <div class="container_16">
        <div class="grid_12" style="margin-top: 10px;margin-right:0px;width:100%" >
            <div class="module" style="">
                <?php
                // if date is set and type is not set i.e. only for email and phone type reminder
                if (isset($_GET['date']) && !isset($_GET['type'])) {
                    $timestamp = strtotime($todoEndDate);
                    $formatted_date = date("d-m-Y", $timestamp);
                    ?>
                    <h2><span>Schedule / Reminder closing on <i>Dt.<u><?php echo $formatted_date; ?></u></i> </span></h2>
                    <div class="module-table-body" style="display:block; ">
                        <table id="myTable" class="tablesorter">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 20%;">Schedule Details</th>
                                <th style="width: 15%;">Schedule Type</th>                                                        
                                <th style="width: 20%;">Schedule Status</th>
                                <th style="width: 15%;">Corporate Contact</th>
                                <th style="width: 15%;">Company Name</th>
                                <th style="width: 10%;">Action</th>
                            </tr>
                            <?php
                            //echo $sql;
                            $i = 1;
                            if (mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_array($res)) {
                                    $todo_id = $row['todo_id'];
                                    $empl_id = $row[7];
                                    if ($i % 2 == 0) {
                                        $rowType = 'class="even"';
                                    } else {
                                        $rowType = 'class="odd"';
                                    }
                                    ?>
                                    <tr <?php echo $rowType; ?>>
                                        <?php if ($_SESSION['empType'] == "Admin" || $_SESSION['empId'] == $empl_id) { ?>
                                            <td><?php echo $i ?></td>
                                            <td><?php echo $row['todo_detail']; ?></td>
                                            <td><?php echo $row['todo_type']; ?></td>
                                            <td><?php echo $row['todo_status']; ?></td>
                                            <td><?php echo $row['cont_name']; ?></td>
                                            <td><?php echo $row['comp_name']; ?></td>
                                            <td>
                                                <a href='ajax_editTodo.php?todoId=<?php echo $todo_id; ?>&contName=<?php echo $row['cont_name']; ?>&compName=<?php echo $row['comp_name']; ?>'>
                                                    <img src='../../../edit-image.jpg' width='50' alt='Edit Activity Details' title='Edit Activity Details'>
                                                </a>
                                            </td>
                                        <?php } else {
                                            ?>
                                            <td><?php echo $i ?></td>
                                            <td><?php echo $row['todo_detail']; ?></td>
                                            <td><?php echo $row['todo_type']; ?></td>
                                            <td><?php echo $row['todo_status']; ?></td>
                                            <td><?php echo $row['cont_name']; ?></td>
                                            <td><?php echo $row['comp_name']; ?></td>
                                            <td></td>
                                        <?php }
                                        ?>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6">No Schedules / Reminders found for this date</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                    <?php
                }
                // if date is set and type is set i.e. only for meeting type reminder
                if (isset($_GET['date']) && isset($_GET['type'])) {
                    $timestamp = strtotime($todoEndDate);
                    $formatted_date = date("d-m-Y", $timestamp);
                    ?>
                    <h2><span>Appointment closing on <i>Dt.<u><?php echo $formatted_date; ?></u></i> </span></h2>
                    <div class="module-table-body" style="display:block; ">
                        <table id="myTable" class="tablesorter">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 20%;">Appointment Details</th>
                                <th style="width: 15%;">Appointment Type</th>                                                        
                                <th style="width: 20%;">Appointment Status</th>
                                <th style="width: 15%;">Corporate Contact</th>
                                <th style="width: 15%;">Company Name</th>
                                <th style="width: 10%;">Action</th>                                
                            </tr>
                            <?php
                            //echo $sql;
                            $i = 1;
                            if (mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_array($res)) {
                                    $todo_id = $row['todo_id'];
                                    $empl_id = $row[7];
                                    if ($i % 2 == 0) {
                                        $rowType = 'class="even"';
                                    } else {
                                        $rowType = 'class="odd"';
                                    }
                                    ?>
                                    <tr <?php echo $rowType; ?>>
                                        <?php if ($_SESSION['empType'] == "Admin" || $_SESSION['empId'] == $empl_id) { ?>
                                            <td><?php echo $i ?></td>
                                            <td><?php echo $row['todo_detail']; ?></td>
                                            <td><?php echo $row['todo_type']; ?></td>
                                            <td><?php echo $row['todo_status']; ?></td>
                                            <td><?php echo $row['cont_name']; ?></td>
                                            <td><?php echo $row['comp_name']; ?></td>
                                            <td>
                                                <a href='ajax_editTodo.php?todoId=<?php echo $todo_id; ?>&contName=<?php echo $row['cont_name']; ?>&compName=<?php echo $row['comp_name']; ?>'>
                                                    <img src='../../../edit-image.jpg' width='50' alt='Edit Activity Details' title='Edit Activity Details'>
                                                </a>
                                            </td>
                                        <?php } else {
                                            ?>
                                            <td><?php echo $i ?></td>
                                            <td><?php echo $row['todo_detail']; ?></td>
                                            <td><?php echo $row['todo_type']; ?></td>
                                            <td><?php echo $row['todo_status']; ?></td>
                                            <td><?php echo $row['cont_name']; ?></td>
                                            <td><?php echo $row['comp_name']; ?></td>
                                            <td></td>
                                        <?php }
                                        ?>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6">No Appointment found for this date</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                    <?php
                }
                ?>                
            </div>
        </div>
    </div>
    <?php
}
// if the user select the "All" option from the calendar drop down 
else {
    $condition = '';
    if (isset($_GET['date']) && !isset($_GET['type'])) {
        $condition .= " and todo.todo_endDate='$todoEndDate' and (todo_type='Email' or todo_type='Telephone / Conversation')";
    }
    if (isset($_GET['date']) && isset($_GET['type'])) {
        $condition .= " and todo.todo_endDate='$todoEndDate' and todo_type='Meeting'";
    }
    $compName = $_GET['compName'];
    $sql = "SELECT * "
            . "FROM tbl_todo AS todo "
            . "LEFT OUTER JOIN tbl_contact AS cnt ON todo.cont_id = cnt.cont_id "
            . "LEFT OUTER JOIN tbl_company AS cmp ON cnt.comp_id = cmp.comp_id "
            . "LEFT OUTER JOIN tbl_employee AS emp ON emp.empl_id = todo.empl_id "
            . "WHERE 1 $condition ";
    $res = mysqli_query($con, $sql);
    ?>
    <div class="container_16">
        <div class="grid_12" style="margin-top: 10px;margin-right:0px;width:100%" >
            <div class="module" style="">
                <?php
                // if date is set and type is not set i.e. only for email and phone type reminder of all employee
                if (isset($_GET['date']) && !isset($_GET['type'])) {
                    $timestamp = strtotime($todoEndDate);
                    $formatted_date = date("d-m-Y", $timestamp);
                    ?>
                    <h2><span>Schedule / Reminder of all CR Member closing on <i>Dt.<u><?php echo $formatted_date; ?></u></i> </span></h2>
                    <div class="module-table-body" style="display:block; ">
                        <table id="myTable" class="tablesorter">
                            <tr>                                
                                <th>#</th>
                                <th>CR Team Member</th>
                                <th>Schedule Details</th>
                                <th>Schedule Type</th>                                                                
                                <th>Corporate Contact</th>
                                <th>Schedule Status</th> 
                                <th>Action</th>
                            </tr>
                            <?php
                            //echo $sql;
                            $i = 1;
                            if (mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_array($res)) {
                                    $todo_id = $row['todo_id'];
                                    $empl_id = $row[7];
                                    if ($i % 2 == 0) {
                                        $rowType = 'class="even"';
                                    } else {
                                        $rowType = 'class="odd"';
                                    }
                                    $employeeName = $row['empl_name'];
                                    $corporateContact = $row['comp_name'] . "(" . $row['cont_name'] . ")";
                                    ?>
                                    <tr <?php echo $rowType; ?>>
                                        <?php if ($_SESSION['empType'] == "Admin" || $_SESSION['empId'] == $empl_id) { ?>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $employeeName; ?></td>
                                            <td><?php echo $row['todo_detail']; ?></td>
                                            <td><?php echo $row['todo_type']; ?></td>                                        
                                            <td><?php echo $corporateContact; ?></td>
                                            <td><?php echo $row['todo_status']; ?></td>
                                            <td>
                                                <a href='ajax_editTodo.php?todoId=<?php echo $todo_id; ?>&contName=<?php echo $row['cont_name']; ?>&compName=<?php echo $row['comp_name']; ?>'>
                                                    <img src='../../../edit-image.jpg' width='50' alt='Edit Activity Details' title='Edit Activity Details'>
                                                </a>
                                            </td>
                                        <?php } else {
                                            ?>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $employeeName; ?></td>
                                            <td><?php echo $row['todo_detail']; ?></td>
                                            <td><?php echo $row['todo_type']; ?></td>                                        
                                            <td><?php echo $corporateContact; ?></td>
                                            <td><?php echo $row['todo_status']; ?></td>
                                            <td></td>
                                        <?php }
                                        ?>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6">No Schedules / Reminders found for this date</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                    <?php
                }

                if (isset($_GET['date']) && isset($_GET['type'])) {
                    $timestamp = strtotime($todoEndDate);
                    $formatted_date = date("d-m-Y", $timestamp);
                    ?>
                    <h2><span>Appointment closing on <i>Dt.<u><?php echo $formatted_date; ?></u></i> </span></h2>
                    <div class="module-table-body" style="display:block; ">
                        <table id="myTable" class="tablesorter">
                            <tr>                                
                                <th>#</th>
                                <th>CR Team Member</th>
                                <th>Appointment Details</th>
                                <th>Appointment Type</th>                                                                
                                <th>Corporate Contact</th>
                                <th>Appointment Status</th> 
                                <th>Action</th>
                            </tr>
                            <?php
                            //echo $sql;
                            $i = 1;
                            if (mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_array($res)) {
                                    $todo_id = $row['todo_id'];
                                    $empl_id = $row[7];
                                    if ($i % 2 == 0) {
                                        $rowType = 'class="even"';
                                    } else {
                                        $rowType = 'class="odd"';
                                    }
                                    $employeeName = $row['empl_name'];
                                    $corporateContact = $row['comp_name'] . "(" . $row['cont_name'] . ")";
                                    ?>
                                    <tr <?php echo $rowType; ?>>
                                        <?php if ($_SESSION['empType'] == "Admin" || $_SESSION['empId'] == $empl_id) { ?>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $employeeName; ?></td>
                                            <td><?php echo $row['todo_detail']; ?></td>
                                            <td><?php echo $row['todo_type']; ?></td>                                        
                                            <td><?php echo $corporateContact; ?></td>
                                            <td><?php echo $row['todo_status']; ?></td>  
                                            <td>
                                                <a href='ajax_editTodo.php?todoId=<?php echo $todo_id; ?>&contName=<?php echo $row['cont_name']; ?>&compName=<?php echo $row['comp_name']; ?>'>
                                                    <img src='../../../edit-image.jpg' width='50' alt='Edit Activity Details' title='Edit Activity Details'>
                                                </a>
                                            </td>
                                        <?php } else {
                                            ?>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $employeeName; ?></td>
                                            <td><?php echo $row['todo_detail']; ?></td>
                                            <td><?php echo $row['todo_type']; ?></td>                                        
                                            <td><?php echo $corporateContact; ?></td>
                                            <td><?php echo $row['todo_status']; ?></td>
                                            <td></td>
                                        <?php }
                                        ?>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6">No Appointment found for this date</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}