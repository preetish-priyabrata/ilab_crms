<?php

include_once '../db.php';

if (isset($_GET['eventId']) && !empty($_GET['eventId'])) {
    $eventId = $_GET['eventId'];

    $sql = "SELECT `evet_link` FROM `tbl_event` WHERE `evet_id` = $eventId ";
    $res = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($res);
    $fileToDel = $row[0];

    if ($fileToDel == "") {
        $sql1 = "DELETE FROM `tbl_event` WHERE `evet_id`=$eventId";
        $res1 = mysqli_query($con, $sql1);
        if ($res1)
            echo "Success";
        else
            echo "Fail";
    }
    else {
        //echo getcwd() . "<br>";
        chdir('../../../images/notice/');
        //echo getcwd() . "<br>";
        $do = unlink($fileToDel); 
        if ($do != 1) {
            echo "Fail";
        } else {
            $sql1 = "DELETE FROM `tbl_event` WHERE `evet_id`=$eventId";
            $res1 = mysqli_query($con, $sql1);
            if ($res1)
                echo "Success";
            else
                echo "Fail";
        }
    }
}
?>
