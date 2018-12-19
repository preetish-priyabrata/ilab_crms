<?php
include_once '../db.php';

if (isset($_POST['btnAdminCmpSubmit']) && !empty($_POST['btnAdminCmpSubmit'])) {
    $comp_name = mysqli_real_escape_string($con, $_POST['txtCmpName']);
    $company_type = mysqli_real_escape_string($con, $_POST['cmbCmpType']);
    $company_website = mysqli_real_escape_string($con, $_POST['txtWebSite']);    
    $hidCmpId = $_POST['hidCmpId'];
    $company_name = str_replace("zxtwuqmtz","&",$comp_name);
    
$sql = "UPDATE `tbl_company` SET `comp_name` = '$company_name', `comp_type` = '$company_type', `comp_website` = '$company_website' WHERE `tbl_company`.`comp_id` = $hidCmpId;";

    $update_res = mysqli_query($con, $sql);

    if ($update_res) {
        echo "Success";
    } else {
        echo "Fail";
    }
}
?>
