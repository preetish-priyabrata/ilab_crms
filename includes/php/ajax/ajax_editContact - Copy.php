<!DOCTYPE html>
<html>
    <head>
        <title>CRM :: Add new Event/Notice</title>
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
                        include_once '../db.php';
                        if (isset($_POST['btnUpdateContact']) && !empty($_POST['btnUpdateContact']) && $_POST['btnUpdateContact'] == 'Update Contact') {
                            $cont_id = mysqli_real_escape_string($con, $_POST['hid_cont_id']);
                            $salute = mysqli_real_escape_string($con, $_POST['salute']);
                            $cont_name = mysqli_real_escape_string($con, $_POST['cont_name']);
                            $fullName = $salute . " " . $cont_name;
                            $cont_email = mysqli_real_escape_string($con, $_POST['cont_email']);
                            $cont_mobile = mysqli_real_escape_string($con, $_POST['cont_mobile']);
                            $cont_direct = mysqli_real_escape_string($con, $_POST['cont_direct']);
                            $cont_ext = mysqli_real_escape_string($con, $_POST['cont_ext']);
                            $cont_desg = mysqli_real_escape_string($con, $_POST['cont_desg']);
                            $cont_dept = mysqli_real_escape_string($con, $_POST['cont_dept']);
                            $cont_active = mysqli_real_escape_string($con, $_POST['cont_active']);
                            $cont_type = 'Regular';
                            $officeUpdateStatus = $_POST['officeSubmitType'];
                            $otherUpdateStatus = $_POST['otherSubmitType'];
                            //$cont_status = mysqli_real_escape_string($con, $_POST['cont_status']);
                            //if other details of the contact is none
                            if ($otherUpdateStatus == "none") {
                                $sql = "UPDATE `tbl_contact` SET `cont_name` = '$fullName', `cont_type` = '$cont_type', `cont_dept` = '$cont_dept', `cont_email` = '$cont_email', `cont_mobile` = '$cont_mobile', `cont_direct` = '$cont_direct', `cont_ext` = '$cont_ext', `cont_desg` = '$cont_desg', `cont_active` = '$cont_active' WHERE `tbl_contact`.`cont_id` = $cont_id;";
                            } else {
                                $sql = "UPDATE `tbl_contact` SET `cont_name` = '$fullName', `cont_type` = '$cont_type', `cont_dept` = '$cont_dept', `cont_email` = '$cont_email', `cont_mobile` = '$cont_mobile', `cont_direct` = '$cont_direct', `cont_ext` = '$cont_ext', `cont_desg` = '$cont_desg', `cont_active` = '$cont_active' WHERE `tbl_contact`.`cont_id` = $cont_id;";
                                $sqlDeleteStream = "delete from tbl_stream where cont_id=$cont_id";
                                $resDeleteStream = mysqli_query($con, $sqlDeleteStream);
                                //,visitingYear,visitingMonth,statusComment,empl_cont_id,status,cont_status
                                $stream = $_POST['stream'];
                                $package = $_POST['txtPackage'];
                                $amtPackage = array();
                                for ($i = 0, $j = 0, $c = count($package); $i < $c; $i++) {
                                    if ($package[$i] !== "") {
                                        $amtPackage[$j] = $package[$i];
                                        $j++;
                                    }
                                }
                                $packageAmount = 0;
                                for ($i = 0, $c = count($stream); $i < $c; $i++) {
                                    if ($amtPackage[$i] == "")
                                        $packageAmount = 0;
                                    else
                                        $packageAmount = $amtPackage[$i];
                                    $queryStream = "insert into tbl_stream values (NULL, '$stream[$i]', $cont_id, $packageAmount)";
                                    mysqli_query($con, $queryStream);
                                }
                                $contStatus = $_POST['cont_status'];
                                $empl_cont_id = $_POST['empl_cont_id'];
                                $comment = '';
                                if (strpos($contStatus, "Negetive") !== FALSE) {
                                    $comment = $_POST['statusComment'];
                                } else if (strpos($contStatus, "Positive") !== FALSE) {
                                    $month = $_POST['visitingMonth'];
                                    $year = $_POST['visitingYear'];
                                    $comment = "$month,$year";
                                }
                                $sqlUpdateEmpCont = "update tbl_emp_contact set `act_statusComment`='$comment', `act_newStatus`='$contStatus' where empl_cont_id=$empl_cont_id";
                                $resUpdateEmpCont = mysqli_query($con, $sqlUpdateEmpCont);
                                if (mysqli_affected_rows($con) > 0) {
                                    $sqlUpdateContact = "update tbl_contact set cont_status='$contStatus' where cont_id=$cont_id";
                                    mysqli_query($con, $sqlUpdateContact);
                                }
                            }
                            // if office address of the contact is not updated
                            if ($officeUpdateStatus == "none") {
                                $sql = "UPDATE `tbl_contact` SET `cont_name` = '$fullName', `cont_type` = '$cont_type', `cont_dept` = '$cont_dept', `cont_email` = '$cont_email', `cont_mobile` = '$cont_mobile', `cont_direct` = '$cont_direct', `cont_ext` = '$cont_ext', `cont_desg` = '$cont_desg', `cont_active` = '$cont_active' WHERE `tbl_contact`.`cont_id` = $cont_id;";
                            } else if ($officeUpdateStatus == "updateOffice") {//newOffice, 
                                $office_id = $_POST['cmbOffice'];
                                $sql = "UPDATE `tbl_contact` SET `cont_name` = '$fullName', `cont_type` = '$cont_type', `cont_dept` = '$cont_dept', `cont_email` = '$cont_email', `cont_mobile` = '$cont_mobile', `cont_direct` = '$cont_direct', `cont_ext` = '$cont_ext', `cont_desg` = '$cont_desg', `cont_active` = '$cont_active', `offi_id` = '$office_id' WHERE `tbl_contact`.`cont_id` = $cont_id;";
                            } else {   //else if a new office address is added
                                //hid_comp_id, offi_type, newOffice, offi_add1, offi_add2, offi_country, offi_city, rec_status
                                //insert the new office address of that company in the tbl_office
                                $companyId = $_POST['hid_comp_id'];
                                $officeType = $_POST['offi_type'];
                                $officeBoard = $_POST['newOffice'];
                                $officeAddress = $_POST['offi_add1'] . " " . $_POST['offi_add1'];
                                $recStatus = $_POST['rec_status'];
                                if ($recStatus == "") {
                                    $recStatus = "No";
                                }
                                $officeCountry = $_POST['offi_country'];
                                $city = $_POST['offi_city'];
                                $pin = $_POST['offi_pin'];
                                $sqlOffice = "insert into tbl_office values (NULL,'$officeType','$recStatus','None','$officeBoard','$officeAddress','$officeCountry','$city','$pin',$companyId)";
                                $resOffice = mysqli_query($con, $sqlOffice);
                                if ($resOffice) {
                                    $office_id = mysqli_insert_id($con);
                                    $sql = "UPDATE `tbl_contact` SET `cont_name` = '$fullName', `cont_type` = '$cont_type', `cont_dept` = '$cont_dept', `cont_email` = '$cont_email', `cont_mobile` = '$cont_mobile', `cont_direct` = '$cont_direct', `cont_ext` = '$cont_ext', `cont_desg` = '$cont_desg', `cont_active` = '$cont_active', `offi_id` = '$office_id' WHERE `tbl_contact`.`cont_id` = $cont_id;";
                                } else {
                                    echo "<div>
                                        <span class='notification n-success'>Error while inserting New Office Details.</span>
                                    </div>";
                                    return;
                                }
                            }
                            $res = mysqli_query($con, $sql);
                            if ($con) {
                                echo "<div>
                                        <span class='notification n-success'>Contact has been updated sucessfully.</span>
                                    </div>";
                            } else {
                                echo "<div>
                                    <span class='notification n-error'>No updates done.</span>
                                  </div>";
                            }
                        } else if (isset($_GET['contId']) && !empty($_GET['contId'])) {
                            $cont_id = $_GET['contId'];

                            $sql = "SELECT * FROM `tbl_contact` WHERE `cont_id` = $cont_id";
                            $res = mysqli_query($con, $sql);
                            $row = mysqli_fetch_array($res);

                            $cont_name = $row['cont_name'];
                            //contact name without the salutation 
                            $name_without_salutation = "";
                            $arr_name = explode(' ', trim($cont_name));
                            for ($i = 0; $i < sizeof($arr_name); $i++) {
                                if ($i == 0)
                                    continue;
                                else
                                    $name_without_salutation .= $arr_name[$i] . " ";
                            }
                            //salutation    
                            $salute = $arr_name[0];
                            $sqlSalute = "select sett_value from tbl_setting where sett_type='salute' and sett_status='Active'";
                            $resSalute = mysqli_query($con, $sqlSalute);
                            $salutation = "<select name='salute'>";
                            while ($rowSalute = mysqli_fetch_array($resSalute)) {
                                if ($rowSalute[0] == $salute)
                                    $salutation.="<option selected>" . $rowSalute[0] . "</option>";
                                else
                                    $salutation.="<option>" . $rowSalute[0] . "</option>";
                            }
                            $salutation.="</select>";

                            //contact type
                            $cont_type = $row['cont_type'];
                            $additional_relation = "";
                            $dispaly_additional_contact = "none";
                            if ($cont_type == "Regular" || $cont_type == "Functional Head") {
                                if ($cont_readtype == "Regular")
                                    $contactType .= "<option selected>Regular</option>";
                                else
                                    $contactType .= "<option>Regular</option>";
                                if ($cont_type == "Functional Head")
                                    $contactType .= "<option selected>Functional Head</option>";
                                else
                                    $contactType .= "<option>Functional Head</option>";
                                $contactType .= "<option>Additional Contact</option>";
                            }
                            else {
                                $contactType .= "<option>Regular</option>";
                                $contactType .= "<option>Functional Head</option>";
                                $contactType .= "<option selected>Additional Contact</option>";
                                $dispaly_additional_contact = "block";

                                $sqlAddContact = "select sett_value from tbl_setting where sett_type='add_relation'";
                                $resAddContact = mysqli_query($con, $sqlAddContact);
                                while ($rowAddContact = mysqli_fetch_array($resAddContact)) {
                                    if ($rowAddContact[0] == $cont_type)
                                        $additional_relation.="<option selected>" . $rowAddContact[0] . "</option>";
                                    else
                                        $additional_relation.="<option>" . $rowAddContact[0] . "</option>";
                                }
                            }
                            //contact status
                            $cont_status = $row['cont_status'];
                            $sqlContStatus = "select sett_value from tbl_setting where sett_type='acti_status'";
                            $resContStatus = mysqli_query($con, $sqlContStatus);
                            $contactStatus = "";
                            while ($rowContStatus = mysqli_fetch_array($resContStatus)) {
                                if ($rowContStatus[0] == $cont_status)
                                    $contactStatus.="<option selected>" . $rowContStatus[0] . "</option>";
                                else
                                    $contactStatus.="<option>" . $rowContStatus[0] . "</option>";
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
                            //company id
                            $comp_id = $row['comp_id'];

                            //department
                            $cont_dept = $row['cont_dept'];
                            $sqlDept = "select sett_value from tbl_setting where sett_type='func_dept' and sett_status='Active'";
                            $resDept = mysqli_query($con, $sqlDept);
                            $contDept = "<option value='NA'>Select a Department</option>";
                            if (mysqli_num_rows($resDept) > 0) {
                                while ($rowDept = mysqli_fetch_array($resDept)) {
                                    if ($cont_dept == $rowDept[0])
                                        $contDept .= "<option value='$rowDept[0]' selected>$rowDept[0]</option>";
                                    else
                                        $contDept .= "<option value='$rowDept[0]'>$rowDept[0]</option>";
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
                                <input type="text" class="input-long" placeholder="Enter the Name of the Contact" name="cont_name" value="<?php echo $name_without_salutation ?>"/>
                            </p>
                            <p>
                                <label>Email</label>
                                <input type="text" class="input-long" placeholder="Enter the E-Mail if the Contact" name="cont_email" value="<?php echo $email; ?>"/>
                            </p>
                            <p>
                                <label>Mobile</label> 
                                <input type="text" class="input-long" placeholder="Enter the Mobile Number of the Contact" name="cont_mobile" value="<?php echo $mobile; ?>" />
                            </p>
                            <p>
                                <label>Direct Number</label> 
                                <input type="text" class="input-long" placeholder="Enter the Direct Number of the Contact"  name="cont_direct" value="<?php echo $direct; ?>"/>
                            </p>
                            <p>
                                <label>Extension Number</label> 
                                <input type="text" class="input-long" placeholder="Enter the Extension Number of the Contact"  name="cont_ext" value="<?php echo $extension; ?>" />
                            </p>
                            <p>                
                                <label>Designation</label> 
                                <input type="text" class="input-long" placeholder="Enter the Designation of the Contact"  name="cont_desg" value="<?php echo $designation; ?>"/>
                            </p>
                            <p>
                                <label>Department(*)</label> <select class="input-long"  placeholder="Department to which Contact belongs" name="cont_dept">
                                    <?php echo $contDept; ?>
                                </select>
                            </p>
                            <p>                
                                <label>Current Status</label> 
                                <select class="input-long" name="cont_active">
                                    <?php echo $currentStatus; ?>
                                </select>
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
                        $office_boardLine = $office_row['offi_boardline'];
                        $office_address = $office_row['offi_address'];
                        $office_country = $office_row['offi_country'];
                        $office_city = $office_row['offi_city'];
                        $office_pin = $office_row['offi_pin'];
                        ?>                            
                        <div class="module-body" id="existingOffice" style="display:block;padding-top: 0px;" >
                            <p>
                                <label>Office Type</label>                                                                            
                                <input type='text' class='input-long' name='txtOfficeType' value='<?php echo $office_type ?>' readonly>  
                            </p>
                            <p>
                                <label>Board Line Number</label>
                                <input type="text" class="input-long" name="txtOffiBoardLine" value ="<?php echo "$office_boardLine"; ?>" readonly />
                            </p>
                            <p>
                                <label>Address</label>                               
                                <textarea class="input-long" rows="4" cols="45" style="resize: none;" readonly><?php echo "$office_address"; ?></textarea>
                            </p>                            
                            <p>
                                <label>Country</label> 
                                <input type="text" class="input-long" name="txtCountry" value="<?php echo "$office_country"; ?>" readonly />
                            </p>
                            <p>
                                <label>City</label> 
                                <input type="text" class="input-long" name="txtCity" value="<?php echo "$office_city"; ?>" readonly />
                            </p>
                            <p>
                                <label>PIN</label> 
                                <input type="text" class="input-long" name="txtPin" value="<?php if($office_pin!="") {echo "$office_pin";}else{echo "Not Available";} ?>" readonly />
                            </p>
                            <p>
                                <label>Recruitment Calls are taken from this office</label>
                                <input type="text" class="input-long" name="txtCity" value="<?php echo "$office_rec"; ?>" readonly />
                            </p>
                            <!--
                            <p>
                                <label>Country</label> 
                                <select class="input-long" name="offi_country" onchange="fetchCityList(this.value)">
                                    <?php
                                    if ($office_country == "India") {
                                        echo "<option selected>India</option><option>Other</option>";
                                    } else {
                                        echo "<option selected>India</option><option selected>Other</option>";
                                    }
                                    ?>                                                                                                
                                </select>
                            </p>
                            <p>
                                <label>City</label> 
                                <span id="cityListContainer">
                                    <?php
                                    $sqlAddress = "select addr_city from tbl_address where addr_country='India' order by addr_city asc";
                                    $resAddress = mysqli_query($con, $sqlAddress);
                                    if (mysqli_num_rows($resAddress) > 0) {
                                        $city = "<select class='input-long' name='offi_city'>";
                                        while ($row = mysqli_fetch_array($resAddress)) {
                                            if ($office_city == $row[0]) {
                                                $city.="<option selected>" . $row[0] . "</option>";
                                            } else {
                                                $city.="<option>" . $row[0] . "</option>";
                                            }
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
                                <?php
                                if($office_rec == "Yes"){
                                   $recrument_status = "checked='checked'"; 
                                }
                                else{
                                    $recrument_status = ""; 
                                }
                                ?>
                                <label><input type="checkbox" style="margin-top:10px;" name="rec_status" <?php echo $recrument_status; ?>/>Recruitment Calls are taken from this office</label>
                            </p>-->
                        </div>                                                
                        <div class="module-body" id="newOffice" style="display:none; padding-top: 0px;" >
                            <input type="hidden" value="<?php echo $comp_id; ?>" name="hid_comp_id" />                                    
                            <p>
                                <label>Office Type</label> <select class="input-long" name="offi_type"><?php include_once './ajax_officeTypeComboList.php'; ?></select>
                            </p>
                            <p>
                                <label>Board Line Number</label><input type="text" class="input-long" placeholder="Enter the Board Line Number" name="newOffice"/>
                            </p>
                            <p>
                                <label>Address Line 1</label> <input type="text" class="input-long" placeholder="First Line of Address" name="offi_add1"/>
                            </p>
                            <p>
                                <label>Address Line 2</label> <input type="text" class="input-long" placeholder="Second Line of Address" name="offi_add2"/>
                            </p>
                            <p>
                                <label>Country</label> 
                                <select class="input-long" name="offi_country" onchange="fetchCityList(this.value)">
                                    <option>India</option>
                                    <option>Other</option>
                                </select>
                            </p>
                            <p>
                                <label>City</label> 
                                <span id="cityListContainer">
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
                                <label><input type="checkbox" style="margin-top:10px;" name="rec_status" value="Yes" checked="checked"/>Recruitment Calls are taken from this office</label>
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
                                        echo "<tr><td style='border:0px;'><input type='checkbox' value='" . $row[0] . "' id='stream$i' name='stream[]' onclick='displayPackage($i);' checked/> " . $row[0] . "</td><td style='border:0px;'><input type='text'  palceholder='Enter the package here' placeholder='Enter Offered Salary for $row[0] here' name='txtPackage[]' id='txtPackage$i' value='$rowCheckStream[3]' style='width: 100%'></td></tr>";
                                    } else {
                                        echo "<tr><td style='border:0px;'><input type='checkbox' value='" . $row[0] . "' id='stream$i' name='stream[]' onclick='displayPackage($i);'/> " . $row[0] . "</td><td style='border:0px;'><input type='text'  palceholder='Enter the package here' placeholder='Enter Offered Salary for $row[0] here' name='txtPackage[]' id='txtPackage$i' style='display:none;width: 100%'></td></tr>";
                                    }
                                    $i++;
                                }
                                echo "</table>";
                                ?>
                            </p>
                            <p>
                                <label>Status of the Contact</label>
                                <select class="input-long" name="cont_status" onchange="commentOnStatusChange(this.value)">
                                    <?php
                                    $sqlStatus = "select act_newStatus, act_statusComment, empl_cont_id from tbl_emp_contact where act_detail='The contact was added to the CR Database' and cont_id=$cont_id";
                                    $resStatus = mysqli_query($con, $sqlStatus);
                                    $rowStatus = mysqli_fetch_array($resStatus);
                                    $sqlStream = "select sett_value from tbl_setting where sett_type='acti_status'";
                                    $resStream = mysqli_query($con, $sqlStream);

                                    $status = "<option value='NA'>Select</option>";
                                    while ($row = mysqli_fetch_array($resStream)) {
                                        if ($row[0] == $rowStatus[0])
                                            $status.="<option selected>" . $row[0] . "</option>";
                                        else
                                            $status.="<option>" . $row[0] . "</option>";
                                    }
                                    echo $status;
                                    ?>
                                </select>
                            </p>
                            <?php
                            if (strpos($rowStatus[0], "Negetive") !== FALSE) {
                                ?>
                                <input type="hidden" value="negetive" name="status" />
                                <input type="hidden" value="<?php echo $rowStatus[2]; ?>" name="empl_cont_id" />
                                <textarea class="input-long" rows="10" id="statusComment" name="statusComment" placeholder="Enter the summarized comments for the selected status"><?php echo $rowStatus[1]; ?>' </textarea>
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
                            } else if (strpos($rowStatus[0], "Positive") !== FALSE) {
                                $date = explode(",", $rowStatus[1]);
                                $month = $date[0];
                                $year = $date[1];
                                ?>                                
                                <input type="hidden" value="positive" name="status" />
                                <input type="hidden" value="<?php echo $rowStatus[2]; ?>" name="empl_cont_id" />
                                <textarea class="input-long" rows="10" id="statusComment" name="statusComment" placeholder="Enter the summarized comments for the selected status" style="display: none;"></textarea>
                                <p  id="positiveLabel">Enter the probable Month of visit</p>
                                <select name="visitingMonth" id="visitingMonth">
                                    <option value="NA">Select</option>
                                    <?php
                                    for ($monthNum = 1; $monthNum <= 12; $monthNum++) {
                                        //this convert a month number to a month name
                                        $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
                                        //if current month
                                        if ($monthName == $month)
                                            echo "<option selected>$monthName</option>";
                                        else
                                            echo "<option>$monthName</option>";
                                    }
                                    ?>                                                                        
                                </select>
                                <select name="visitingYear" id="visitingYear" >
                                    <option value="NA">Select</option>
                                    <?php
                                    for ($yearNum = 2013; $yearNum <= 2017; $yearNum++) {
                                        // if current year
                                        if ($yearNum == $year)
                                            echo "<option selected>$yearNum</option>";
                                        else
                                            echo "<option>$yearNum</option>";
                                    }
                                    ?>                                    
                                </select>
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

                <fieldset style="text-align: center;">
                    <input class="submit-green" type="submit" name="btnUpdateContact" value="Update Contact" style="margin-left:30px;" />
                </fieldset> 
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
                    document.getElementById("officeSubmitType").value = "change";
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
            function fetchCityList(country) {
                //alert(compId);
                //alert(country);
                var contentArea = document.getElementById("cityListContainer");
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
                            contentArea.innerHTML = "<select class='input-long' name='offi_city'>" + data + "</select>";
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