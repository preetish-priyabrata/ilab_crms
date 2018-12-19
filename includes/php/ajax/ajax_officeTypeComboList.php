<?php

include_once '../db.php';
$sqlOfficeType = "select sett_value from tbl_setting where sett_type='offi_type'";
$resOfficeType = mysqli_query($con, $sqlOfficeType);
$officeType = "";
while ($row = mysqli_fetch_array($resOfficeType)) {
    $officeType.="<option>" . $row[0] . "</option>";
}
echo $officeType;
?>
