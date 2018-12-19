<?php

include_once '../db.php';

if (isset($_POST['btnAddDistrict']) && !empty($_POST['btnAddDistrict'])) {
    $city = mysqli_real_escape_string($con, $_POST['city']);
    $state = mysqli_real_escape_string($con, $_POST['state']);
    $country = $_POST['country'];

    $sql = "INSERT INTO `tbl_address` (`addr_id`, `addr_city`, `addr_state`, `addr_country`) VALUES (NULL, '$city', '$state', '$country');";

    $insert_res = mysqli_query($con, $sql);

    if ($insert_res) {
        echo "Success";
    } else {
        echo "Fail";
    }
}
?>