<?php
session_start();
//contactId=923&include=style&contactName=Ms.%20Richa%20Tiwari&compName=a2z%20Infotech%20Pvt.%20Ltd.
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
$contactId = $_GET['contactId'];
$contactName = $_GET['contactName'];
$todoEndDate = $_GET['date'];
$employeeId = $_SESSION['empId'];

$condition = '';
if (isset($_GET[contactId])) {
    $condition .= " and cnt.cont_id=$contactId ";
}
if (isset($_GET['date'])) {
    $condition .= " and todo.todo_endDate='$todoEndDate' and todo.empl_id=$employeeId ";
}

$compName = $_GET['compName'];
$sql = "SELECT * FROM tbl_todo AS todo LEFT OUTER JOIN tbl_contact AS cnt ON todo.cont_id = cnt.cont_id LEFT OUTER JOIN tbl_company AS cmp ON cnt.comp_id = cmp.comp_id LEFT OUTER JOIN tbl_employee AS te ON te.empl_id = todo.empl_id WHERE 1 $condition ";
$res = mysqli_query($con, $sql);
?>
<div class="container_16">
    <div class="grid_12" style="margin-top: 10px;margin-right:0px;width:100%" >

        <div class="module" style="">
            <?php
            if (isset($_GET['contactId'])) {
                ?>
                <h2><span>Schedule / Reminder Details for <i><u><?php echo $contactName ?></u></i> of <i><u><?php echo $compName ?></u></i> company</span></h2>
                <div class="module-table-body" style="display:block; ">
                    <table id="myTable" class="tablesorter">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 20%;">Schedule Details</th>
                            <th style="width: 15%;">Schedule Type</th>
                            <th style="width: 20%;">CR Team Member</th>
                            <th style="width: 15%;">Closing Date</th>
                            <th style="width: 15%;">Schedule Status</th>
                            <th style="width: 10%;">Action</th>                            
                        </tr>
                        <?php
                        //echo $sql;
                        $i = 1;
                        if (mysqli_num_rows($res) > 0) {
                            while ($row = mysqli_fetch_array($res)) {
                                $todo_id = $row['todo_id'];
                                $empl_id = $row['empl_id'];
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
                                        <td><?php echo $row['empl_name']; ?></td>
                                        <td>
                                            <?php
                                            $formatted_date = date("d-m-Y", strtotime($row['todo_endDate']));
                                            echo $formatted_date;
                                            ?>                                        
                                        </td>
                                        <td><?php echo $row['todo_status']; ?></td>
                                        <td>
                                            <a href='ajax_editTodo.php?todoId=<?php echo $todo_id; ?>&contName=<?php echo $row['cont_name']; ?>&compName=<?php echo $row['comp_name']; ?>'>
                                                <img src='../../../edit-image.jpg' width='50' alt='Edit Activity Details' title='Edit Activity Details'>
                                            </a>
                                        </td>
                                    <?php } else { ?>
                                        <td><?php echo $i ?></td>
                                        <td><?php echo $row['todo_detail']; ?></td>
                                        <td><?php echo $row['todo_type']; ?></td>
                                        <td><?php echo $row['empl_name']; ?></td>
                                        <td>
                                            <?php
                                            $formatted_date = date("d-m-Y", strtotime($row['todo_endDate']));
                                            echo $formatted_date;
                                            ?>                                        
                                        </td>
                                        <td><?php echo $row['todo_status']; ?></td>
                                        <td></td>
                                    <?php } ?>
                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="6">No Schedules / Reminders found for this contact</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
                <?php
            }
            if (isset($_GET['date'])) {
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
                                $empl_id = $row['empl_id'];
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
                                <td colspan="6">No Schedules / Reminders found for this contact</td>
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