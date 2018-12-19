<?php

require_once '../db.php';
if (isset($_POST['btnFrmUserAdd']) && !empty($_POST['btnFrmUserAdd'])) {
    $employeeName = mysqli_real_escape_string($con, $_POST['txtEmpName']);
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
    $active = "Active";
    //$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    //$password = substr( str_shuffle( $chars ), 0, 8 );

    $sql = "INSERT INTO `tbl_employee` (`empl_id`, `empl_userId`, `empl_name`, `empl_type`, `empl_dob`, `empl_email`, `empl_mobile`, `empl_address`, `empl_status`, `password`) VALUES (NULL,  '$userId', '$employeeName', '$userType', '$dob', '$email', '$mobile', '$address', '$active', '$password');";
    
    $res = mysqli_query($con, $sql);    
    
    if ($res)
        echo "Success";
    else
        echo "Fail";
}
?>
