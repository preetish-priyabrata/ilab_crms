<?php
include_once '../db.php';;

if(isset($_GET['id'])){
    $addrId = $_GET['id'];
    
    $sql = "delete from tbl_address where addr_id = $addrId";
    $res = mysqli_query($con, $sql);
    if($res){
        echo "Success";
    }
    else {
        echo "Fail";
    }
}

?>
