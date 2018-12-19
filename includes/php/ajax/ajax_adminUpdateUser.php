<?php

require_once '../db.php';
if (isset($_POST['btnFrmUserAdd']) && !empty($_POST['btnFrmUserAdd'])) {
    $empName = mysqli_real_escape_string($con, $_POST['txtEmpName']);
    $userId = mysqli_real_escape_string($con, $_POST['txtUserId']);
    $password = mysqli_real_escape_string($con, $_POST['txtPassword']);
    $userType = mysqli_real_escape_string($con, $_POST['cmbUserType']);
    $dob = mysqli_real_escape_string($con, $_POST['txtDob']);
    $dobDay = substr($dob, 0, 2);
    $dobMonth = substr($dob, 3, 2);
    $dobYear = substr($dob, 6, 4);
    $dob = $dobYear . "-" . $dobMonth . "-" . $dobDay;
    $email = mysqli_real_escape_string($con, $_POST['txtEmail']);
    $mobile = mysqli_real_escape_string($con, $_POST['txtMobile']);
    $address = mysqli_real_escape_string($con, $_POST['txtAddress']);
    $empId = $_POST['userId'];
    $active = $_POST['cmbUserStatus'];

    $sql = "UPDATE `tbl_employee` SET `empl_name` = '$empName',`empl_userId` = '$userId', `empl_type` = '$userType', `empl_dob` = '$dob', `empl_email` = '$email', `empl_mobile` = '$mobile', `empl_address` = '$address', `empl_status` = '$active', `password` = '$password'  WHERE `tbl_employee`.`empl_id` = $empId;";

    $update_res = mysqli_query($con, $sql);    
    
    if (mysqli_affected_rows($con)>0) {
        echo "Success";
    } else {
        echo "Fail";
    }
}
?>
