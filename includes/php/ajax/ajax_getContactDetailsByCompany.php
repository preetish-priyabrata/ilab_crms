<?php
include_once '../db.php';
session_start();
if (isset($_GET['emplId']) && !empty($_GET['emplId'])) {
    $empId = $_GET['emplId'];
} else {
    $empId = $_SESSION['empId'];
}
if (isset($_GET['compId']) && !empty($_GET['compId'])) {
    $company_id = $_GET['compId'];
    if ($company_id == "all") {
        ?>
        <tbody style="height: 242px;overflow-y: auto;display: block;">
            <tr class="even" style="font-weight: bold;">
                <td>#</td>
                <td>Name</td>
                <td>Company</td>
                <td>Primary Contacts</td>
                <td>Other Contacts</td>
                <td>Job Position</td>                                    
                <td>Office Details</td>                                    
                <td>Status</td>
                <td>Event</td>
            </tr> 
            <?php
            $contactSql = "SELECT * , comp_name FROM tbl_contact tc, tbl_office tof, tbl_company tcom WHERE tc.offi_id = tof.offi_id and tc.comp_id = tcom.comp_id and `empl_id`=$empId order by cont_name asc";
            $contactRes = mysqli_query($con, $contactSql);
            $i = 1;
            if (mysqli_num_rows($contactRes) > 0) {
                while ($rowContactDetail = mysqli_fetch_array($contactRes)) {
                    if ($i % 2 == 0)
                        $rowType = 'class="even"';
                    else
                        $rowType = 'class="odd"';
                    ?>
                    <tr <?php echo $rowType; ?>>
                        <td style="width: 20px"><?php echo $i ?></td>
                        <td style="width: 162px"><?php echo $rowContactDetail['cont_name'] ?></td>
                        <td style="width: 138px"><?php echo $rowContactDetail['comp_name'] ?></td>
                        <td style="width: 220px"><?php
                            echo "Email: " . $rowContactDetail['cont_email'] . "<hr />Mobile:" . $rowContactDetail['cont_mobile'] . "<hr />Direct: " . $rowContactDetail['cont_direct'] . "<hr />Extension: " . $rowContactDetail['cont_ext']
                            ?>
                        </td>
                        <td style="width: 188px"><?php echo "Board Line : " . $rowContactDetail['offi_boardline']; ?></td>
                        <td style="width: 130px"><?php echo "Designation: " . $rowContactDetail['cont_desg'] . "<br />Department: " . $rowContactDetail['cont_dept'] ?></td>                                        
                        <td style="width: 169px">
                            <?php
                            echo $rowContactDetail['offi_address'] . "<br />" . $rowContactDetail['offi_city'] . ", " . $rowContactDetail['offi_country'];
                            if ($rowContactDetail['offi_pin'] != "") {
                                echo "PIN: " . $rowContactDetail['offi_pin'];
                            }
                            ?>
                        </td>                                        
                        <td>
                            <?php
                            $cont_id = $rowContactDetail['cont_id'];
                            $sqlStatus = "select act_newStatus from tbl_emp_contact where empl_cont_id = (SELECT MAX( empl_cont_id ) FROM tbl_emp_contact where cont_id=$cont_id )";
                            $resStatus = mysqli_query($con, $sqlStatus);
                            $status = mysqli_fetch_array($resStatus);
                            echo $status[0];
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($rowContactDetail['cont_conclave'] == "") {
                                echo "No Event";
                            } else {
                                echo $rowContactDetail['cont_conclave'];
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
            } else {
                echo "<tr><td colspan='9'>No contact found.</td></tr>";
            }
            ?>
        </tbody>
        <?php
    } else {
        ?>                            
        <tbody style="height: 242px;overflow-y: auto;display: block;">
            <tr class="even" style="font-weight: bold;">
                <td>#</td>
                <td>Name</td>
                <td>Company</td>
                <td>Primary Contacts</td>
                <td>Other Contacts</td>
                <td>Job Position</td>                                    
                <td>Office Details</td>                                    
                <td>Status</td>
                <td>Event</td>
            </tr> 
            <?php
            $contactSql = "SELECT * , comp_name FROM tbl_contact tc, tbl_office tof, tbl_company tcom WHERE tc.offi_id = tof.offi_id and tc.comp_id = tcom.comp_id and `empl_id`=$empId and tc.comp_id=$company_id order by cont_name asc";
            $contactRes = mysqli_query($con, $contactSql);
            $i = 1;
            if (mysqli_num_rows($contactRes) > 0) {
                while ($rowContactDetail = mysqli_fetch_array($contactRes)) {
                    if ($i % 2 == 0)
                        $rowType = 'class="even"';
                    else
                        $rowType = 'class="odd"';
                    ?>
                    <tr <?php echo $rowType; ?>>
                        <td style="width: 20px"><?php echo $i ?></td>
                        <td style="width: 162px"><?php echo $rowContactDetail['cont_name'] ?></td>
                        <td style="width: 138px"><?php echo $rowContactDetail['comp_name'] ?></td>
                        <td style="width: 220px"><?php
                            echo "Email: " . $rowContactDetail['cont_email'] . "<hr />Mobile:" . $rowContactDetail['cont_mobile'] . "<hr />Direct: " . $rowContactDetail['cont_direct'] . "<hr />Extension: " . $rowContactDetail['cont_ext']
                            ?>
                        </td>
                        <td style="width: 188px"><?php echo "Board Line : " . $rowContactDetail['offi_boardline']; ?></td>
                        <td style="width: 130px"><?php echo "Designation: " . $rowContactDetail['cont_desg'] . "<br />Department: " . $rowContactDetail['cont_dept'] ?></td>                                        
                        <td style="width: 169px">
                            <?php
                            echo $rowContactDetail['offi_address'] . "<br />" . $rowContactDetail['offi_city'] . ", " . $rowContactDetail['offi_country'];
                            if ($rowContactDetail['offi_pin'] != "") {
                                echo "PIN: " . $rowContactDetail['offi_pin'];
                            }
                            ?>
                        </td>                                        
                        <td>
                            <?php
                            $cont_id = $rowContactDetail['cont_id'];
                            $sqlStatus = "select act_newStatus from tbl_emp_contact where empl_cont_id = (SELECT MAX( empl_cont_id ) FROM tbl_emp_contact where cont_id=$cont_id )";
                            $resStatus = mysqli_query($con, $sqlStatus);
                            $status = mysqli_fetch_array($resStatus);
                            echo $status[0];
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($rowContactDetail['cont_conclave'] == "") {
                                echo "No Event";
                            } else {
                                echo $rowContactDetail['cont_conclave'];
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
            } else {
                echo "<tr><td  colspan='9'>No contact found.</td></tr>";
            }
            ?>
        </tbody>
        <?php
    }
}    