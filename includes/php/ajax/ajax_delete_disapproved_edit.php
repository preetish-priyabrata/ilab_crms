<?php

include_once '../db.php';

if (isset($_GET['editId']) && !empty($_GET['editId'])) {
    $edit_id = $_GET['editId'];
    $delete_edit_status_sql = "DELETE FROM `tbl_cont_edit_status` WHERE `edit_id`=$edit_id";
    $delete_edit_status_res = mysqli_query($con, $delete_edit_status_sql);
    if($delete_edit_status_res){
        echo "Success";
    }else{
        echo "Failed";
    }
}

