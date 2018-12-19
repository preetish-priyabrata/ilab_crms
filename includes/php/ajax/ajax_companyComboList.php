<?php

include_once '../db.php';
if (isset($_GET['compLst']) && !empty($_GET['compLst'])) {
    $sql = "select comp_id, comp_name from tbl_company order by comp_name ";
    $rs = mysqli_query($con, $sql);
    echo "<option value='NA'>Select a Company</option>";
    while ($row = mysqli_fetch_array($rs)) {
        echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
    }
}else{
    $sql = "select comp_id, comp_name from tbl_company order by comp_name ";
    $rs = mysqli_query($con, $sql);
    echo "<option value='NA'>Select a Company</option>";
    while ($row = mysqli_fetch_array($rs)) {
        echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
    }
}
?>