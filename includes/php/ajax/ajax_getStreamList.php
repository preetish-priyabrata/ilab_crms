<label>Select a Stream</label>
<?php
include_once '../db.php';
$sql = "SELECT `sett_value` FROM `tbl_setting` WHERE `sett_type`='stream'";
$res = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($res)) {
    echo "<input type='checkbox' name='chkStream[]' value='$row[0]' checked> $row[0]&nbsp;&nbsp;";
}
?>



