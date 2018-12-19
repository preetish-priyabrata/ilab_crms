<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../db.php';
$sql = "select sett_value from tbl_setting where sett_type='func_dept' and sett_status='Active'";
    $res = mysqli_query($con, $sql);
echo "<option value='NA'>Select a Department</option>";
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_array($res)) {
                echo "<option value='$row[0]'>$row[0]</option>";
            }
        } else {
            echo "<option value='NA'>No Department Found</option>";
        }
?>
