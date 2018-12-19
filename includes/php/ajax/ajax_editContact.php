<?php
session_start();
include_once '../db.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>CRM :: Edit Contact</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- List of style sheets to be included-->
        <!-- CSS Reset -->
        <link rel="stylesheet" type="text/css" href="../../../reset.css" media="screen" />

        <!-- Fluid 960 Grid System - CSS framework -->
        <link rel="stylesheet" type="text/css" href="../../../grid.css" media="screen" />
        <script type="text/javascript" src="../../../js/contactAction.js"></script>

        <!-- IE Hacks for the Fluid 960 Grid System -->
        <!--[if IE 6]><link rel="stylesheet" type="text/css" href="../../ie6.css"  media="screen" /><![endif]-->
        <!--[if IE 7]><link rel="stylesheet" type="text/css" href="../../ie.css" media="screen" /><![endif]-->

        <!-- Main stylesheet -->
        <link rel="stylesheet" type="text/css" href="../../../styles.css"  media="screen" />
    </head>
    <body>
        <form method="post" action="" id="editContactForm" onsubmit="return validateEditContact();">
            <div class="grid_6" style="margin-top: 10px;width: 95%" >
                <div class="module">
                    <h2><span>Contact Details</span></h2>
                    <div class="module-body">
                        <?php
                        if (isset($_POST['btnUpdateContact']) && !empty($_POST['btnUpdateContact'])) {
                            $contact_flag = false;
                            $cont_id = $_POST['hid_cont_id'];
                            //check whether the cont_id is there in tbl_cont_edit_status
                            $check_contact_sql = "SELECT * FROM `tbl_cont_edit_status` WHERE `cont_id`=$cont_id and edit_status='Pending'";
                            $check_contact_res = mysqli_query($con, $check_contact_sql);
                            if (mysqli_num_rows($check_contact_res) == 0) {
                                $companyId = $_POST['hid_comp_id'];

                                // here we are getting all the user inputed value and hidden value                                                      
                                $salute = mysqli_real_escape_string($con, $_POST['cont_salute']);
                                $old_salute = $_POST['hid_cont_salute'];
                                $cont_name = mysqli_real_escape_string($con, $_POST['cont_name']);
                                $old_cont_name = $_POST['hid_cont_name'];
                                $cont_email = mysqli_real_escape_string($con, $_POST['cont_email']);
                                $old_cont_email = $_POST['hid_cont_email'];
                                $cont_mobile = mysqli_real_escape_string($con, $_POST['cont_mobile']);
                                $old_cont_mobile = $_POST['hid_cont_mobile'];
                                $cont_direct = mysqli_real_escape_string($con, $_POST['cont_direct']);
                                $old_cont_direct = $_POST['hid_cont_direct'];
                                $cont_ext = mysqli_real_escape_string($con, $_POST['cont_ext']);
                                $old_cont_ext = $_POST['hid_cont_ext'];
                                $cont_desg = mysqli_real_escape_string($con, $_POST['cont_desg']);
                                $old_cont_desg = $_POST['hid_cont_desg'];
                                $cont_dept = mysqli_real_escape_string($con, $_POST['cont_dept']);
                                $old_cont_dept = $_POST['hid_cont_dept'];
                                $cont_active = mysqli_real_escape_string($con, $_POST['cont_active']);
                                $old_cont_active = $_POST['hid_cont_active'];
                                $conclave = $_POST['cont_conclave'];
                                $hid_conclave = $_POST['hidConclave'];
                                $fullName = $salute . " " . $cont_name;
                                $cont_type = 'Regular';
                                $edit_category = '';
                                $flag = false;
                                // here we check that if the hidden value which is the existing personal information of the contact differ the current inputed value                                                         
                                if ($salute != $old_salute || $cont_name != $old_cont_name || $cont_email != $old_cont_email || $cont_mobile != $old_cont_mobile || $cont_direct != $old_cont_direct || $cont_ext != $old_cont_ext || $cont_desg != $old_cont_desg || $cont_dept != $old_cont_dept || $cont_active != $old_cont_active) {
                                    // change the category to personal information
                                    $edit_category = 'Personal Details';
                                    // Then insert the current information in the temporary table
                                    $contact_insert_sql = "INSERT INTO `tbl_contact_temp` (`cont_id`, `cont_name`, `cont_type`, `cont_dept`, `cont_email`, `cont_mobile`, `cont_direct`, `cont_ext`, `cont_desg`, `cont_active`, `cont_conclave`) VALUES ('$cont_id', '$fullName', '$cont_type', '$cont_dept', '$cont_email', '$cont_mobile', '$cont_direct', '$cont_ext', '$cont_desg', '$cont_active', $hid_conclave);";
                                    $res = mysqli_query($con, $contact_insert_sql);
                                    if ($res) {
                                        $flag = true;
                                    }
                                }

                                $officeUpdateStatus = $_POST['officeSubmitType'];
                                $otherUpdateStatus = $_POST['otherSubmitType'];
                                //$cont_status = mysqli_real_escape_string($con, $_POST['cont_status']);
                                //if other details of the contact checkbox is checked
                                if ($otherUpdateStatus != "none") {
                                    $stream = $_POST['stream'];
                                    $hid_stream = $_POST['hid_stream'];
                                    $package = $_POST['txtPackage'];
                                    $amtPackage = array();
                                    // get the individual package amount from the package array
                                    for ($i = 0, $j = 0, $c = count($package); $i < $c; $i++) {
                                        if ($package[$i] !== "") {
                                            $amtPackage[$j] = $package[$i];
                                            $j++;
                                        }
                                    }
                                    $hid_package = $_POST['hid_txtPackage'];
                                    // here we check that if the user changes the streams or the package amount of the stream
                                    if ($stream != $hid_stream || $amtPackage != $hid_package || $conclave != $hid_conclave) {
                                        // if conclave is not equal to the hindden conclave value
                                        if ($conclave != $hid_conclave) {
                                            // if personal details is not updated then the cont_temp_insert_id would be 0 else it would be the insert id
                                            if ($flag == false) {
                                                // insert the personal details into the cont_temp table
                                                $contact_insert_sql = "INSERT INTO `tbl_contact_temp` (`cont_id`, `cont_name`, `cont_type`, `cont_dept`, `cont_email`, `cont_mobile`, `cont_direct`, `cont_ext`, `cont_desg`, `cont_active`, `cont_conclave`) VALUES ('$cont_id', '$fullName', '$cont_type', '$cont_dept', '$cont_email', '$cont_mobile', '$cont_direct', '$cont_ext', '$cont_desg', '$cont_active', '$hid_conclave');";
                                                $res = mysqli_query($con, $contact_insert_sql);
                                            }
                                            // update the cont_conclave column value to the updated conclave value with the selected value
                                            $query_conclave = "UPDATE `tbl_contact_temp` SET `cont_conclave` = '$conclave' WHERE `cont_id` = $cont_id;";
                                            $res_conclave = mysqli_query($con, $query_conclave);
                                            if (mysqli_affected_rows($con)) {
                                                $flag = TRUE;
                                            }
                                        }
                                        // if stream or package details have been changed
                                        if ($stream != $hid_stream || $amtPackage != $hid_package) {
                                            // insert the package amount stream wise to the tbl_stream_temp table
                                            $packageAmount = 0;
                                            for ($i = 0, $c = count($stream); $i < $c; $i++) {
                                                if ($amtPackage[$i] == "") {
                                                    $packageAmount = 0;
                                                } else {
                                                    $packageAmount = $amtPackage[$i];
                                                }
                                                $queryStream = "insert into tbl_stream_temp values (NULL, '$stream[$i]', $cont_id, $packageAmount)";
                                                $res = mysqli_query($con, $queryStream);
                                                if ($res) {
                                                    $flag = true;
                                                }
                                            }
                                        }
                                        // check wheather the persoanl details has been changed
                                        if ($edit_category == 'Personal Details') {
                                            $edit_category = 'Personal Details, Other Details';
                                        } else {
                                            $edit_category = 'Other Details';
                                        }
                                    }
                                }
                                // if office address of the contact is not updated
                                if ($officeUpdateStatus != "none") {
                                    // way to change the office                                    
                                    $office_change_type = $_POST['offi_selector'];
                                    $office_flag = false;
                                    //Select a way,New,Existing
                                    if ($office_change_type == 'New') {
                                        $officeType = $_POST['offi_type'];
                                        $hid_officeType = $_POST['hid_offi_type'];
                                        $officeBoard = mysqli_real_escape_string($con, $_POST['newOffice']);
                                        $officeAddress = mysqli_real_escape_string($con, $_POST['offi_add1']) . " " . mysqli_real_escape_string($con, $_POST['offi_add2']);
                                        $recStatus = $_POST['rec_status'];
                                        if ($recStatus == "") {
                                            $recStatus = "No";
                                        }
                                        $officeCountry = $_POST['offi_country'];
                                        $city = $_POST['offi_city'];
                                        $pin = mysqli_real_escape_string($con, $_POST['offi_pin']);
                                        //insert the new office address of that company in the tbl_office                                        
                                        $sqlOffice = "INSERT INTO `tbl_office_temp` (`offi_id`, `offi_type`, `offi_rec`, `offi_comment`, `offi_boardline`, `offi_address`, `offi_country`, `offi_city`, `offi_pin`, `offi_status`, `offi_old_id`, `cont_id`) VALUES (NULL, '$officeType', '$recStatus', 'None', '$officeBoard', '$officeAddress', '$officeCountry', '$city', '$pin', 'New', '0', '$cont_id');";
                                        $resOffice = mysqli_query($con, $sqlOffice);
                                        if ($resOffice) {
                                            $flag = true;
                                            $office_flag = true;
                                        }
                                    } else if ($office_change_type == 'Existing') {
                                        $office_id = $_POST['cmbOffice'];
                                        $office_type = $_POST['cmbOfficeType1'];
                                        $hid_office_type = $_POST['hid_cmbOfficeType1'];
                                        $office_board_line = mysqli_real_escape_string($con, $_POST['txtOffiBoardLine1']);
                                        $hid_office_board_line = $_POST['hid_txtOffiBoardLine1'];
                                        $office_address = mysqli_real_escape_string($con, $_POST['areaOfficeAddress1']);
                                        $hid_office_address = $_POST['hid_areaOfficeAddress1'];
                                        $office_country = $_POST['cmbCountry1'];
                                        $hid_office_country = $_POST['hid_cmbCountry1'];
                                        $office_city = $_POST['cmbCity1'];
                                        $hid_office_city = $_POST['hid_cmbCity1'];
                                        $office_pin = mysqli_real_escape_string($con, $_POST['txtPin1']);
                                        $hid_office_pin = $_POST['hid_txtPin1'];
                                        $office_recrument = $_POST['chkRecrument1'];
                                        if ($office_recrument == "") {
                                            $office_recrument = "No";
                                        }
                                        $hid_office_recrument = $_POST['hid_chkRecrument'];
                                        if ($office_type != $hid_office_type || $office_board_line != $hid_office_board_line || $office_address != $hid_office_address || $office_country != $hid_office_country || $office_city != $hid_office_city || $office_pin != $hid_office_pin || $office_recrument != $hid_office_recrument) {
                                            $sqlOffice = "insert into tbl_office_temp values (NULL,'$office_type','$office_recrument','None','$office_board_line','$office_address','$office_country','$office_city','$office_pin','Change Shifted Office',$office_id,$cont_id)";
                                            $resOffice = mysqli_query($con, $sqlOffice);
                                            if ($resOffice) {
                                                $flag = true;
                                                $office_flag = true;
                                            }
                                        } else {
                                            $sqlOffice = "insert into tbl_office_temp values (NULL,'','','','','','','','','Shift Office',$office_id,$cont_id)";
                                            $resOffice = mysqli_query($con, $sqlOffice);
                                            if ($resOffice) {
                                                $flag = true;
                                                $office_flag = true;
                                            }
                                        }
                                    } else {
                                        $office_id = $_POST['hid_office_id'];
                                        $office_type = $_POST['cmbOfficeType'];
                                        $hid_office_type = $_POST['hid_cmbOfficeType'];
                                        $office_board_line = mysqli_real_escape_string($con, $_POST['txtOffiBoardLine']);
                                        $hid_office_board_line = $_POST['hid_txtOffiBoardLine'];
                                        $office_address = mysqli_real_escape_string($con, $_POST['areaOfficeAddress']);
                                        $hid_office_address = $_POST['hid_areaOfficeAddress'];
                                        $office_country = $_POST['cmbCountry'];
                                        $hid_office_country = $_POST['hid_cmbCountry'];
                                        $office_city = $_POST['cmbCity'];
                                        $hid_office_city = $_POST['hid_cmbCity'];
                                        $office_pin = mysqli_real_escape_string($con, $_POST['txtPin']);
                                        $hid_office_pin = $_POST['hid_txtPin'];
                                        $office_recrument = $_POST['chkRecrument'];
                                        if ($office_recrument == "") {
                                            $office_recrument = "No";
                                        }
                                        $hid_office_recrument = $_POST['hid_chkRecrument'];
                                        if ($office_type != $hid_office_type || $office_board_line != $hid_office_board_line || $office_address != $hid_office_address || $office_country != $hid_office_country || $office_city != $hid_office_city || $office_pin != $hid_office_pin || $office_recrument != $hid_office_recrument) {
                                            $sqlOffice = "insert into tbl_office_temp values (NULL,'$office_type','$office_recrument','None','$office_board_line','$office_address','$office_country','$office_city','$office_pin','Existing',$office_id,$cont_id)";
                                            $resOffice = mysqli_query($con, $sqlOffice);
                                            if ($resOffice) {
                                                $flag = true;
                                                $office_flag = true;
                                            }
                                        }
                                    }
                                    if ($office_flag) {
                                        if ($edit_category == 'Personal Details') {
                                            $edit_category = 'Personal Details, Office Details';
                                        } else if ($edit_category == 'Other Details') {
                                            $edit_category = 'Office Details, Other Details';
                                        } else if ($edit_category == 'Personal Details, Other Details') {
                                            $edit_category = 'Personal Details, Office Details, Other Details';
                                        } else {
                                            $edit_category = 'Office Details';
                                        }
                                    }
                                }
                                if ($flag) {
                                    $edit_time = time();
                                    $edit_status = 'Pending';
                                    $empl_id = $_SESSION['empId'];

                                    $edit_status_sql = "INSERT INTO `tbl_cont_edit_status` (`edit_id`, `edit_category`, `edit_time`, `edit_status`, `edit_comment`, `cont_id`, `comp_id`, `empl_id`) VALUES (NULL, '$edit_category', '$edit_time', '$edit_status', '', '$cont_id', '$companyId', '$empl_id');";
                                    $edit_status_res = mysqli_query($con, $edit_status_sql);
                                    if ($edit_status_res) {
                                        if ($edit_category == 'Personal Details') {
                                            ?>
                                            <div>
                                                <span class='notification n-success'>Updated Personal Details has been send for Admin Approval.</span>
                                            </div>
                                            <?php
                                        } else if ($edit_category == 'Office Details') {
                                            ?>
                                            <div>
                                                <span class='notification n-success'>Updated Office Details has been send for Admin Approval.</span>
                                            </div>
                                            <?php
                                        } else if ($edit_category == 'Other Details') {
                                            ?>
                                            <div>
                                                <span class='notification n-success'>Updated Other Details has been send for Admin Approval.</span>
                                            </div>
                                            <?php
                                        } else if ($edit_category == 'Personal Details, Other Details') {
                                            ?>
                                            <div>
                                                <span class='notification n-success'>Updated Personal Details and Other Details has been send for Admin Approval.</span>
                                            </div>
                                            <?php
                                        } else if ($edit_category == 'Personal Details, Office Details') {
                                            ?>
                                            <div>
                                                <span class='notification n-success'>Updated Personal Details and Office Details has been send for Admin Approval.</span>
                                            </div>
                                            <?php
                                        } else if ($edit_category == 'Office Details, Other Details') {
                                            ?>
                                            <div>
                                                <span class='notification n-success'>Updated Office Details and Other Details has been send for Admin Approval.</span>
                                            </div>
                                            <?php
                                        } else if ($edit_category == 'Personal Details, Office Details, Other Details') {
                                            ?>
                                            <div>
                                                <span class='notification n-success'>Updated Personal Details, Office Details and Other Details has been send for Admin Approval.</span>
                                            </div>
                                            <?php
                                        } else {
                                            echo "<div>
                                                <span class='notification n-error'>No updates done - edit catagory.</span>
                                            </div>";
                                        }
                                    } else {
                                        echo "<div>
                                    <span class='notification n-error'>Error while updating contact.</span>
                                  </div>";
                                    }
                                } else {
                                    echo "<div>
                                    <span class='notification n-error'>No updates done - no changes found.</span>
                                  </div>";
                                }
                            } else {
                                echo "<div>
                                    <span class='notification n-error'>An edit request has already been pending for this contact. Please check the status on \"Edit Status\" section and wait untill admin approval to edit it again.
                                  </div>";
                            }
                        } else if (isset($_GET['contId']) && !empty($_GET['contId'])) {
                            $cont_id = $_GET['contId'];

                            $sql = "SELECT * FROM `tbl_contact` WHERE `cont_id` = $cont_id";
                            $res = mysqli_query($con, $sql);
                            $row = mysqli_fetch_array($res);

                            $cont_name = $row['cont_name'];
                            $arr_name = explode(' ', trim($cont_name));
                            // to check wheteher the salutation is there in the name or not 
                            $salute_counter = 0;

                            //salutation    
                            $salute = $arr_name[0];
                            $existing_salute = "";
                            $sqlSalute = "select sett_value from tbl_setting where sett_type='salute' and sett_status='Active'";
                            $resSalute = mysqli_query($con, $sqlSalute);
                            $salutation = "<select name='cont_salute'><option value=''>Salutation</option>";
                            while ($rowSalute = mysqli_fetch_array($resSalute)) {
                                if ($rowSalute[0] == $salute) {
                                    $salutation.="<option selected>" . $rowSalute[0] . "</option>";
                                    $salute_counter++;
                                    $existing_salute = $salute;
                                } else {
                                    $salutation.="<option>" . $rowSalute[0] . "</option>";
                                }
                            }
                            $salutation.="</select>";

                            //contact name without the salutation 
                            $name_without_salutation = "";
                            if ($salute_counter == 0) {
                                $name_without_salutation = $cont_name;
                            } else {
                                for ($i = 0; $i < sizeof($arr_name); $i++) {
                                    if ($i == 0) {
                                        continue;
                                    } else {
                                        $name_without_salutation .= $arr_name[$i] . " ";
                                    }
                                }
                            }

                            //contact type
                            $cont_type = $row['cont_type'];
                            $additional_relation = "";
                            $dispaly_additional_contact = "none";
                            //contact status
                            $cont_status = $row['cont_status'];
                            $sqlContStatus = "select sett_value from tbl_setting where sett_type='acti_status'";
                            $resContStatus = mysqli_query($con, $sqlContStatus);
                            $contactStatus = "";
                            while ($rowContStatus = mysqli_fetch_array($resContStatus)) {
                                if ($rowContStatus[0] == $cont_status) {
                                    $contactStatus.="<option selected>" . $rowContStatus[0] . "</option>";
                                } else {
                                    $contactStatus.="<option>" . $rowContStatus[0] . "</option>";
                                }
                            }

                            //email
                            $email = $row['cont_email'];
                            //mobile
                            $mobile = $row['cont_mobile'];
                            //direct number
                            $direct = $row['cont_direct'];
                            //extension number
                            $extension = $row['cont_ext'];
                            //designation
                            $designation = $row['cont_desg'];
                            // conclave
                            $conclave = $row['cont_conclave'];
                            //company id
                            $comp_id = $row['comp_id'];

                            //department
                            $cont_dept = $row['cont_dept'];
                            $sqlDept = "select sett_value from tbl_setting where sett_type='func_dept' and sett_status='Active'";
                            $resDept = mysqli_query($con, $sqlDept);
                            $contDept = "<option value='NA'>Select a Department</option>";
                            if (mysqli_num_rows($resDept) > 0) {
                                while ($rowDept = mysqli_fetch_array($resDept)) {
                                    if ($cont_dept == $rowDept[0]) {
                                        $contDept .= "<option value='$rowDept[0]' selected>$rowDept[0]</option>";
                                    } else {
                                        $contDept .= "<option value='$rowDept[0]'>$rowDept[0]</option>";
                                    }
                                }
                            } else {
                                $contDept .= "<option value='NA'>No Department Found</option>";
                            }

                            //current status
                            $curr_status = $row['cont_active'];
                            $currentStatus = "";
                            if ($curr_status == "Active") {
                                $currentStatus .= "<option selected>Active</option>";
                            } else {
                                $currentStatus .= "<option>Active</option>";
                            }
                            if ($curr_status == "Inactive") {
                                $currentStatus .= "<option selected>Inactive</option>";
                            } else {
                                $currentStatus .= "<option>Inactive</option>";
                            }
                            ?>                                                    
                            <div id="notificationMsg"></div>
                            <input type="hidden" value="<?php echo $cont_id; ?>" name="hid_cont_id" />        
                            <p>
                                <label>Name(*)</label>
                                <?php echo $salutation; ?> 
                                <input type="hidden" name="hid_cont_name" value="<?php echo $name_without_salutation; ?>" />
                                <input type="text" class="input-long" placeholder="Enter the Name of the Contact" name="cont_name" value="<?php echo $name_without_salutation; ?>"/>
                                <input type="hidden" name="hid_cont_salute" value="<?php echo $existing_salute; ?>" />                                
                            </p>
                            <p>
                                <label>Email</label>
                                <input type="text" class="input-long" placeholder="Enter the E-Mail if the Contact" name="cont_email" value="<?php echo $email; ?>"/>
                                <input type="hidden" name="hid_cont_email" value="<?php echo $email; ?>" />
                            </p>
                            <p>
                                <label>Mobile</label> 
                                <input type="text" class="input-long" placeholder="Enter the Mobile Number of the Contact" name="cont_mobile" value="<?php echo $mobile; ?>" />
                                <input type="hidden" name="hid_cont_mobile" value="<?php echo $mobile; ?>"/>
                            </p>
                            <p>
                                <label>Direct Number</label> 
                                <input type="text" class="input-long" placeholder="Enter the Direct Number of the Contact"  name="cont_direct" value="<?php echo $direct; ?>"/>
                                <input type="hidden" name="hid_cont_direct" value="<?php echo $direct; ?>" />
                            </p>
                            <p>
                                <label>Extension Number</label> 
                                <input type="text" class="input-long" placeholder="Enter the Extension Number of the Contact"  name="cont_ext" value="<?php echo $extension; ?>" />
                                <input type="hidden" name="hid_cont_ext" value="<?php echo $extension; ?>" />
                            </p>
                            <p>                
                                <label>Designation</label> 
                                <input type="text" class="input-long" placeholder="Enter the Designation of the Contact"  name="cont_desg" value="<?php echo $designation; ?>"/>
                                <input type="hidden" name="hid_cont_desg" value="<?php echo $designation; ?>" />
                            </p>
                            <p>
                                <label>Department(*)</label> 
                                <select class="input-long"  placeholder="Department to which Contact belongs" name="cont_dept">
                                    <?php echo $contDept; ?>
                                </select>
                                <input type="hidden" name="hid_cont_dept" value="<?php echo $cont_dept; ?>"/>
                            </p>
                            <p>                
                                <label>Current Status</label> 
                                <select class="input-long" name="cont_active">
                                    <?php echo $currentStatus; ?>
                                </select>
                                <input type="hidden" name="hid_cont_active" value="<?php echo $curr_status; ?>"/>
                            </p>

                            <p id="additionalContactRelation" style="display: <?php echo $dispaly_additional_contact; ?>;">
                                <label>Select the Relationship for Additional Contact</label>
                                <select name="add_relation">
                                    <?php echo $additional_relation; ?>
                                </select>
                            </p>
                            <p>                
                                <input type="checkbox" name="chkChangeOffi" id="chkChangeOffi" onchange="displayOfficeDetails();"> Edit Office Details
                            </p>                                                     
                            <p>                
                                <input type="checkbox" name="chkChangeOther" id="chkChangeOther" onchange="displayOtherDetails();"> Edit Other Details
                            </p>                                                     
                        </div>
                    </div> 
                </div> 
                <!-- this section display the Office Details the Contact-->
                <div class="grid_6" style="margin-top: 10px;width: 95%;display: none" id="contactOfficeDiv">
                    <div class="module">
                        <h2><span>Office Details</span></h2>
                        <div class="module-body" style="display:block;padding-bottom: 0px;">
                            <p>
                                <label>Select the Way to update office details</label> 
                                <select name="offi_selector" onchange="displayOfficeForm(this.value)">                                    
                                    <option>Select a way</option>
                                    <option>New</option>
                                    <option>Existing</option>
                                </select>                                
                            </p>
                        </div>
                        <!-- Hidden field to store the operation regarding updating office details of the contact -->
                        <input type="hidden" name="officeSubmitType" id="officeSubmitType" value="none" />                        
                        <?php
                        $office_id = $row['offi_id'];
                        $office_sql = "SELECT * FROM `tbl_office` WHERE `offi_id`=$office_id ";
                        $office_res = mysqli_query($con, $office_sql);
                        $office_row = mysqli_fetch_array($office_res);
                        $office_type = $office_row['offi_type'];
                        $office_rec = $office_row['offi_rec'];
                        if ($office_rec == "Yes") {
                            $recrument_status = "checked='checked'";
                        } else {
                            $recrument_status = "";
                        }
                        $office_boardLine = $office_row['offi_boardline'];
                        $office_address = $office_row['offi_address'];
                        $office_country = $office_row['offi_country'];
                        $office_city = $office_row['offi_city'];
                        $office_pin = $office_row['offi_pin'];
                        ?>                            
                        <div class="module-body" id="existingOffice" style="display:block;padding-top: 0px;" >
                            <p>
                                <input type="hidden" value="<?php echo $comp_id; ?>" name="hid_comp_id" />                                    
                                <input type="hidden" name="hid_office_id" value="<?php echo $office_id; ?>"/>
                                <label>Office Type</label>          
                                <!-- <input type='text' class='input-long' name='txtOfficeType' value='<?php echo $office_type ?>' >  -->
                                <select name="cmbOfficeType" class="input-long">
                                    <?php
                                    $sqlOfficeType = "select sett_value from tbl_setting where sett_type='offi_type'";
                                    $resOfficeType = mysqli_query($con, $sqlOfficeType);
                                    $officeType = "";
                                    while ($row = mysqli_fetch_array($resOfficeType)) {
                                        if ($row[0] == $office_type) {
                                            $officeType.="<option selected>" . $row[0] . "</option>";
                                        } else {
                                            $officeType.="<option>" . $row[0] . "</option>";
                                        }
                                    }
                                    echo $officeType;
                                    ?>
                                </select>
                                <input type="hidden" name="hid_cmbOfficeType" value='<?php echo $office_type ?>'/>
                            </p>
                            <p>
                                <label>Board Line Number</label>
                                <input type="text" class="input-long" name="txtOffiBoardLine" value ="<?php echo "$office_boardLine"; ?>" >
                                <input type="hidden" name="hid_txtOffiBoardLine" value ="<?php echo "$office_boardLine"; ?>" />
                            </p>
                            <p>
                                <label>Address</label>                               
                                <textarea class="input-long" name="areaOfficeAddress" rows="4" cols="45" style="resize: none;" ><?php echo "$office_address"; ?></textarea>
                                <input type="hidden" name="hid_areaOfficeAddress" value="<?php echo "$office_address"; ?>"/>
                            </p>                            
                            <p>
                                <label>Country</label> 
                                <!-- <input type="text" class="input-long" name="txtCountry" value="<?php echo "$office_country"; ?>"  > -->                                
                                <select class="input-long" name="cmbCountry" onchange="fetchCityList(this.value, 'current')">
                                    <?php if ($office_country == 'India') { ?>
                                        <option selected>India</option>
                                        <option>Other</option>
                                    <?php } else { ?>
                                        <option>India</option>
                                        <option selected>Other</option>
                                    <?php } ?>
                                </select>                                    
                                <input type="hidden" name="hid_cmbCountry" value="<?php echo "$office_country"; ?>" />
                            </p>
                            <p>
                                <label>City</label> 
                                <!--<input type="text" class="input-long" name="txtCity" value="<?php echo "$office_city"; ?>" > -->
                                <span id="cityListContainer-current">
                                    <?php
                                    $sqlAddress = "select addr_city from tbl_address where addr_country='India' order by addr_city asc";
                                    $resAddress = mysqli_query($con, $sqlAddress);
                                    if (mysqli_num_rows($resAddress) > 0) {
                                        $city = "<select class='input-long' name='cmbCity'>";
                                        while ($row = mysqli_fetch_array($resAddress)) {
                                            if ($office_city == $row[0]) {
                                                $city.="<option selected>" . $row[0] . "</option>";
                                            } else {
                                                $city.="<option >" . $row[0] . "</option>";
                                            }
                                        }
                                        $city.="</select>";
                                    } else {
                                        $city = "No City Available";
                                    }
                                    echo $city;
                                    ?>
                                </span>
                                <input type="hidden" name="hid_cmbCity" value="<?php echo "$office_city"; ?>" >
                            </p>
                            <p>
                                <label>PIN</label> 
                                <input type="text" class="input-long" name="txtPin" <?php
                                if ($office_pin != "") {
                                    echo "value='$office_pin'";
                                } else {
                                    echo "placeholder='Not Available'";
                                }
                                ?>/>
                                <input type="hidden" name="hid_txtPin" value="<?php echo $office_pin; ?>"/>
                            </p>
                            <p>
                                <label>
                                    <input type="checkbox" name="chkRecrument" <?php echo $recrument_status; ?> value="Yes"> Recruitment Calls are taken from this office
                                </label>                                
                                <input type="hidden" name="hid_chkRecrument" value="<?php echo "$office_rec"; ?>" >
                            </p>                            
                        </div>                                                
                        <div class="module-body" id="newOffice" style="display:none; padding-top: 0px;" >                            
                            <p>
                                <label>Office Type</label> 
                                <select class="input-long" name="offi_type">
                                    <?php include_once './ajax_officeTypeComboList.php'; ?>
                                </select>                            
                            </p>
                            <p>
                                <label>Board Line Number</label>
                                <input type="text" class="input-long" placeholder="Enter the Board Line Number" name="newOffice"/>
                            </p>
                            <p>
                                <label>Address Line 1</label> 
                                <input type="text" class="input-long" placeholder="First Line of Address" name="offi_add1"/>
                            </p>
                            <p>
                                <label>Address Line 2</label> 
                                <input type="text" class="input-long" placeholder="Second Line of Address" name="offi_add2"/>
                            </p>
                            <p>
                                <label>Country</label> 
                                <select class="input-long" name="offi_country" onchange="fetchCityList(this.value, 'new')">
                                    <option>India</option>
                                    <option>Other</option>
                                </select>
                            </p>
                            <p>
                                <label>City</label> 
                                <span id="cityListContainer-new">
                                    <?php
                                    $sqlAddress = "select addr_city from tbl_address where addr_country='India' order by addr_city asc";
                                    $resAddress = mysqli_query($con, $sqlAddress);
                                    if (mysqli_num_rows($resAddress) > 0) {
                                        $city = "<select class='input-long' name='offi_city'>";
                                        while ($row = mysqli_fetch_array($resAddress)) {
                                            $city.="<option>" . $row[0] . "</option>";
                                        }
                                        $city.="</select>";
                                    } else {
                                        $city = "No City Available";
                                    }
                                    echo $city;
                                    ?>
                                </span>
                            </p>
                            <p>
                                <label>PIN</label> 
                                <input type="text" class="input-long" name="offi_pin" placeholder="Enter PIN Code"/>
                            </p>
                            <p>                                
                                <label>
                                    <input type="checkbox" style="margin-top:10px;" name="rec_status" value="Yes" checked="checked"/>
                                    Recruitment Calls are taken from this office
                                </label>
                            </p>
                        </div>

                        <div class="module-body" id="oldOffice" style="display:none; padding-top: 0px;">
                            <!--a href="javascript:void(0)" class="button" onclick="fetchOfficeList(document.getElementsByName('txtCompSearchBox')[0].value)">
                                <span>Fetch Office List<img src="plus-small.gif" width="12" height="9" alt="Fetch Office List" /></span>
                            </a-->
                            <div id='officeListContainer'>
                                <select name="cmbOffice" onchange="displayOfficeDetail(this.value);">
                                    <option value="NA">Select an office</option>
                                    <?php
                                    $sqlCompany = "SELECT `offi_id`,`offi_type`,`offi_city` FROM `tbl_office` WHERE `comp_id`=$comp_id";
                                    $resCompany = mysqli_query($con, $sqlCompany);
                                    while ($rowCompany = mysqli_fetch_array($resCompany)) {
                                        echo "<option value='$rowCompany[0]'>$rowCompany[1] - $rowCompany[2]</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div id='officeDetailDiv'>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- this section display the Other Details the Contact-->
                <div class="grid_6" style="margin-top: 10px;width: 95%;display: none" id="contactOtherDiv">
                    <div class="module">
                        <h2><span>Other Details</span></h2>                                                   
                        <div class="module-body" style="display:block;padding-bottom: 10px;">
                            <p>
                                <label>Select the appropriate Stream</label> 
                                <?php
                                $sqlStream = "select sett_value from tbl_setting where sett_type='stream'";
                                $resStream = mysqli_query($con, $sqlStream);
                                echo "<table style='border:0px;'>";
                                $i = 1;
                                while ($row = mysqli_fetch_array($resStream)) {
                                    $sqlCheckStream = "select * from tbl_stream where stream = '$row[0]' and cont_id=$cont_id;";
                                    $resCheckStream = mysqli_query($con, $sqlCheckStream);
                                    $rowCheckStream = mysqli_fetch_array($resCheckStream);
                                    if (mysqli_num_rows($resCheckStream) > 0) {
                                        echo "<tr><td style='border:0px;'><input type='checkbox' value='" . $row[0] . "' id='stream$i' name='stream[]' onclick='displayPackage($i);' checked/> " . $row[0] . "</td><td style='border:0px;'><input type='text'  placeholder='Enter Offered Salary for $row[0] here' name='txtPackage[]' id='txtPackage$i' value='$rowCheckStream[3]' style='width: 100%'><input type='hidden' name='hid_stream[]' value='" . $row[0] . "'><input type='hidden' name='hid_txtPackage[]' value='$rowCheckStream[3]' style='width: 100%'></td></tr>";
                                    } else {
                                        echo "<tr><td style='border:0px;'><input type='checkbox' value='" . $row[0] . "' id='stream$i' name='stream[]' onclick='displayPackage($i);'/> " . $row[0] . "</td><td style='border:0px;'><input type='text'  palceholder='Enter the package here' placeholder='Enter Offered Salary for $row[0] here' name='txtPackage[]' id='txtPackage$i' style='display:none;width: 100%'></td></tr>";
                                    }
                                    $i++;
                                }
                                echo "</table>";
                                ?>
                            </p>

                            <p>
                                <label>Select the appropriate Event</label>
                                <select class="input-long" name="cont_conclave" id="cont_conclave">\                                    
                                    <option value="">Select an Event</option>                                
                                    <option value="Finance Conclave" <?php
                                    if ($conclave == "Finance Conclave") {
                                        echo "selected";
                                    }
                                    ?>>Finance Conclave</option>                                
                                    <option value="HR Conclave" <?php
                                    if ($conclave == "HR Conclave") {
                                        echo "selected";
                                    }
                                    ?>>HR Conclave</option>
                                    <option value="Marketing Conclve" <?php
                                    if ($conclave == "Marketing Conclve") {
                                        echo "selected";
                                    }
                                    ?>>Marketing Conclave</option>
                                    <option value="NMC" <?php
                                    if ($conclave == "NMC") {
                                        echo "selected";
                                    }
                                    ?>>NMC</option>
                                    <option value="Seminar" <?php
                                    if ($conclave == "Seminar") {
                                        echo "selected";
                                    }
                                    ?>>Seminar</option>
                                </select>
                                <input type="hidden" name="hidConclave" value="<?php echo $conclave; ?>" />
                            </p>

                            <p>
                                <label>Status of the Contact</label>
                                <!-- <select class="input-long" name="cont_status" onchange="commentOnStatusChange(this.value)"> -->
                                <select class="input-long" name="cont_status" disabled>
                                    <?php
                                    $sqlStatus = "select act_newStatus, act_statusComment, empl_cont_id from tbl_emp_contact where empl_cont_id = (SELECT MAX( empl_cont_id ) FROM tbl_emp_contact where cont_id=$cont_id )";
                                    $resStatus = mysqli_query($con, $sqlStatus);
                                    $rowStatus = mysqli_fetch_array($resStatus);
                                    $sqlStream = "select sett_value from tbl_setting where sett_type='acti_status'";
                                    $resStream = mysqli_query($con, $sqlStream);

                                    $status = "<option value='NA'>Select</option>";
                                    while ($row = mysqli_fetch_array($resStream)) {
                                        if ($row[0] == $rowStatus[0]) {
                                            $status.="<option selected>" . $row[0] . "</option>";
                                        } else {
                                            $status.="<option>" . $row[0] . "</option>";
                                        }
                                    }
                                    echo $status;
                                    ?>
                                </select>
                                <input type="hidden" name="hid_cont_status" value="<?php echo $rowStatus[0]; ?>"/>
                            </p>
                            <?php
                            if (strpos($rowStatus[0], "Negetive") !== FALSE) {
                                ?>
                                <input type="hidden" value="negetive" name="status" />
                                <input type="hidden" value="<?php echo $rowStatus[2]; ?>" name="empl_cont_id" />                                
                                <textarea class="input-long" rows="10" id="statusComment" name="statusComment" placeholder="Enter the summarized comments for the selected status" disabled><?php echo $rowStatus[1]; ?>' </textarea>
                                <input type="hidden" name="hid_statusComment" value="<?php echo $rowStatus[1]; ?>"/>
                                <p  id="positiveLabel" style="display:none">Enter the probable Month of visit</p>
                                <select name="visitingMonth" id="visitingMonth" style="display:none">
                                    <option value="NA">Select</option>
                                    <?php
                                    for ($monthNum = 1; $monthNum <= 12; $monthNum++) {
                                        //this convert a month number to a month name
                                        $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
                                        echo "<option>$monthName</option>";
                                    }
                                    ?>                                                                        
                                </select>
                                <select name="visitingYear" id="visitingYear" style="display:none">
                                    <option value="NA">Select</option>
                                    <?php
                                    $year_limit = (int) (date("Y") + 5);
//                                    $year = $year_limit+1;
                                    for ($year = 2013; $year < $year_limit; $year++) {
                                        echo "<option>$year</option>";
                                    }
                                    ?>                                    
                                </select>
                                <?php
                            } else if (strpos($rowStatus[0], "Positive") !== FALSE) {
                                $date = explode(",", $rowStatus[1]);
                                $month = $date[0];
                                $year = $date[1];
                                ?>                                
                                <input type="hidden" value="positive" name="status" />
                                <input type="hidden" value="<?php echo $rowStatus[2]; ?>" name="empl_cont_id" />
                                <textarea class="input-long" rows="10" id="statusComment" name="statusComment" placeholder="Enter the summarized comments for the selected status" style="display: none;"></textarea>
                                <p  id="positiveLabel">Enter the probable Month of visit</p>
                                <select name="visitingMonth" id="visitingMonth" disabled>
                                    <option value="NA">Select</option>
                                    <?php
                                    for ($monthNum = 1; $monthNum <= 12; $monthNum++) {
                                        //this convert a month number to a month name
                                        $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
                                        //if current month
                                        if ($monthName == $month) {
                                            echo "<option selected>$monthName</option>";
                                        } else {
                                            echo "<option>$monthName</option>";
                                        }
                                    }
                                    ?>                                                                        
                                </select>
                                <input type="hidden" name="hid_visitingMonth" value="<?php echo $month; ?>" />
                                <select name="visitingYear" id="visitingYear" disabled>
                                    <option value="NA">Select</option>
                                    <?php
                                    for ($yearNum = 2013; $yearNum <= 2017; $yearNum++) {
                                        // if current year
                                        if ($yearNum == $year) {
                                            echo "<option selected>$yearNum</option>";
                                        } else {
                                            echo "<option>$yearNum</option>";
                                        }
                                    }
                                    ?>                                    
                                </select>
                                <input type="hidden" name="hid_visitingYear" value="<?php echo $year; ?>" />
                            <?php } else { ?>
                                <input type="hidden" value="<?php echo $rowStatus[2]; ?>" name="empl_cont_id" />
                                <textarea class="input-long" rows="10" id="statusComment" name="statusComment" placeholder="Enter the summarized comments for the selected status" style="display: none;"></textarea>
                                <p  id="positiveLabel" style="display:none">Enter the probable Month of visit</p>
                                <select name="visitingMonth" id="visitingMonth" style="display:none">
                                    <option value="NA">Select</option>
                                    <?php
                                    for ($monthNum = 1; $monthNum <= 12; $monthNum++) {
                                        //this convert a month number to a month name
                                        $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
                                        echo "<option>$monthName</option>";
                                    }
                                    ?>                                                                        
                                </select>
                                <select name="visitingYear" id="visitingYear" style="display:none">
                                    <option value="NA">Select</option>
                                    <?php
                                    for ($yearNum = 2013; $yearNum <= 2017; $yearNum++) {
                                        echo "<option>$yearNum</option>";
                                    }
                                    ?>                                    
                                </select>
                                <?php
                            }
                            ?>
                        </div>                                                
                    </div>                    
                </div>   
                <input type="hidden" name="otherSubmitType" id="otherSubmitType" value="none" />                        
                <div class="grid_6">
                    <fieldset style="padding-left: 200px;">
                        <input class="submit-green" type="submit" name="btnUpdateContact" value="Send for Approval" style="margin-left:30px;" />
                    </fieldset> 
                </div>

            </form>  
            <?php
        }
        ?>
        <script>
            function validateEditContact() {
                var msgDiv = document.getElementById("notificationMsg");
                var msgSpan = document.createElement("span");
                msgSpan.setAttribute("class", "notification n-error");
                if (document.getElementById("notificationMsg").innerHTML !== "") {
                    msgDiv.innerHTML = "";
                }

                var form = document.getElementById("editContactForm");
                if (form.cont_name.value === "") {
                    msgSpan.innerHTML = "Name of the Contact cannot be left empty";
                    msgDiv.appendChild(msgSpan);
                    form.cont_name.focus();
                    return false;
                }
                if (form.cont_dept.value === "NA") {
                    msgSpan.innerHTML = "Department of the Contact cannot be left empty";
                    msgDiv.appendChild(msgSpan);
                    form.cont_dept.focus();
                    return false;
                }
                if (form.officeSubmitType.value === "change") {
                    msgSpan.innerHTML = "Please select a way to update Office Details.";
                    msgDiv.appendChild(msgSpan);
                    form.offi_selector.focus();
                    return false;
                }
                if (form.officeSubmitType.value === "newOffice") {
                    if (form.newOffice.value === "") {
                        msgSpan.innerHTML = "Board Line Number of the office cannot be left empty.";
                        msgDiv.appendChild(msgSpan);
                        form.newOffice.focus();
                        return false;
                    }
                    if (form.offi_add1.value === "") {
                        msgSpan.innerHTML = "Address Line 1 of the office cannot be left empty.";
                        msgDiv.appendChild(msgSpan);
                        form.offi_add1.focus();
                        return false;
                    }
                }
                if (form.officeSubmitType.value === "updateOffice") {
                    if (form.cmbOffice.value === "NA") {
                        msgSpan.innerHTML = "Please select an existing office.";
                        msgDiv.appendChild(msgSpan);
                        form.cmbOffice.focus();
                        return false;
                    }
                }
                if (form.otherSubmitType.value === "change") {
                    var stream = document.getElementsByName("stream[]");
                    var errFlag = 0;
                    for (var i = 0; i < stream.length; i++) {
                        if (stream[i].checked === true) {
                            errFlag = 1;
                        }
                    }
                    if (errFlag === 0) {
                        msgSpan.innerHTML = "Please select at least one Stream, regarding which the discussions are being carried out.";
                        msgDiv.appendChild(msgSpan);
                        stream[0].focus();
                        return false;
                    }
                }
                /*                  
                 var contStatus = form.cont_status.value;
                 if (contStatus === "NA") {
                 msgSpan.innerHTML = "Please select the status of the contact";
                 msgDiv.appendChild(msgSpan);
                 form.cont_status.focus();
                 return false;
                 }
                 if (contStatus.indexOf("Positive") >= 0) {
                 if (document.getElementById("visitingYear").value === "NA") {
                 msgSpan.innerHTML = "Please select expected visiting year.";
                 msgDiv.appendChild(msgSpan);
                 document.getElementById("visitingYear").focus();
                 return false;
                 }
                 if (document.getElementById("visitingMonth").value === "NA") {
                 msgSpan.innerHTML = "Please select expected visiting month.";
                 msgDiv.appendChild(msgSpan);
                 document.getElementById("visitingMonth").focus();
                 return false;
                 }
                 }
                 if (contStatus.indexOf("Negetive") >= 0) {
                 if (document.getElementById("statusComment").value === "") {
                 msgSpan.innerHTML = "Please enter the proper comment with reason in the field provided.";
                 msgDiv.appendChild(msgSpan);
                 document.getElementById("statusComment").focus();
                 return false;
                 }
                 }
                 */
            }
            function displayAdditionalContact(type) {
                if (type === "Additional Contact") {
                    document.getElementById("additionalContactRelation").style.display = "block";
                    var contentArea = document.getElementsByName("add_relation")[0];
                    if (contentArea.innerHTML !== "")
                        contentArea.innerHTML = "";
                    var xmlhttp;
                    if (window.XMLHttpRequest) {
                        xmlhttp = new XMLHttpRequest();
                    }
                    else
                    {
                        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
                    }
                    xmlhttp.onreadystatechange = function()
                    {
                        if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
                        {
                            contentArea.innerHTML = xmlhttp.responseText;
                        }
                    };
                    xmlhttp.open('GET', 'ajax_relationComboList.php', true);
                    xmlhttp.send();
                }

                else {
                    document.getElementById("additionalContactRelation").style.display = "none";
                }
            }
            function displayOfficeDetails() {
                if (document.getElementById("chkChangeOffi").checked === true) {
                    document.getElementById("contactOfficeDiv").style.display = "block";
                    document.getElementById("officeSubmitType").value = "existingOffice";
                }
                else {
                    document.getElementById("contactOfficeDiv").style.display = "none";
                    document.getElementById("officeSubmitType").value = "none";
                }
            }
            function displayOtherDetails() {
                if (document.getElementById("chkChangeOther").checked === true) {
                    document.getElementById("contactOtherDiv").style.display = "block";
                    document.getElementById("otherSubmitType").value = "change";
                }
                else {
                    document.getElementById("contactOtherDiv").style.display = "none";
                    document.getElementById("otherSubmitType").value = "none";
                }
            }
            function displayOfficeForm(type) {
                var officeSubmitType = document.getElementById("officeSubmitType");
                //alert(type);
                if (type === "New") {
                    document.getElementById('newOffice').style.display = 'block';
                    document.getElementById('oldOffice').style.display = 'none';
                    document.getElementById('existingOffice').style.display = 'none';
                    officeSubmitType.value = "newOffice";
                } else if (type === "Existing") {
                    document.getElementById('newOffice').style.display = 'none';
                    document.getElementById('oldOffice').style.display = 'block';
                    document.getElementById('existingOffice').style.display = 'none';
                    officeSubmitType.value = "updateOffice";
                }
                else {
                    document.getElementById('newOffice').style.display = 'none';
                    document.getElementById('oldOffice').style.display = 'none';
                    document.getElementById('existingOffice').style.display = 'block';
                    officeSubmitType.value = "none";
                }
            }
            function fetchCityList(country, type) {
                var contentArea = "";
                var cmbName = "";
                if (type === 'current') {
                    contentArea = document.getElementById("cityListContainer-current");
                    cmbName = "cmbCity";
                } else if (type === 'new') {
                    contentArea = document.getElementById("cityListContainer-new");
                    cmbName = "offi_city";
                } else if (type === 'existing') {
                    contentArea = document.getElementById("cityListContainer-existing");
                    cmbName = "cmbCity1";
                }
                var xmlhttp;
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                }
                else
                {
                    xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
                }
                xmlhttp.onreadystatechange = function()
                {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
                    {
                        var data = xmlhttp.responseText;
                        if (data == "No City Available")
                            contentArea.innerHTML = "Please Contact Admin to add the City to the Database";
                        else
                            contentArea.innerHTML = "<select class='input-long' name='" + cmbName + "'>" + data + "</select>";
                    }
                }
                xmlhttp.open('GET', 'ajax_cityComboList.php?country=' + country, true);
                xmlhttp.send();
            }
            function displayOfficeDetail(officeId) {
                var contentArea = document.getElementById("officeDetailDiv");
                if (officeId === "NA")
                    contentArea.innerHTML = "";
                else {
                    var xmlhttp;
                    if (window.XMLHttpRequest) {
                        xmlhttp = new XMLHttpRequest();
                    }
                    else
                    {
                        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
                    }
                    xmlhttp.onreadystatechange = function()
                    {
                        if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
                        {
                            contentArea.innerHTML = xmlhttp.responseText;
                        }
                    };
                    xmlhttp.open('GET', 'ajax_officeComboList.php?task=officeDetail&officeId=' + officeId, true);
                    xmlhttp.send();
                }
            }
            function commentOnStatusChange(statusType) {
                if (statusType.indexOf("Positive") > -1) {
                    document.getElementById("statusComment").style.display = "none";
                    document.getElementById("positiveLabel").style.display = "inline";
                    document.getElementById("visitingMonth").style.display = "inline";
                    document.getElementById("visitingYear").style.display = "inline";
                } else if (statusType.indexOf("Negetive") > -1) {
                    document.getElementById("statusComment").style.display = "inline";
                    document.getElementById("visitingMonth").style.display = "none";
                    document.getElementById("visitingYear").style.display = "none";
                    document.getElementById("positiveLabel").style.display = "none";
                } else {
                    document.getElementById("statusComment").style.display = "none";
                    document.getElementById("visitingMonth").style.display = "none";
                    document.getElementById("visitingYear").style.display = "none";
                    document.getElementById("positiveLabel").style.display = "none";
                }
            }
        </script>
    </body>
</html>