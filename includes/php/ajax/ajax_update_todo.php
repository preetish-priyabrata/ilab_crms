<?php

include_once '../db.php';

if (isset($_POST['updateBtn']) && !empty($_POST['updateBtn'])) {
    $closeDay = mysqli_real_escape_string($con, $_POST['closingDate']);
    $desc = mysqli_real_escape_string($con, $_POST['description']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $contactId = mysqli_real_escape_string($con, $_POST['contact']);
    $todoId = $_POST['todoId'];

    $closeDayDay = substr($closeDay, 0, 2);
    $closeDayMonth = substr($closeDay, 3, 2);
    $closeDayYear = substr($closeDay, 6, 4);
    $closeDay = $closeDayYear . "-" . $closeDayMonth . "-" . $closeDayDay;

    if (strtotime(date('d-m-Y')) > strtotime($closeDay)) {
        echo "Wrong Date";
    } else {
        $sql = "UPDATE `tbl_todo` SET `todo_endDate` = '$closeDay', `todo_detail` = '$desc', `todo_status` = '$status', `cont_id` = '$contactId' WHERE `tbl_todo`.`todo_id` = $todoId;";
        $res = mysqli_query($con, $sql);
        if ($res) {
            echo "Success";
        } else {
            echo "Fail";
        }
    }
}
if(isset($_POST['action'])&&!empty($_POST['action'])){
    $todo_id = $_POST['todo_id'];
    $sql_update = "update tbl_todo set todo_status='Closed' where todo_id=$todo_id";
    mysqli_query($con, $sql_update);
    if(mysqli_affected_rows($con)>0){
        echo "Success";
    }
    else{
        echo "Fail";
    }
}
?>
