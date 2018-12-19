<?php
include_once '../db.php';
$sqlStream="select sett_value from tbl_setting where sett_type='add_relation'";
        $resStream=  mysqli_query($con, $sqlStream);
        $stream="";
        while($row=  mysqli_fetch_array($resStream)){
            $stream.="<option>".$row[0]."</option>";
        }
        echo $stream;
?>
