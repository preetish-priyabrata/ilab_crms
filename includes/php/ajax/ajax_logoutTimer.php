<?php
                            
session_start();
include_once "../db.php";
$login_id = $_SESSION['login_id'];
$now = date('Y-m-d H:i:s'); 
$logout_sql = "UPDATE `tbl_login` SET `logout` = '$now' WHERE `tbl_login`.`login_id` = $login_id;";
mysqli_query($con, $logout_sql);


if (isset($_GET['infoType']) && !empty($_GET['infoType'])) {
    $event_name = $_GET['infoType'];
    $empId = $_SESSION['empId'];
    $currDate = date("Y-m-d");
    if ($event_name == "changeDate") {
        $login_sql = "INSERT INTO `tbl_login` (`login_id`, `login`, `logout`, `login_date`, `empl_id`) VALUES (NULL, '$now', '', '$currDate', '$empId');";
        $login_res = mysqli_query($con, $login_sql);
        $_SESSION['login_id'] = mysqli_insert_id($con);
        if($login_res){
            echo "Success";
        }
    }
}
?>

