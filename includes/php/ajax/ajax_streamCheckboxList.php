<?php

include_once '../db.php';
$sqlStream = "select sett_value from tbl_setting where sett_type='stream'";
$resStream = mysqli_query($con, $sqlStream);
echo "<table style='border:0px;'>";
$i = 1;
while ($row = mysqli_fetch_array($resStream)) {
    echo "<tr><td style='border:0px;'><input type='checkbox' value='" . $row[0] . "' name='stream' id='stream$i' onclick='displayPackage($i);'/> " . $row[0] . "</td><td style='border:0px;'><input type='text' palceholder='Enter the package here' placeholder='Enter Offered Salary for $row[0] here' name='txtPackage' id='txtPackage$i' style='display:none;width: 100%'></td></tr>";
    $i++;
}
echo "</table>";
?>
