<?php

include_once '../db.php';
session_start();
$empId = $_SESSION['empId'];

$sql = "select comp_id, comp_name from tbl_company where comp_id in(select distinct(tcom.comp_id) from tbl_company tcom, tbl_contact tcon where tcom.comp_id=tcon.comp_id and tcon.empl_id=$empId ) order by comp_name";
$rs = mysqli_query($con, $sql);
echo "<option value='NA'>Select a Company</option>";
while ($row = mysqli_fetch_array($rs)) {
    echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
}
?>