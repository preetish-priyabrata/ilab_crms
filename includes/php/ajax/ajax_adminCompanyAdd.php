<?php
include_once '../db.php';

if (isset($_GET['btnAdminCmpSubmit']) && !empty($_GET['btnAdminCmpSubmit'])) {

    $comp_name = mysqli_real_escape_string($con, $_GET['txtCmpName']);
    $company_type = mysqli_real_escape_string($con, $_GET['cmbCmpType']);
    $company_website = mysqli_real_escape_string($con, $_GET['txtWebSite']);    
    $company_name = str_replace("zxtwuqmtz","&",$comp_name);
    
    $sql = "INSERT INTO `tbl_company` (`comp_id`, `comp_name`, `comp_type`, `comp_website`, `comp_logo`) VALUES (NULL, '$company_name', '$company_type', '$company_website', '');";
    $insert_res = mysqli_query($con, $sql);

    if ($insert_res) {
        echo "Success";
    } else {
        echo "Fail";
    }
}
?>
