<?php
include_once '../db.php';

$category_id = 0;
$category_name = "";
if (isset($_GET['categoryId']) && !empty($_GET['categoryId'])) {
    $category_id = $_GET['categoryId'];
    $category_name = $_GET['categoryName'];
}
if ($category_name == "employee") {
    ?>
    <input type="hidden" name="hidEmlpId" value ="<?php echo $category_id; ?>" />
    <input type="hidden" name="hidReportFor" value="employee" />
    <p>
        <label>Select a Company Name</label>
        <select class="input-short" style='width: 70%' name="cmbCompLst" onchange="displayContactDetailsByCompany(this.value,<?php echo $category_id; ?>);">                 
            <option value="all">All Companies</option>
            <?php
            $sql_comp_lst = "SELECT `comp_id`,`comp_name` FROM `tbl_company` WHERE `comp_id` IN (SELECT distinct(comp_id) FROM `tbl_contact` WHERE empl_id=$category_id)";
            $res_comp_lst = mysqli_query($con, $sql_comp_lst);

            while ($row_comp_lst = mysqli_fetch_array($res_comp_lst)) {
                echo "<option value='$row_comp_lst[0]'>$row_comp_lst[1]</option>";
            }
            ?>
        </select>
    </p>                                                                                                                  

    <div class="grid_6" style="width: 100%;height: 370px; margin-left: 0px;" id="divContactDetails">
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
                        $contactSql = "SELECT * , comp_name FROM tbl_contact tc, tbl_office tof, tbl_company tcom WHERE tc.offi_id = tof.offi_id and tc.comp_id = tcom.comp_id and `empl_id`=$category_id order by cont_name asc";
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
                                        if($rowContactDetail['cont_conclave'] == ""){
                                            echo "No Event";
                                        }else{
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
    </div>
    <?php
} 
else if ($category_name == "company") {
    ?>
    <input type="hidden" name="hidCompanyId" value ="<?php echo $category_id; ?>" />
    <input type="hidden" name="hidReportFor" value="company" />
    <div class="grid_6" style="width: 100%;height: 370px; margin-left: 0px;" id="divContactDetails">
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
                        $contactSql = "SELECT * , comp_name FROM tbl_contact tc, tbl_office tof, tbl_company tcom WHERE tc.offi_id = tof.offi_id and tc.comp_id = tcom.comp_id and tc.comp_id=$category_id order by cont_name asc";
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
                                        if($rowContactDetail['cont_conclave'] == ""){
                                            echo "No Event";
                                        }else{
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
    </div>
    <?php
} 
else if ($category_name == "stream") {
    ?>
    <input type="hidden" name="hidStreamId" value ="<?php echo $category_id; ?>" />
    <input type="hidden" name="hidReportFor" value="stream" />
    <div class="grid_6" style="width: 100%;height: 370px; margin-left: 0px;" id="divContactDetails">
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
                        $contactSql = "SELECT * , comp_name FROM tbl_contact tc, tbl_office tof, tbl_company tcom, tbl_stream ts WHERE tc.offi_id = tof.offi_id and tc.comp_id = tcom.comp_id and tc.cont_id=ts.cont_id and ts.stream='$category_id' order by tc.cont_id asc";
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
                                        if($rowContactDetail['cont_conclave'] == ""){
                                            echo "No Event";
                                        }else{
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
    </div>
    <?php
} 
else if ($category_name == "companyStream") {
    ?>
    <p>
        <label>Select a Stream Name</label>
        <select class="input-short" style='width: 40%' name="cmbCompLst" id="cmbCompLst" onchange="displayContactDetailsByStream(this.value);">                             
            <?php
            $sql_comp_lst = "select distinct(ts.stream) from tbl_stream ts, tbl_contact tcon, tbl_company tcom where ts.cont_id=tcon.cont_id and tcon.comp_id=tcom.comp_id and tcom.comp_id=$category_id";
            $res_comp_lst = mysqli_query($con, $sql_comp_lst);

            if (mysqli_num_rows($res_comp_lst) == 0) {
                ?><option value="all">No Stream Available</option><?php
            } else {
                ?>
                <option value="all">Select a stream</option>
                <?php
                while ($row_comp_lst = mysqli_fetch_array($res_comp_lst)) {
                    echo "<option value='$row_comp_lst[0]'>$row_comp_lst[0]</option>";
                }
            }
            ?>
        </select>
    </p>                                                                                                                  

    <div class="grid_6" style="width: 100%;height: 370px; margin-left: 0px;" id="divContactDetails">

    </div>
    <?php
} 
else {
    ?>  
    <input type="hidden" name="hidEventValue" value ="<?php echo $category_id; ?>" />
    <input type="hidden" name="hidReportFor" value="event" />
    <div class="grid_6" style="width: 100%;height: 370px; margin-left: 0px;" id="divContactDetails">
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
                        $contactSql = "SELECT *, comp_name FROM tbl_contact tc, tbl_office tof, tbl_company tcom WHERE tc.offi_id = tof.offi_id and tc.comp_id = tcom.comp_id and `cont_conclave` LIKE '$category_id'";
                        $contactRes = mysqli_query($con, $contactSql);
                        $i = 1;
                        if (mysqli_num_rows($contactRes) > 0) {
                            while ($rowContactDetail = mysqli_fetch_array($contactRes)) {
                                if ($i % 2 == 0) {
                                    $rowType = 'class="even"';
                                } else {
                                    $rowType = 'class="odd"';
                                }
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
                                        if($rowContactDetail['cont_conclave'] == ""){
                                            echo "No Event";
                                        }else{
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
    </div>
    <?php
} 

