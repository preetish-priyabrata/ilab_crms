<?php
include_once '../db.php';
if(isset($_GET['eventId']) && !empty($_GET['eventId'])){
    $eventId = $_GET['eventId'];
    $status = $_GET['eventStatus'];
    if($status == "Active")
        $status = "Inactive";
    else
        $status = "Active";
    $sql = "UPDATE `tbl_event` SET `evet_status` = '$status' WHERE `tbl_event`.`evet_id` = $eventId;";
    $res = mysqli_query($con, $sql);
    if(mysqli_affected_rows($con)>0){
        echo "Success";
    }
    else
        echo "Fail";
}
?>
