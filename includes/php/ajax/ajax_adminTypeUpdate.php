<?php
include_once '../db.php';

if (isset($_POST['btnAdminSubmit']) && !empty($_POST['btnAdminSubmit'])) {
    $typeName = mysqli_real_escape_string($con, $_POST['txtType']);
    $typeName = str_replace("*()", "+", $typeName);
    $typeStatus = $_POST['cmbTypeStatus'];    
    $typeId = $_POST['hidTypeId'];
    $type = $_POST['type'];    
    
    if($type == 'comp_type'){
        $existingName = $_POST['settingValue'];
        $sql1 = "UPDATE `tbl_company` SET `comp_type` = '$typeName' WHERE `tbl_company`.`comp_type` = '$existingName';";
        mysqli_query($con, $sql1);
    }
        
    $sql = "UPDATE `tbl_setting` SET `sett_value` = '$typeName', `sett_status` = '$typeStatus' WHERE `tbl_setting`.`sett_id` = $typeId;";

    $update_res = mysqli_query($con, $sql);

    if ($update_res) {
        echo "Success";
    } else {
        echo "Fail";
    }
}
?>
