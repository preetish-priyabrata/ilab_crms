<?php

include_once '../db.php';

if (isset($_GET['id'])) {
    $companyId = $_GET['id'];

    $sql = "select count(*) from tbl_contact where comp_id = $companyId";
    $res = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($res)) {
        if ($row[0] > 0) {
            echo "$row[0] contacts";
        }
    }
    $sql = "select count(*) from tbl_office where comp_id = $companyId";
    $res = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($res)) {
        if ($row[0] > 0){
            echo " $row[0] offices";
        }
    }
    $sql = "select count(*) from tbl_emp_contact where comp_id = $companyId";
    $res = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($res)) {
        if ($row[0] > 0){
            echo " $row[0] employee contacts details";
        }
    }
    $sql = "select count(*) from tbl_comp_coll where comp_id = $companyId";
    $res = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($res)) {
        if ($row[0] > 0){
            echo " $row[0] company colleges details";
        }
    }
}
