<?php

include_once '../db.php';
$sqlStream = "select sett_value from tbl_setting where sett_type='acti_status'";
$resStream = mysqli_query($con, $sqlStream);
$stream = "<option value='NA'>Select a Status</option>";
while ($row = mysqli_fetch_array($resStream)) {
    $stream.="<option>" . $row[0] . "</option>";
}
echo $stream;
?>
