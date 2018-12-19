<?php

include_once '../db.php';

if($_GET['permission']=="yes" && isset($_GET['contId'])){
    $cont_id = $_GET['contId'];
    $delete_query = "DELETE FROM `tbl_contact` WHERE `cont_id`=$cont_id";
    $res = mysqli_query($con, $delete_query);
    
    if($res){
        echo "success";
    }    
}

