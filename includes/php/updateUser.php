<?php
session_start();
include_once 'db.php';
$empId = $_SESSION['empId'];
?>
<!DOCTYPE html>
<html>
    <head>
        <title>CRM :: Update User</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- List of style sheets to be included-->
        <!-- CSS Reset -->
        <link rel="stylesheet" type="text/css" href="../../reset.css" media="screen" />

        <!-- Fluid 960 Grid System - CSS framework -->
        <link rel="stylesheet" type="text/css" href="../../grid.css" media="screen" />

        <!-- IE Hacks for the Fluid 960 Grid System -->
        <!--[if IE 6]><link rel="stylesheet" type="text/css" href="../../ie6.css"  media="screen" /><![endif]-->
        <!--[if IE 7]><link rel="stylesheet" type="text/css" href="../../ie.css" media="screen" /><![endif]-->

        <!-- Main stylesheet -->
        <link rel="stylesheet" type="text/css" href="../../styles.css"  media="screen" />
        <!-- Style Sheet related to DatePicker -->
        <link rel="stylesheet" type="text/css" media="all" href="../../css/jsDatePick_ltr.min.css" />
        <!-- Datepicker JavaScript -->
        <script type="text/javascript" src="../../js/jsDatePick.min.1.3.js"></script>
        <script type="text/javascript">
            var showDatePicker = function(field) {
                new JsDatePick({
                    useMode: 2,
                    target: field,
                    dateFormat: "%d-%m-%Y"
                });
            };
        </script>
    </head>
    <body>    
        <div class="grid_12" style="width: 450px;">
            <div class="module">
                <h2><span>Edit your profile</span></h2>

                <div class="module-body">
                    <div id="successNotice"></div>
                    <?php
                    if (isset($empId)) {
                        if (isset($_POST['btnFrmUserUpdate']) && !empty($_POST['btnFrmUserUpdate'])) {
                            $empName = mysqli_real_escape_string($con, $_POST['txtEmpName']);
                            $empUserId = mysqli_real_escape_string($con, $_POST['txtUserId']);
                            $empPass = mysqli_real_escape_string($con, $_POST['txtPassword']);
                            $empDob = mysqli_real_escape_string($con, $_POST['txtDob']);
                            $formattedDob = date("Y-m-d", strtotime($empDob));
                            $empEmail = mysqli_real_escape_string($con, $_POST['txtEmail']);
                            $empMobile = mysqli_real_escape_string($con, $_POST['txtMobile']);
                            $empAddress = mysqli_real_escape_string($con, $_POST['txtAddress']);

                            $sqlUpdate = "UPDATE `tbl_employee` SET `empl_userId` = '$empUserId', `empl_name` = '$empName', `empl_dob` = '$formattedDob', `empl_email` = '$empEmail', `empl_mobile` = '$empMobile', `empl_address` = '$empAddress', `password` = '$empPass' WHERE `tbl_employee`.`empl_id` = $empId;";
                            mysqli_query($con, $sqlUpdate);

                            if (mysqli_affected_rows($con) > 0) {
                                ?>
                                <script>
                                    var msgDiv = document.getElementById("successNotice");
                                    msgDiv.innerHTML = "";
                                    var msgSpan = document.createElement("span");
                                    msgSpan.setAttribute("class", "notification n-success");
                                    msgSpan.innerHTML = "Profile details updated successfully";
                                    msgDiv.appendChild(msgSpan);
                                </script>
                                <?php
                            } else {
                                ?>
                                <script>
                                    var msgDiv = document.getElementById("successNotice");
                                    msgDiv.innerHTML = "";
                                    var msgSpan = document.createElement("span");
                                    msgSpan.setAttribute("class", "notification n-error");
                                    msgSpan.innerHTML = "No updation occured.";
                                    msgDiv.appendChild(msgSpan);
                                </script>
                                <?php
                            }
                        }


                        $sqlFetch = "SELECT * FROM `tbl_employee` WHERE `empl_id` = $empId ";
                        $resUserDetails = mysqli_query($con, $sqlFetch);
                        $row = mysqli_fetch_array($resUserDetails);
                        ?>
                        <form name='frmEditUserDetails' id='frmEditUserDetails' onsubmit="return validateUserDetials();" method="post" action="">        
                            <p>
                                <label>Employees's Name</label>
                                <input type='text' name='txtEmpName' class='input-short' value="<?php echo $row['empl_name']; ?>" placeholder="Enter Employee's Name" style='width: 85%' autofocus /> 
                            </p> 
                            <p>
                                <label>User ID</label>
                                <input type='text' name='txtUserId' class='input-short' value="<?php echo $row['empl_userId']; ?>" placeholder="Enter only Alphanumeric User ID" style='width: 85%' readonly /> 
                            </p> 
                            <p>
                                <label>Password</label>
                                <input type='text' name='txtPassword' class='input-short' value="<?php echo $row['password']; ?>" placeholder="Enter Password" style='width: 85%' /> 
                            </p>                           
                            <p>
                                <label>Date of Birth</label>
                                <input type="text" name='txtDob' size="12" id="inputField" class='input-short' value="<?php echo date("d-m-Y", strtotime($row['empl_dob'])); ?>" placeholder="Click here to select" style='width: 60%' onfocus="showDatePicker('inputField');" />
                            </p>
                            <p>
                                <label>E-mail</label>
                                <input type='text' name='txtEmail' class='input-short' value="<?php echo $row['empl_email']; ?>" placeholder="Enter User's E-mail Address" style='width: 85%' /> 
                            </p> 
                            <p>
                                <label>Mobile</label>
                                <input type='text' name='txtMobile' class='input-short' value="<?php echo $row['empl_mobile']; ?>" placeholder="Enter User's Mobile Number" style='width: 85%' /> 
                            </p> 
                            <p>
                                <label>Address</label>
                                <input type='text' name='txtAddress' class='input-short' value="<?php echo $row['empl_address']; ?>" placeholder="Enter User's Address" style='width: 85%' /> 
                            </p>

                            <fieldset id="fldUserBtnContainer">
                                <input class="submit-green" type="submit" name="btnFrmUserUpdate" value="Update" /> 
                                <input class="submit-gray" type="reset" name="btnFrmUserClear" value="Clear" onclick="javascript: document.frmEditUserDetails.txtEmpName.focus();"/>
                            </fieldset>            
                        </form>                        
                        <script>
                            function validateUserDetials() {
                                var msgDiv = document.getElementById("successNotice");
                                msgDiv.innerHTML = "";
                                var msgSpan = document.createElement("span");
                                msgDiv.appendChild(msgSpan);
                                if (document.frmEditUserDetails.txtEmpName.value === "") {
                                    msgSpan.setAttribute("class", "notification n-error");
                                    msgSpan.innerHTML = "Employee Name field should not empty.";
                                    document.frmEditUserDetails.txtEmpName.focus();
                                    return false;
                                }
                                else if (document.frmEditUserDetails.txtUserId.value === "") {
                                    msgSpan.setAttribute("class", "notification n-error");
                                    msgSpan.innerHTML = "User ID field should not empty.";
                                    document.frmEditUserDetails.txtUserId.focus();
                                    return false;
                                }
                                else if (document.frmEditUserDetails.txtPassword.value === "") {
                                    msgSpan.setAttribute("class", "notification n-error");
                                    msgSpan.innerHTML = "Password field should not empty.";
                                    document.frmEditUserDetails.txtPassword.focus();
                                    return false;
                                }
                                else if (document.frmEditUserDetails.txtDob.value === "") {
                                    msgSpan.setAttribute("class", "notification n-error");
                                    msgSpan.innerHTML = "Date of Birth field should not empty.";
                                    document.frmEditUserDetails.txtDob.focus();
                                    return false;
                                }
                                else if (document.frmEditUserDetails.txtEmail.value === "") {
                                    msgSpan.setAttribute("class", "notification n-error");
                                    msgSpan.innerHTML = "Email field should not empty.";
                                    document.frmEditUserDetails.txtEmail.focus();
                                    return false;
                                }
                                else if (document.frmEditUserDetails.txtMobile.value === "") {
                                    msgSpan.setAttribute("class", "notification n-error");
                                    msgSpan.innerHTML = "Mobile No field should not empty.";
                                    document.frmEditUserDetails.txtMobile.focus();
                                    return false;
                                }
                                else if (document.frmEditUserDetails.txtAddress.value === "") {
                                    msgSpan.setAttribute("class", "notification n-error");
                                    msgSpan.innerHTML = "Address field should not empty.";
                                    document.frmEditUserDetails.txtAddress.focus();
                                    return false;
                                }
                            }
                        </script> 
                        <?php
                    } else {
                        ?>
                        <script>
                            var msgDiv = document.getElementById("successNotice");
                            msgDiv.innerHTML = "";
                            var msgSpan = document.createElement("span");
                            msgSpan.setAttribute("class", "notification n-error");
                            msgSpan.innerHTML = "Please login before accessing this page.";
                            msgDiv.appendChild(msgSpan);
                        </script>
                        <?php
                    }
                    ?>        
                </div> <!-- End .module-body -->

            </div>  <!-- End .module -->
        </div>        
    </body>
</html>
