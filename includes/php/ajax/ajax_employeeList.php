<?php
include_once '../db.php';

$sql = "SELECT `empl_id`,`empl_name` FROM `tbl_employee` order by empl_name asc";
$res = mysqli_query($con, $sql);

if (mysqli_num_rows($res) > 0) {
    echo "<option value='NA'>Select an Employee</option>";
    while ($row = mysqli_fetch_array($res)) {
        echo "<option value='$row[0]'>$row[1]</option>";
    }
} else {
    echo "<option value='NA' disabled>No Employee Found</option>";
}
?>