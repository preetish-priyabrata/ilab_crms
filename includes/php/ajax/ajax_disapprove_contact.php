<?php

include_once '../db.php';

if (isset($_GET['comment']) && !empty($_GET['comment'])) {
    $contact_id = $_GET['contactId'];
    $category = $_GET['category'];
    $comment = $_GET['comment'];

    $update_flag = FALSE;
    if (strpos($category, 'Personal Details') !== FALSE) {
        $delete_contact_sql = "DELETE FROM `tbl_contact_temp` WHERE `cont_id`=$contact_id";
        $delete_contact_res = mysqli_query($con, $delete_contact_sql);
        if ($delete_contact_res) {
            $update_flag = TRUE;
        }
    }

    if (strpos($category, 'Office Details') !== FALSE) {
        $delete_office = "DELETE FROM `tbl_office_temp` WHERE `cont_id`=$contact_id";
        $delete_res = mysqli_query($con, $delete_office);
        if ($delete_res) {
            $update_flag = TRUE;
        }
    }
    if (strpos($category, 'Other Details') !== FALSE) {
        $stream_delete_sql = "DELETE FROM `tbl_stream_temp` WHERE `cont_id`=$contact_id";
        $stream_delete_res = mysqli_query($con, $stream_delete_sql);
        
        // check and delete the the row if there is any new conclave update request is there.
        $conclave_sql = "SELECT `cont_conclave` FROM `tbl_contact_temp` WHERE `cont_id`=$contact_id";
        $conclave_res = mysqli_query($con, $conclave_sql);
        $conclave_delete_res = FALSE;
        if (mysqli_num_rows($conclave_res) > 0) {
            $conclave_delete_sql = "DELETE FROM `tbl_contact_temp` WHERE `cont_id`=$contact_id";
            mysqli_query($con, $conclave_delete_sql);
            if (mysqli_affected_rows($con)) {
                $conclave_delete_res = true;
            }
        }
        if ($stream_delete_res || $conclave_delete_res) {
            $update_flag = TRUE;
        }
    }
    if ($update_flag) {
        $edit_status_update_sql = "UPDATE `tbl_cont_edit_status` SET `edit_status` = 'Disapproved', `edit_comment` = '$comment' WHERE `tbl_cont_edit_status`.`cont_id` = $contact_id and `tbl_cont_edit_status`.`edit_status` = 'Pending';";
        $edit_status_update_res = mysqli_query($con, $edit_status_update_sql);
        if (mysqli_affected_rows($con) > 0) {
            echo "Success";
        } else {
            echo "Failed";
        }
    }
}
    