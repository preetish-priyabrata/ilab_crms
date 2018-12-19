<?php

include_once '../db.php';

if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $key = mysqli_real_escape_string($con, $_GET['keyword']);
    $keyword = str_replace("zxtwuqmtz","&",$key);
    $callFunc = $_GET['callFunc'];
    if ($callFunc == 'search') {
        $sql = "select distinct(cont_name) from tbl_contact where cont_name like '%$keyword%' "
                . "union select distinct(cont_email) from tbl_contact where `cont_email` like '%$keyword%' "
                . "union select distinct(sett_value) from tbl_setting where `sett_value` like '%$keyword%' and sett_type = 'comp_type' "
                . "union select distinct(cont_mobile) from tbl_contact where `cont_mobile` like '%$keyword%' "
                . "union select distinct(cont_direct) from tbl_contact where `cont_direct` like '%$keyword%' "
                . "union select distinct(cont_ext) from tbl_contact where `cont_ext` like '%$keyword%' "
                . "union select distinct(cont_desg) from tbl_contact where `cont_desg` like '%$keyword%' "
                . "union select distinct(comp_name) from tbl_company tcom, tbl_contact tcon where `comp_name` like '%$keyword%' and tcom.comp_id=tcon.comp_id "
                . "union select distinct(comp_website) from tbl_company tcom, tbl_contact tcon where `comp_website` like '%$keyword%' and tcom.comp_id=tcon.comp_id "
                . "union select distinct(offi_boardline) from tbl_office tof, tbl_contact tcon where `offi_boardline` like '%$keyword%' and tof.offi_id =tcon.offi_id "
                . "union select distinct(offi_address) from tbl_office tof, tbl_contact tcon where `offi_address` like '%$keyword%'  and tof.offi_id =tcon.offi_id "
                . "union select distinct(offi_city) from tbl_office tof, tbl_contact tcon where `offi_city` like '%$keyword%' and tof.offi_id =tcon.offi_id order by cont_name asc";
        $res = mysqli_query($con, $sql);
        echo "<table width='100%' style='border: 1px ridge gray; border-top-style: hidden;'>";
        if (mysqli_num_rows($res)) {
            while ($row = mysqli_fetch_array($res)) {
                echo "<tr class='companySuggestTr'><td style='cursor: pointer; border-top: 1px solid gray; border-right: 1px solid gray;' onmousedown=\"setText('" . $row[0] . "','" . $row[0] . "','" . $callFunc . "');\">" . $row[0] . "</td></tr>";
            }
        } else {
            echo "<tr><td style='cursor: pointer; border-top: 1px solid black; border-right: 1px solid gray;' onmousedown=\"setText('no','NA');\">Your search did not match with any Contact Details.</td></tr>";
        }
    } else {
        $sql = "SELECT `comp_id`,`comp_name` FROM `tbl_company` where `comp_name` like '%$keyword%' order by comp_name asc LIMIT 0, 10 ";
        
        $res = mysqli_query($con, $sql);
        echo "<table width='100%' style='border: 1px ridge gray; border-top-style: hidden;'>";
        if (mysqli_num_rows($res)) {
            while ($row = mysqli_fetch_array($res)) {
                echo "<tr class='companySuggestTr'><td style='cursor: pointer; border-top: 1px solid gray; border-right: 1px solid gray;' onmousedown=\"setText('" . $row[1] . "','" . $row[0] . "','" . $callFunc . "');\">" . $row[1] . "</td></tr>";
            }
        } else {
            echo "<tr><td style='cursor: pointer; border-top: 1px solid black; border-right: 1px solid gray;' onmousedown=\"setText('no','NA');\">No Company found</td></tr>";
        }
    }
    echo "</table>";
}
?>
