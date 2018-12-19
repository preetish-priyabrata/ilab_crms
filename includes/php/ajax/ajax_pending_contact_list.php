<?php
include_once '../db.php';

if (isset($_GET['contactId']) && !empty($_GET['contactId'])) {
    $contact_id = $_GET['contactId'];
    $category = $_GET['category'];
    $old_office_id = $_GET['officeId'];

    $old_personal_details = '';
    $new_personal_details = '';
    $old_office_details = '';
    $new_office_details = '';
    $old_other_details = '';
    $new_other_details = '';

    if (strpos($category, 'Personal Details') !== FALSE) {
        $old_contact_sql = "select * from tbl_contact where cont_id=$contact_id";
        $old_contact_res = mysqli_query($con, $old_contact_sql);
        $old_row = mysqli_fetch_array($old_contact_res);
        $old_name = $old_row[1];
        $old_email = $old_row[4];
        $old_mobile = $old_row[5];
        $old_direct = $old_row[6];
        $old_extension = $old_row[7];
        $old_designation = $old_row[8];
        $old_dept = $old_row[3];
        $old_status = $old_row[10];

        $new_contact_sql = "select * from tbl_contact_temp where cont_id=$contact_id";
        $new_contact_res = mysqli_query($con, $new_contact_sql);
        $new_row = mysqli_fetch_array($new_contact_res);
        $new_name = $new_row[1];
        $new_email = $new_row[4];
        $new_mobile = $new_row[5];
        $new_direct = $new_row[6];
        $new_extension = $new_row[7];
        $new_designation = $new_row[8];
        $new_dept = $new_row[3];
        $new_status = $new_row[9];

        $name_diff = '';
        $email_diff = '';
        $mobile_diff = '';
        $direct_diff = '';
        $extension_diff = '';
        $designation_diff = '';
        $dept_diff = '';
        $status_diff = '';
        if ($new_name != $old_name) {
            $name_diff = 'diff-color';
        }
        if ($new_email != $old_email) {
            $email_diff = 'diff-color';
        }
        if ($new_mobile != $old_mobile) {
            $mobile_diff = 'diff-color';
        }
        if ($new_direct != $old_direct) {
            $direct_diff = 'diff-color';
        }
        if ($new_extension != $old_extension) {
            $extension_diff = 'diff-color';
        }
        if ($new_designation != $old_designation) {
            $designation_diff = 'diff-color';
        }
        if ($new_dept != $old_dept) {
            $dept_diff = 'diff-color';
        }
        if ($new_status != $old_status) {
            $status_diff = 'diff-color';
        }
        $old_personal_details = '<div class="module">'
                . '<h2><span>Existing Personal Details</span></h2>'
                . '<div class="module-body">'
                . '<p>'
                . '<label>Name</label>'
                . '<input type="text" class="input-default ' . $name_diff . '" value="' . $old_name . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Email</label>'
                . '<input type="text" class="input-default ' . $email_diff . '" value="' . $old_email . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Mobile</label>'
                . '<input type="text" class="input-default ' . $mobile_diff . '" value="' . $old_mobile . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Direct Number</label>'
                . '<input type="text" class="input-default ' . $direct_diff . '" value="' . $old_direct . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Extension Number</label>'
                . '<input type="text" class="input-default ' . $extension_diff . '" value="' . $old_extension . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Designation</label>'
                . '<input type="text" class="input-default ' . $designation_diff . '" value="' . $old_designation . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Department</label>'
                . '<input type="text" class="input-default ' . $dept_diff . '" value="' . $old_dept . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Current Status</label>'
                . '<input type="text" class="input-default ' . $status_diff . '" value="' . $old_status . '" readonly disabled/>'
                . '</p>'
                . '</div>'
                . '</div>';

        $new_personal_details = '<div class="module">'
                . '<h2><span>New Personal Details</span></h2>'
                . '<div class="module-body">'
                . '<p>'
                . '<label>Name</label>'
                . '<input type="text" class="input-default ' . $name_diff . '" value="' . $new_name . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Email</label>'
                . '<input type="text" class="input-default ' . $email_diff . '" value="' . $new_email . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Mobile</label>'
                . '<input type="text" class="input-default ' . $mobile_diff . '" value="' . $new_mobile . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Direct Number</label>'
                . '<input type="text" class="input-default ' . $direct_diff . '" value="' . $new_direct . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Extension Number</label>'
                . '<input type="text" class="input-default ' . $extension_diff . '" value="' . $new_extension . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Designation</label>'
                . '<input type="text" class="input-default ' . $designation_diff . '" value="' . $new_designation . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Department</label>'
                . '<input type="text" class="input-default ' . $dept_diff . '" value="' . $new_dept . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Current Status</label>'
                . '<input type="text" class="input-default ' . $status_diff . '" value="' . $new_status . '" readonly disabled/>'
                . '</p>'
                . '</div>'
                . '</div>';
    }
    if (strpos($category, 'Office Details') !== FALSE) {
        $old_office_sql = "select * from tbl_office where offi_id=$old_office_id";
        $old_office_res = mysqli_query($con, $old_office_sql);
        $old_row = mysqli_fetch_array($old_office_res);
        $old_offi_type = $old_row[1];
        $old_offi_board = $old_row[4];
        $old_offi_address = $old_row[5];
        $old_offi_country = $old_row[6];
        $old_offi_city = $old_row[7];
        $old_offi_pin = $old_row[8];
        $old_offi_recrut = $old_row[2];

        $new_office_sql = "select * from tbl_office_temp where cont_id=$contact_id";
        $new_office_res = mysqli_query($con, $new_office_sql);
        $new_row = mysqli_fetch_array($new_office_res);
        $new_offi_status = $new_row[9];
        $old_offi_id = $new_row[10];
        if ($new_offi_status == 'Shift Office') {
            $shift_offi_sql = "select * from tbl_office where offi_id=$old_offi_id";
            $shift_offi_res = mysqli_query($con, $shift_offi_sql);
            $shift_row = mysqli_fetch_array($shift_offi_res);
            $new_offi_type = $shift_row[1];
            $new_offi_board = $shift_row[4];
            $new_offi_address = $shift_row[5];
            $new_offi_country = $shift_row[6];
            $new_offi_city = $shift_row[7];
            $new_offi_pin = $shift_row[8];
            $new_offi_recrut = $shift_row[2];
        } else {
            $new_offi_type = $new_row[1];
            $new_offi_board = $new_row[4];
            $new_offi_address = $new_row[5];
            $new_offi_country = $new_row[6];
            $new_offi_city = $new_row[7];
            $new_offi_pin = $new_row[8];
            $new_offi_recrut = $new_row[2];
        }

        $offi_type_diff = '';
        $board_diff = '';
        $address_diff = '';
        $country_diff = '';
        $city_diff = '';
        $pin_diff = '';
        $recrut_diff = '';
        if ($new_offi_type != $old_offi_type) {
            $offi_type_diff = 'diff-color';
        }
        if ($new_offi_board != $old_offi_board) {
            $board_diff = 'diff-color';
        }
        if ($new_offi_address != $old_offi_address) {
            $address_diff = 'diff-color';
        }
        if ($new_offi_country != $old_offi_country) {
            $country_diff = 'diff-color';
        }
        if ($new_offi_city != $old_offi_city) {
            $city_diff = 'diff-color';
        }
        if ($new_offi_pin != $old_offi_pin) {
            $pin_diff = 'diff-color';
        }
        if ($new_offi_recrut != $old_offi_recrut) {
            $recrut_diff = 'diff-color';
        }

        $old_office_details = '<div class="module">'
                . '<h2><span>Existing Office Details</span></h2>'
                . '<div class="module-body">'
                . '<p>'
                . '<label>Office Type</label>'
                . '<input type="text" class="input-default ' . $offi_type_diff . '" value="' . $old_offi_type . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Board Line Number</label>'
                . '<input type="text" class="input-default ' . $board_diff . '" value="' . $old_offi_board . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Address</label>'
                . '<input type="text" class="input-default ' . $address_diff . '" value="' . $old_offi_address . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Country</label>'
                . '<input type="text" class="input-default ' . $country_diff . '" value="' . $old_offi_country . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>City</label>'
                . '<input type="text" class="input-default ' . $city_diff . '" value="' . $old_offi_city . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>PIN</label>'
                . '<input type="text" class="input-default ' . $pin_diff . '" value="' . $old_offi_pin . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Recruitment Calls are taken from this office</label>'
                . '<input type="text" class="input-default ' . $recrut_diff . '" value="' . $old_offi_recrut . '" readonly disabled/>'
                . '</p>'
                . '</div>'
                . '</div>';

        $new_office_details = '<div class="module">'
                . '<h2><span>Existing Office Details</span></h2>'
                . '<div class="module-body">'
                . '<p>'
                . '<label>Office Type</label>'
                . '<input type="text" class="input-default ' . $offi_type_diff . '" value="' . $new_offi_type . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Board Line Number</label>'
                . '<input type="text" class="input-default ' . $board_diff . '" value="' . $new_offi_board . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Address</label>'
                . '<input type="text" class="input-default ' . $address_diff . '" value="' . $new_offi_address . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Country</label>'
                . '<input type="text" class="input-default ' . $country_diff . '" value="' . $new_offi_country . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>City</label>'
                . '<input type="text" class="input-default ' . $city_diff . '" value="' . $new_offi_city . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>PIN</label>'
                . '<input type="text" class="input-default ' . $pin_diff . '" value="' . $new_offi_pin . '" readonly disabled/>'
                . '</p>'
                . '<p>'
                . '<label>Recruitment Calls are taken from this office</label>'
                . '<input type="text" class="input-default ' . $recrut_diff . '" value="' . $new_offi_recrut . '" readonly disabled/>'
                . '</p>'
                . '</div>'
                . '</div>';
    }

    if (strpos($category, 'Other Details') !== FALSE) {
        // dispaly if any stream details have been changed for the particular contact
        $new_stream_sql = "select * from tbl_stream_temp where cont_id=$contact_id";
        $new_stream_res = mysqli_query($con, $new_stream_sql);
        $new_stream = '';
        $stream_count = count($old_stream_row);
        $diff_count = 0;
        $new_other_details = '<div class="module">'
                . '<h2><span>Existing Other Details</span></h2>'
                . '<div class="module-body">';
        if (mysqli_num_rows($new_stream_res) > 0) {
            while ($new_row = mysqli_fetch_array($new_stream_res)) {
                for ($i = 0; $i < $stream_count; $i++) {
                    if ($new_row[1] == $old_stream_row[$i]) {
                        if ($new_row[3] == $old_stream_salary[$i]) {
                            $diff_count++;
                            break;
                        } else {
                            $diff_count = 0;
                        }
                    } else {
                        $diff_count = 0;
                    }
                }
                if ($diff_count == 1) {
                    $new_stream .= '<tr><td style="width:50%;border:0;">' . $new_row[1] . '</td><td style="width:50%;border:0;"><input type="text" value="' . $new_row[3] . '" readonly disabled></td></tr>';
                } else {
                    $new_stream .= '<tr><td style="width:50%;border:0;background-color: yellow;">' . $new_row[1] . '</td><td style="width:50%;border:0;background-color: yellow;"><input type="text" value="' . $new_row[3] . '" readonly disabled></td></tr>';
                }
            }
            $new_other_details .= '<p><table style="border:0;">' . $new_stream . '</table></p>';
        } else {
            $new_other_details .= '<p><table style="border:0;">No updates made in Stream</table></p>';
        }
        $new_conclave_sql = "SELECT `cont_conclave` FROM `tbl_contact_temp` WHERE `cont_id`=$contact_id";
        $new_conclave_res = mysqli_query($con, $new_conclave_sql);
        if (mysqli_num_rows($new_conclave_res) > 0) {
            $new_conclave = mysqli_fetch_array($new_conclave_res);
            $new_conclave_value = $new_conclave[0];
            if ($new_conclave_value == "") {
                $new_conclave_value = "None";
            }
            $new_other_details .= '<p>'
                    . '<label>Appropriate Event</label>'
                    . '<input type="text" class="input-default diff-color" value="' . $new_conclave_value . '" readonly disabled/>'
                    . '</p>';
        }
        $new_other_details.="</div></div>";

        // if the stream of the contact have been changed then check for the 
        $old_other_details = '<div class="module">'
                . '<h2><span>Existing Other Details</span></h2><div class="module-body">';
        if (mysqli_num_rows($new_stream_res) > 0) {
            $old_stream_sql = "select * from tbl_stream where cont_id=$contact_id";
            $old_stream_res = mysqli_query($con, $old_stream_sql);
            $old_stream = '';
            $old_stream_row = array();
            $old_stream_salary = array();
            if (mysqli_num_rows($old_stream_res) > 0) {
                $i = 0;
                while ($old_row = mysqli_fetch_array($old_stream_res)) {
                    $old_stream .= '<tr><td style="width:50%;border:0;">' . $old_row[1] . '</td><td style="width:50%;border:0;"><input type="text" value="' . $old_row[3] . '" readonly disabled></td></tr>';
                    $old_stream_row[$i] = $old_row[1];
                    $old_stream_salary[$i] = $old_row[3];
                    $i++;
                }
                $old_other_details .= '<p><table style="border:0;">' . $old_stream . '</table></p>';
            } else {
                $old_other_details .='<p><table style="border:0;">No Stream Selected</table></p>';
            }
        } else {
            $old_other_details .= '<p><table style="border:0;">No updates made in Stream</table></p>';
        }
        // if there is any conclave in the tbl_cont_temp while while the category of the Edit is "Other", then display the value of the old conclave for comparision
        if (mysqli_num_rows($new_conclave_res) > 0) {
            $old_conclave_sql = "SELECT `cont_conclave` FROM `tbl_contact` WHERE `cont_id`=$contact_id";
            $old_conclave_res = mysqli_query($con, $old_conclave_sql);

            $old_conclave = mysqli_fetch_array($old_conclave_res);
            $old_conclave_value = $old_conclave[0];
            if ($old_conclave_value == "") {
                $old_conclave_value = "None";
            }
            $old_other_details .= '<p>'
                    . '<label>Appropriate Event</label>'
                    . '<input type="text" class="input-default diff-color" value="' . $old_conclave_value . '" readonly disabled/>'
                    . '</p>';
        }
        $old_other_details.="</div></div>";
    }
    ?>    
    <div class="module" style="width: 49%;">
        <h2><span>Existing - Contact Details</span></h2>

        <div class="module-body">
            <?php
            echo $old_personal_details;
            echo $old_office_details;
            echo $old_other_details;
            ?>
        </div>
    </div>
    <div class="module" style="width:49%; float: right;">
        <h2><span>New - Contact Details</span></h2>

        <div class="module-body">
            <?php
            echo $new_personal_details;
            echo $new_office_details;
            echo $new_other_details;
            ?>
            <div class="module" id="comment-div" style="display: none;">
                <h2><span>Reason for Disapproval</span></h2>
                <div class="module-body">
                    <textarea rows="5" cols="50" placeholder="Enter the reason for disapproval here..." name="txtDisapproveReason" id="txtDisapproveReason"></textarea>
                </div>
            </div>
            <input class="submit-green" type="button" onclick="approveUpdatedContact('<?php echo $contact_id; ?>');" name="btnContactApprove" value="Approve" style="margin-left: 30%;">
            <input class="submit-gray" type="button" onclick="disapproveUpdatedContact('<?php echo $contact_id; ?>', '<?php echo $category; ?>');" name="btnContactDisapprove" value="Disapprove" style="margin-left: 1%">
        </div>        
    </div>
    <?php
}
