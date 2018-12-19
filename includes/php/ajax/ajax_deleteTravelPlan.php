<?php
include_once '../db.php';;

if(isset($_GET['travelId'])){
    $travelId = $_GET['travelId'];
    
    $sql = "DELETE FROM `tbl_travel` WHERE `trav_id`=$travelId";
    $res = mysqli_query($con, $sql);
    if($res){
        echo "Success";
    }
    else {
        echo "Fail";
    }
}

?>
