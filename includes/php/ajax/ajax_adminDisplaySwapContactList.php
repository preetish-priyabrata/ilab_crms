<?php
include_once '../db.php';
if (isset($_GET['empId']) && !empty($_GET['empId'])) {
    $empId = $_GET['empId'];
    if ($_GET['task'] == 'displayTable') {
        $sql = "SELECT `cont_id`,`cont_name`,`comp_name`  FROM `tbl_contact` as tcon, `tbl_company` as tcom WHERE tcon.`empl_id`= $empId  and tcon.comp_id=tcom.comp_id order by `cont_name` asc";
        $res = mysqli_query($con, $sql);
        $table_body = "";
        if (mysqli_num_rows($res) > 0) {
            $row_no = 1;            
            while ($row = mysqli_fetch_array($res)) {
                if ($row_no % 2 == 0)
                    $row_color = "odd";
                else
                    $row_color = "even";
                $table_body .= "<tr class='$row_color'><td style='width:5%'><input type='checkbox' value='" . $row[0] . "' onclick='deselectAllSwapCheck();' /></td><td>$row[1]</td><td>$row[2]</td></tr>";
                $row_no++;
            }            
        }
        else {
            $table_body .= "<tr><td colspan='3'><i>No contacts available.</i></td></tr>";
        }

        $sql1 = "SELECT `empl_name` FROM `tbl_employee` WHERE `empl_id`= $empId";
        $res1 = mysqli_query($con, $sql1);
        $row1 = mysqli_fetch_array($res1);
        echo "<div style='padding-right: 20%;'>
                  <input type='text' id='txtContactSearchBox' class='input-short' style='width: 35%;float: right;' placeholder='Enter contact name to search' onkeyup='searchSwapContact(this.value);'>
            </div>
            <div class = 'module' style='width: 80%;'>
                <h2><span>Contacts added by $row1[0]</span></h2>
                <div class = 'module-table-body' style='display: block; height: 300px; overflow-y: auto;'>  
                <form action = '' name='frmContactLst'>
                    <table class = 'tablesorter' >
                        <thead>
                            <tr>
                                 <th style='width:5%'><input type='checkbox' name='chkCheckSelector' id='chkCheckSelector' onclick='empSwapChkAll();' title='Check All' /> </th>
                                 <th style='width:45%'>Contact Name</th>
                                 <th style='width:50%'>Company Name</th>
                            </tr>
                        </thead>
                        <tbody id='swapEmpList'>                            
                                $table_body                           
                        </tbody>
                    <table> 
             </form>
                </div> <!--End .module-table-body-->
            </div>";
    } else if ($_GET['task'] == 'fillEmployeeList') {
        $sql = "SELECT `empl_id`,`empl_name` FROM `tbl_employee` order by empl_name asc";
        $res = mysqli_query($con, $sql);

        if (mysqli_num_rows($res) > 0) {
            echo "<option value='NA'>Select an Employee</option>";
            while ($row = mysqli_fetch_array($res)) {
                if ($row[0] == $empId) {
                    continue;
                } else {
                    echo "<option value='$row[0]'>$row[1]</option>";
                }
            }
        } else {
            echo "<option value='NA' disabled>No Employee Found</option>";
        }
    }
} 
?>
