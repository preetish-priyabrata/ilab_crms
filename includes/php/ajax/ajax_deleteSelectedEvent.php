<?php

include_once '../db.php';
if (isset($_GET['selectedEventId']) && !empty($_GET['selectedEventId'])) {
    $eventList = $_GET['selectedEventId'];
    //$qryDelete = "DELETE FROM authors WHERE AuthorNo IN($authors)"; 
    $sql = "SELECT `evet_id`,`evet_link` FROM `tbl_event` WHERE `evet_id` in($eventList) ";
    $res = mysqli_query($con, $sql);
    chdir('../../../images/notice/');
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_array($res)) {
            if ($row[1] == "") {
                $sql1 = "DELETE FROM `tbl_event` WHERE `evet_id`=$row[0]";
                $res1 = mysqli_query($con, $sql1);
            } else {
                $fileToDel = $row[1];
                $do = unlink($fileToDel);
                if ($do != 1) {
                    
                } else {
                    $sql1 = "DELETE FROM `tbl_event` WHERE `evet_id`=$row[0]";
                    $res1 = mysqli_query($con, $sql1);
                }
            }
        }
        echo "Success";
    }
    else{
        echo "Fail";
    }
}
?>
