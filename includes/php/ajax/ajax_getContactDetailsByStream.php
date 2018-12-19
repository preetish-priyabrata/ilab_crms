<?php
include_once '../db.php';
if (isset($_GET['streamName']) && !empty($_GET['streamName'])) {
    $comp_id = $_GET['compId'];
    $stream = $_GET['streamName'];
    ?>    
    <input type="hidden" name="hidContactCompanyId" value ="<?php echo $comp_id; ?>" />
    <input type="hidden" name="hidContactStreamId" value ="<?php echo $stream; ?>" />
    <input type="hidden" name="hidReportFor" value="companyStream" />
    <div class="module">
        <h2><span>Contact Details</span></h2>
        <div class="module-table-body">                           
            <table class="tablesorter" style="width: 1026px;" id='contactDetailContainer'>    
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
                    $contactSql = "SELECT * , comp_name FROM tbl_contact tc, tbl_office tof, tbl_company tcom, tbl_stream ts WHERE tc.offi_id = tof.offi_id and tc.comp_id = tcom.comp_id and tc.cont_id=ts.cont_id and ts.stream='$stream' and tc.comp_id=$comp_id order by tc.cont_id asc";
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
                                <td><?php echo $i ?></td>
                                <td><?php echo $rowContactDetail['cont_name'] ?></td>
                                <td><?php echo $rowContactDetail['comp_name'] ?></td>
                                <td><?php
                                    echo "Email: " . $rowContactDetail['cont_email'] . "<hr />Mobile:" . $rowContactDetail['cont_mobile'] . "<hr />Direct: " . $rowContactDetail['cont_direct'] . "<hr />Extension: " . $rowContactDetail['cont_ext']
                                    ?>
                                </td>
                                <td><?php echo "Board Line : " . $rowContactDetail['offi_boardline']; ?></td>
                                <td><?php echo "Designation: " . $rowContactDetail['cont_desg'] . "<br />Department: " . $rowContactDetail['cont_dept'] ?></td>                                        
                                <td>
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
            </table>
            <div style="padding-bottom: 10px;">
                <center><input class='submit-green' type='submit' id='btnReportSubmit' name='btnReportSubmit'  value='Download'/></center>
            </div>
        </div>
    </div>                    
    <?php
}
?>
