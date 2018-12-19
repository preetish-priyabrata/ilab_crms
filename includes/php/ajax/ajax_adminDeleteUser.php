<?php
require_once '../db.php';;

if(isset($_GET['userId'])){
    $userId = $_GET['userId'];
    
    $sql = "DELETE FROM `tbl_employee` WHERE `empl_id` = $userId";
    $res = mysqli_query($con, $sql);
    if(mysqli_affected_rows($con)>0){
        echo "Success";
    }
    else {
        echo "Fail";
    }
}

?>
