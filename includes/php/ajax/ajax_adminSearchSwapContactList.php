<?php

include_once '../db.php';
if (isset($_GET['keyword']) && !empty($_GET['empId'])) {
    $empId = $_GET['empId'];
    $keyword = $_GET['keyword'];    
        $sql = "SELECT `cont_id`,`cont_name`,`comp_name`  FROM `tbl_contact` as tcon, `tbl_company` as tcom WHERE tcon.`empl_id`= $empId  and tcon.comp_id=tcom.comp_id and tcon.cont_name like '%$keyword%' order by `cont_name` asc";
        $res = mysqli_query($con, $sql);
        $table_body = "";
        if (mysqli_num_rows($res) > 0) {
            $row_no = 1;
            while ($row = mysqli_fetch_array($res)) {
                if ($row_no % 2 == 0)
                    $row_color = "odd";
                else
                    $row_color = "even";
                $table_body .= "<tr class='$row_color'><td style='width:5%'><input type='checkbox' value='" . $row[0] . "' onclick='deselectAllSwapCheck();' /></td><td>$row[1]</td><td>$row[2]</td></tr>";
                $row_no++;
            }
        }
        else {
            $table_body .= "<tr><td colspan='3'><i>No contacts available.</i></td></tr>";
        }        
        echo "$table_body";    
} 

