<?php
include_once '../db.php';;

if(isset($_GET['id'])){
    $companyId = $_GET['id'];
    
    $sql = "DELETE FROM `tbl_company` WHERE `comp_id` = $companyId";
    $res = mysqli_query($con, $sql);
    if($res){
        echo "Success";
    }
    else {
        echo "Fail";
    }
}

?>
