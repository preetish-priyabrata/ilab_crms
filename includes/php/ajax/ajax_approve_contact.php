<?php

include_once '../db.php';

if (isset($_GET['contactId']) && !empty($_GET['contactId'])) {
    $contact_id = $_GET['contactId'];

    $edit_status_sql = "SELECT * FROM `tbl_cont_edit_status` WHERE `cont_id`=$contact_id ";
    $edit_status_res = mysqli_query($con, $edit_status_sql);
    $status_row = mysqli_fetch_array($edit_status_res);

    $category = $status_row[1];
    $company_id = $status_row[6];

    $update_flag = FALSE;
    if (strpos($category, 'Personal Details') !== FALSE) {
        $contact_sql = "select * from tbl_contact_temp where cont_id = $contact_id";
        $contact_res = mysqli_query($con, $contact_sql);
        $new_row = mysqli_fetch_array($contact_res);
        $name = $new_row[1];
        $type = $new_row[2];
        $dept = $new_row[3];
        $email = $new_row[4];
        $mobile = $new_row[5];
        $direct = $new_row[6];
        $extention = $new_row[7];
        $designation = $new_row[8];
        $active = $new_row[9];

        $update_contact_sql = "UPDATE `tbl_contact` SET `cont_name` = '$name', `cont_type` = '$type', `cont_dept` = '$dept', `cont_email` = '$email', `cont_mobile` = '$mobile', `cont_direct` = '$direct', `cont_ext` = '$extention', `cont_desg` = '$designation', `cont_active` = '$active' WHERE `tbl_contact`.`cont_id` = $contact_id;";
        $update_contact_res = mysqli_query($con, $update_contact_sql);
        if (mysqli_affected_rows($con) > 0) {
            $delete_contact_sql = "DELETE FROM `tbl_contact_temp` WHERE `cont_id`=$contact_id";
            $delete_contact_res = mysqli_query($con, $delete_contact_sql);
            if ($delete_contact_res) {
                $update_flag = TRUE;
            }
        }
    }

    if (strpos($category, 'Office Details') !== FALSE) {
        $office_sql = "SELECT * FROM `tbl_office_temp` WHERE `cont_id`=$contact_id";
        $office_res = mysqli_query($con, $office_sql);
        $new_row = mysqli_fetch_array($office_res);

        $office_update_status = $new_row[9];
        $old_office_id = $new_row[10];

        $type = $new_row[1];
        $recruitment = $new_row[2];
        $comment = $new_row[3];
        $boardline = $new_row[4];
        $address = $new_row[5];
        $country = $new_row[6];
        $city = $new_row[7];
        $pin = $new_row[8];

        $update_office_flag = false;

        if ($office_update_status == 'Shift Office') {
            $update_contact_sql = "UPDATE `tbl_contact` SET `offi_id` = '$old_office_id' WHERE `tbl_contact`.`cont_id` = $contact_id;";
            $update_contact_res = mysqli_query($con, $update_contact_sql);
            if ($update_contact_res) {
                $update_office_flag = true;
            }
        } else if ($office_update_status == 'New') {
            $insert_office_sql = "INSERT INTO `tbl_office` (`offi_id`, `offi_type`, `offi_rec`, `offi_comment`, `offi_boardline`, `offi_address`, `offi_country`, `offi_city`, `offi_pin`, `comp_id`) VALUES (NULL, '$type', '$recruitment', '$comment', '$boardline', '$address', '$country', '$city', '$pin', '$company_id');";
            $insert_office_res = mysqli_query($con, $insert_office_sql);
            if ($insert_office_res) {
                $new_office_id = mysqli_insert_id($con);
                $update_contact_sql = "UPDATE `tbl_contact` SET `offi_id` = '$new_office_id' WHERE `tbl_contact`.`cont_id` = $contact_id;";
                $update_contact_res = mysqli_query($con, $update_contact_sql);
                if ($update_contact_res) {
                    $update_office_flag = true;
                }
            }
        } else if ($office_update_status == 'Change Shifted Office') {
            $update_office_sql = "UPDATE `tbl_office` SET `offi_type` = '$type', `offi_rec` = '$recruitment', `offi_comment` = '$comment', `offi_boardline` = '$boardline', `offi_address` = '$address', `offi_country` = '$country', `offi_city` = '$city', `offi_pin` = '$pin' WHERE `tbl_office`.`offi_id` = $old_office_id;";
            $update_office_res = mysqli_query($con, $update_office_sql);
            if (mysqli_affected_rows($con)) {
                $update_contact_sql = "UPDATE `tbl_contact` SET `offi_id` = '$old_office_id' WHERE `tbl_contact`.`cont_id` = $contact_id;";
                $update_contact_res = mysqli_query($con, $update_contact_sql);
                if ($update_contact_res) {
                    $update_office_flag = true;
                }
            }
        } else {
            $update_office_sql = "UPDATE `tbl_office` SET `offi_type` = '$type', `offi_rec` = '$recruitment', `offi_comment` = '$comment', `offi_boardline` = '$boardline', `offi_address` = '$address', `offi_country` = '$country', `offi_city` = '$city', `offi_pin` = '$pin' WHERE `tbl_office`.`offi_id` = $old_office_id;";
            $update_office_res = mysqli_query($con, $update_office_sql);
            if (mysqli_affected_rows($con)) {
                $update_office_flag = true;
            }
        }
        if ($update_office_flag) {
            $delete_office = "DELETE FROM `tbl_office_temp` WHERE `cont_id`=$contact_id";
            $delete_res = mysqli_query($con, $delete_office);
            if ($delete_res) {
                $update_flag = TRUE;
            }
        }
    }

    if (strpos($category, 'Other Details') !== FALSE) {
        $new_stream_sql = "SELECT * FROM `tbl_stream_temp` WHERE `cont_id`=$contact_id";
        $new_stream_res = mysqli_query($con, $new_stream_sql);
        if (mysqli_num_rows($new_stream_res) > 0) {
            $delete_stream_sql = "DELETE FROM `tbl_stream` WHERE `cont_id`=$contact_id";
            $delete_stream_res = mysqli_query($con, $delete_stream_sql);
            $stream_update_flag = false;
            if ($delete_stream_res) {
                while ($row = mysqli_fetch_array($new_stream_res)) {
                    $stream_name = $row[1];
                    $package = $row[3];
                    $stream_insert_sql = "INSERT INTO `tbl_stream` (`stream_id`, `stream`, `cont_id`, `package`) VALUES (NULL, '$stream_name', '$contact_id', '$package');";
                    $stream_insert_res = mysqli_query($con, $stream_insert_sql);
                    if ($stream_insert_res) {
                        $stream_update_flag = true;
                    }
                }
            }
            if ($stream_update_flag) {
                $stream_delete_sql = "DELETE FROM `tbl_stream_temp` WHERE `cont_id`=$contact_id";
                $stream_delete_res = mysqli_query($con, $stream_delete_sql);
            }
        }
        // chekc and delete whether there is any updates done in the contact event section.
        $conclave_sql = "SELECT `cont_conclave` FROM `tbl_contact_temp` WHERE `cont_id`=$contact_id";
        $conclave_res = mysqli_query($con, $conclave_sql);
        $conclave_update_res = false;
        $conclave_delete_res = FALSE;
        if (mysqli_num_rows($conclave_res) > 0) {
            $conclave = mysqli_fetch_array($conclave_res);
            $conclave_value = $conclave[0];
            $conclave_update_sql = "UPDATE `tbl_contact` SET `cont_conclave` = '$conclave_value' WHERE `cont_id` = $contact_id;";
            mysqli_query($con, $conclave_update_sql);
            if (mysqli_affected_rows($con)) {
                $conclave_update_res = true;
            }
            if ($conclave_update_res) {
                $conclave_delete_sql = "DELETE FROM `tbl_contact_temp` WHERE `cont_id`=$contact_id";
                mysqli_query($con, $conclave_delete_sql);
                if (mysqli_affected_rows($con)) {
                    $conclave_delete_res = true;
                }
            }
        }
        if ($stream_delete_res || $conclave_delete_res) {
            $update_flag = TRUE;
        }
    }
    if ($update_flag) {
        $edit_status_delete_sql = "DELETE FROM `tbl_cont_edit_status` WHERE `cont_id`=$contact_id and `edit_status`='Pending'";
        $edit_status_delete_res = mysqli_query($con, $edit_status_delete_sql);
        if ($edit_status_delete_res) {
            echo "Success";
        } else {
            echo "Failed";
        }
    }
}
