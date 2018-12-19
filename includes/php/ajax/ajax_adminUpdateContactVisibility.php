<?php
include_once '../db.php';

if (isset($_GET['value']) && !empty($_GET['value'])) {
    $value = $_GET['value'];
    $sql = "UPDATE `tbl_setting` SET `sett_value` = '$value' WHERE `tbl_setting`.`sett_type` = 'cont_view'";
    mysqli_query($con, $sql);
    if(mysqli_affected_rows($con)>0){
        echo 'Success';
    }
    else {
        echo 'Fail';
    }
}
?>
