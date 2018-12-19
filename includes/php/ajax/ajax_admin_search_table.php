<?php

include_once '../admin_pagination.php';
$value = $_GET['value'];
$value = str_replace("zxtwuqmtz","&",$value);
$value = str_replace("*()", "+", $value);
$type = $_GET['type'];
if ($type == "company") {
    $_SESSION['sql'] = "SELECT comp_id, comp_name, comp_type, comp_website, comp_type FROM tbl_company where comp_name like '%$value%' ORDER BY comp_name ASC ";
}else if($type == "district"){
    $_SESSION['sql'] = "SELECT * FROM `tbl_address` where `addr_city` like '%$value%' order by addr_city asc";
}else if($type == "company type"){
    $_SESSION['sql'] = "select sett_id, sett_value, sett_status, sett_type from tbl_setting where sett_value like '%$value%' and sett_type='comp_type' order by sett_value asc";
}else if($type == "company type"){
    $_SESSION['sql'] = "select sett_id, sett_value, sett_status, sett_type from tbl_setting where sett_value like '%$value%' and sett_type='comp_type' order by sett_value asc";
}else if ($type == "office type") {
    $_SESSION['sql'] = "select sett_id, sett_value, sett_status, sett_type from tbl_setting where sett_value like '%$value%' and sett_type='offi_type' order by sett_value asc";
}else if ($type == "contact status") {
    $_SESSION['sql'] = "select sett_id, sett_value, sett_status, sett_type from tbl_setting where sett_value like '%$value%' and sett_type='acti_status' order by sett_value asc";    
}else if ($type == "contact relationship") {
    $_SESSION['sql'] = "select sett_id, sett_value, sett_status, sett_type from tbl_setting where sett_value like '%$value%' and sett_type='add_relation' order by sett_value asc";
}else if ($type == "department") {
    $_SESSION['sql'] = "select sett_id, sett_value, sett_status, sett_type from tbl_setting where sett_value like '%$value%' and  sett_type='func_dept' order by sett_value asc";
}else if($type == "salutation"){
    $_SESSION['sql'] = "select sett_id, sett_value, sett_status, sett_type from tbl_setting where sett_value like '%$value%' and sett_type='salute' order by sett_value asc";
}else if($type == "college"){
    $_SESSION['sql'] = "select sett_id, sett_value, sett_status, sett_type from tbl_setting where sett_value like '%$value%' and  sett_type='college' order by sett_value asc";
}else if($type == "stream"){
    $_SESSION['sql'] = "select sett_id, sett_value, sett_status, sett_type from tbl_setting where sett_value like '%$value%' and sett_type='stream' order by sett_value asc";
}else if($type == "user name"){    
    $_SESSION['sql'] = "select empl_id, empl_name, empl_userId, password, empl_type, DATE_FORMAT(empl_dob,\"%d-%m-%Y\"), empl_email, empl_mobile, empl_address, empl_status from tbl_employee WHERE empl_name LIKE '%$value%' order by empl_name asc ";       
}else if($type == "destination"){
    $_SESSION['sql'] = "select sett_id, sett_value, sett_status, sett_type from tbl_setting where sett_value like '%$value%' and sett_type='travel' order by sett_value asc";
}
viewAdminPagination($type);

