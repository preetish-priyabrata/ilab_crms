<?php
include_once '../db.php';

if (isset($_POST['btnAddDistrict']) && !empty($_POST['btnAddDistrict'])) {                    
    $city = mysqli_real_escape_string($con, $_POST['city']);
    $state = mysqli_real_escape_string($con, $_POST['state']);
    $country = $_POST['country'];    
    $id = $_POST['hidAddrId'];
        
$sql = "UPDATE `tbl_address` SET `addr_city` = '$city', `addr_state` = '$state', `addr_country` = '$country' WHERE `tbl_address`.`addr_id` = $id;";

    $update_res = mysqli_query($con, $sql);

    if ($update_res) {
        echo "Success";
    } else {
        echo "Fail";
    }
}
?>
