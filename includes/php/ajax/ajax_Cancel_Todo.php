<?php

include_once '../db.php';

if (isset($_GET['todoId']) && !empty($_GET['todoId'])) {

    $comment = $_GET['comment'];
    $todoId = $_GET['todoId'];
    $sql = "UPDATE `tbl_todo` SET `todo_status` = 'Cancelled', `todo_comment` = '$comment' WHERE `tbl_todo`.`todo_id` = $todoId;";

    $update_res = mysqli_query($con, $sql);

    if ($update_res) {
        echo "Success";
    } else {
        echo "Fail";
    }
}
?>
