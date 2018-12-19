<?php

include_once '../db.php';
if (isset($_GET['selectedContactId']) && !empty($_GET['selectedContactId'])) {
    $contIdLst = $_GET['selectedContactId'];
    $empId = $_GET['empId'];
    //$qryDelete = "DELETE FROM authors WHERE AuthorNo IN($authors)"; 
    $sql = "UPDATE `tbl_contact` SET `empl_id`=$empId WHERE `cont_id` in ($contIdLst)";
    $res = mysqli_query($con, $sql);    
    if (mysqli_affected_rows($con) > 0) {        
        echo "Success";
    } else {
        echo "Fail";
    }
}
?>
