<?php
include_once '../db.php';
$country = $_GET['country'];
if($country == "India")
    $sqlAddress = "select addr_city from tbl_address where addr_country='India'";
else {
    $sqlAddress = "select addr_city from tbl_address where addr_country != 'India'";
}
$resAddress = mysqli_query($con, $sqlAddress);
$city = "";
if(mysqli_num_rows($resAddress)>0)
while ($row = mysqli_fetch_array($resAddress)) {
    $city.="<option>" . $row[0] . "</option>";
}else{
    $city="No City Available";
}
echo $city;
?>