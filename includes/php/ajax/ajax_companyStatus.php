<?php

include_once '../db.php';

if (isset($_GET['status']) && !empty($_GET['status'])) {
    $status = $_GET['status'];

    $tbody = '';
    if (isset($_GET['stream']) && !empty($_GET['stream'])) {
        $stream = $_GET['stream'];
        $str = implode("', '", $stream);
        $sql = "select comp_name, comp_type,act_statusComment,empl_name from tbl_company tco,tbl_emp_contact tec, tbl_employee te where
          tco.comp_id=tec.comp_id and tec.cont_id in (SELECT distinct(`cont_id`) FROM `tbl_stream` WHERE stream in ('$str')) and
          tec.act_newStatus='$status' and tec.empl_id=te.empl_id order by comp_name asc";
    } else {
        $sql = "select comp_name, comp_type,act_statusComment,empl_name from tbl_company tco,tbl_emp_contact tec, tbl_employee te where
          tco.comp_id=tec.comp_id and
          tec.act_newStatus='$status' and tec.empl_id=te.empl_id order by comp_name asc";
    }
    $res = mysqli_query($con, $sql);
    $companyLst = array();
    if (isset($_GET['year']) && isset($_GET['month']) && $_GET['month'] != 'Select a Month' && $_GET['year'] != 'Select a Year') {
        $year = $_GET['year'];
        $month = $_GET['month'];

        if (mysqli_num_rows($res) > 0) {
            $row_no = 1;
            $row_color = '';
            $count = 0;
            while ($row = mysqli_fetch_array($res)) {
                if ($row_no % 2 == 0)
                    $row_color = "odd";
                else
                    $row_color = "even";
                $comapnyName = $row['comp_name'];
                $companyType = $row['comp_type'];
                $comment = $row['act_statusComment'];
                $date = explode(",", $comment);
                $empName = $row['empl_name'];
                $companyEmp = $comapnyName.$empName;
                if ($date[1] == $year) {
                    if ($date[0] == $month) {
                        //$tbody .= $date[1];
                        if (!in_array($companyEmp, $companyLst)) {
                            $tbody .= "<tr class='$row_color'><td >$row_no</td><td >$comapnyName</td><td>$companyType</td><td>$comment</td><td>$empName</td></tr>";
                            array_push($companyLst, $companyEmp);
                        }
                        $count++;
                        $row_no++;
                    }
                }                
            }
            if ($count == 0) {
                $tbody .= "<tr><td colspan='5'>No Company List Found.</td></tr>";
            }
        } else {
            $tbody .= "<tr><td colspan='5'>No Company List Found.</td></tr>";
        }
    } else {
        if (mysqli_num_rows($res) > 0) {
            $row_no = 1;
            $row_color = '';
            while ($row = mysqli_fetch_array($res)) {
                if ($row_no % 2 == 0)
                    $row_color = "odd";
                else
                    $row_color = "even";
                $comapnyName = $row['comp_name'];
                $companyType = $row['comp_type'];
                $comment = $row['act_statusComment'];
                $empName = $row['empl_name'];
                $companyYear = $comapnyName.$comment.$empName;
                if (!in_array($companyYear, $companyLst)) {
                    $tbody .= "<tr class='$row_color'><td >$row_no</td><td >$comapnyName</td><td>$companyType</td><td>$comment</td><td>$empName</td></tr>";                    
                   array_push($companyLst, $companyYear);
                   $row_no++;
                }                
            }
        } else {
            $tbody .= "<tr><td colspan='5'>No Company List Found.</td></tr>";
        }
    }
    echo "<h2><span>Company With $status Response</span></h2>
            <div class='module-table-body' style='background:none;'>
                <table>
                    <thead>
                        <tr>
                            <th style='width:8%'>Sl. No</th>
                            <th style='width:30%'>Company Name</th>
                            <th style='width:20%'>Company Type</th>
                            <th style='width:23%'>Expected Date/Comment</th>
                            <th style='width:20%'>Employee</th>
                        </tr>
                    </thead>
                    <tbody>
                        $tbody
                    </tbody>
                </table>
            </div>";
}
?>
