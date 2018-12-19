<?php
include_once '../db.php';
include_once '../time.php';

session_start();
$empId = $_SESSION['empId'];
if (isset($_GET['cntAction']) && !empty($_GET['cntAction'])) {
    if ($_GET['cntAction'] == 'download reports') {
        $sqlMember = "select * from tbl_employee where empl_status='Active'";
        $resMember = mysqli_query($con, $sqlMember);
        ?>
        <form method="post" action="includes/php/downloadExcelReport.php">
            <p>
                <label>Please select a Company</label>
                <select name="txtCompSearchBox" class="input-long">
                    <option>All</option>
                    <?php include './ajax_companyComboList.php'; ?>
                </select>
            </p>
            <p>
                <label>Please select a Status</label>
                <select name="status_list" class="input-long">
                    <option>All</option>
                    <?php include './ajax_contactStatusComboList.php'; ?>
                </select>
            </p>
            <p>
                <label>Please select Members</label>
                <?php
                while ($rowMember = mysqli_fetch_array($resMember)) {
                    ?>
                    <input type="checkbox" name="memberList[]" value="<?php echo $rowMember['empl_id'] ?>" /><?php echo $rowMember['empl_name'] ?><br />
                    <?php
                }
                ?>
            </p>
            <p>
                <label>Report Type</label>
                <input type="radio" name="reportType" value="activity" checked="checked" />Activity<br />
                <input type="radio" name="reportType" value="todo" />Schedules / Reminder<br />
                <input type="radio" name="reportType" value="connection" />Connections<br />
            </p>
            <fieldset>
                <input class="submit-green" type="submit" name="btnReportSubmit" value="Download" /> 
                <input class="submit-gray" type="reset"  name="btnTodoClear" value="Clear" />
            </fieldset>   
        </form>
        <?php
    } else if ($_GET['cntAction'] == 'probable visits') {
        ?>
        <div id="notificationMsg"></div>         
        <form method="post" action="includes/php/downloadExcelReportByStatus.php">
            <p>
                <label>Please select a Status for which the data will be downloaded</label>
                <select name="status_list" class="input-long" id="status_list" style="width: 30%;" onchange="checkVisitingStatus(this.value);">
                    <?php include './ajax_contactStatusComboList.php'; ?>
                </select>
            </p>
            <p id="datePicker">                
            </p>
            <p id="streamLst"></p>
            <!--
            <legend>Time Range</legend>                        
            <input type="radio" name="rdoTimeRange" id="rdoRangeAll" checked="checked" value="all" /> All
            <input type="radio" name="rdoTimeRange" id="rdoRangeYear" value="year" /> This year
            <input type="radio" name="rdoTimeRange" id="rdoRangeMonth" value="month" /> This month     
            -->
            <div style="width: 100%"></div>                      
            <input class="submit-green" type="button" name="btnReportSubmit" value="Submit" onclick="generateCompanyStatusReport();"/> 
            <input class='submit-gray' type='reset'  name='btnTodoClear' value='Clear' onclick="clearCompanyStatusForm();"/> 
            <br />
            <div class="module"  style="overflow-y: auto;width: 100%;margin-top: 20px;background: none;" id="companyStatusTable">            
            </div>  
            <br />
            <div style='width: 100%'>
                <center><input class='submit-green' type='submit' id='btnReportSubmit' name='btnReportSubmit'  value='Download' style="display: none"/></center>
            </div>                                                   
        </form>
        <?php
    } else if ($_GET['cntAction'] == 'employee performance') {
        ?>        
        <div id="adminSuccessMsg"></div>         
        <table style="border: 0px">
            <tr width="100%">
                <td width="30%" style="border-right: none">
                    <p>
                        <label>Select an Employee</label>
                        <select class="input-short" style='width: 70%' name="cmbEmpLst">
                            <option value="NA">Select an Employee</option>
                            <?php
                            $sql_empl_lst = "select empl_id, empl_name from tbl_employee;";
                            $res_empl_lst = mysqli_query($con, $sql_empl_lst);
                            print_r($res_empl_lst);
                            while ($row_empl_lst = mysqli_fetch_array($res_empl_lst)) {
                                echo "<option value='$row_empl_lst[0]'>$row_empl_lst[1]</option>";
                            }
                            ?>
                        </select>
                    </p>                                                                                        
                    <fieldset>
                        <legend>Select a Date Range</legend>
                        <label>From:</label>
                        <input type="text" name='startDate' style="width: 65%;" id="inputField" class='input-short' placeholder="Click here to select" />
                        <label>To:</label>
                        <input type="text" name='endDate' style="width: 65%;" id="inputField1" class='input-short' placeholder="Click here to select" />                        
                    </fieldset>                                                   
                    <fieldset>
                        <input class="submit-green" type="button" onclick="generateEmplReport();" name="btnTodoSubmit" value="Show" /> 
                        <input class="submit-gray" type="button" onclick="clearEmplReportForm();" name="btnTodoClear" value="Reset" />
                    </fieldset>                                                        
                </td>                
                <td width="70%" style="border-right: none;">
                    <!--Refresh Button -->                    
                    &nbsp;
                    <div id="paginationTable">                         
                    </div> 
                </td>
            </tr>                    
        </table>
        <?php
    } else if ($_GET['cntAction'] == 'travel report') {
        ?>
        <div id="adminSuccessMsg"></div>         
        <table style="border: 0px">
            <tr width="100%">
                <td width="30%" style="border-right: none">
                    <p>
                        <label>Select an Employee</label>
                        <select class="input-short" style='width: 70%' name="cmbEmpLst">
                            <option value="NA">Select an Employee</option>
                            <?php
                            $sql_empl_lst = "select empl_id, empl_name from tbl_employee;";
                            $res_empl_lst = mysqli_query($con, $sql_empl_lst);
                            print_r($res_empl_lst);
                            while ($row_empl_lst = mysqli_fetch_array($res_empl_lst)) {
                                echo "<option value='$row_empl_lst[0]'>$row_empl_lst[1]</option>";
                            }
                            ?>
                        </select>
                    </p>                                                                                        
                    <fieldset>
                        <legend>Select a Date Range</legend>
                        <label>Start Time:</label>
                        <input type="text" name='startDate' style="width: 30%;" id="inputField" class='input-short' placeholder="Start Date" />
                        <select name="cmbStartTime" id="cmbStartTime">
                            <?php echo $start_time; ?>
                        </select>
                        <label>End Time:</label>
                        <input type="text" name='endDate' style="width: 30%;" id="inputField1" class='input-short' placeholder="End Date" />                       
                        <select name="cmbEndTime" id="cmbEndTime">
                            <?php echo $end_time; ?>
                        </select>
                    </fieldset>                                                   
                    <fieldset>
                        <input class="submit-green" type="button" onclick="generateTravelReport();" name="btnTodoSubmit" value="Show" /> 
                        <input class="submit-gray" type="button" onclick="clearEmplReportForm();" name="btnTodoClear" value="Reset" />
                    </fieldset>                                                         
                </td>                
                <td width="70%" style="border-right: none;">
                    <!--Refresh Button -->                    
                    &nbsp;
                    <div id="paginationTable">                         
                    </div> 
                </td>
            </tr>                    
        </table>
        <?php
    } else if ($_GET['cntAction'] == 'contact report') {
        ?>         
        <form method="post" action="includes/php/downloadExcelReportByContact.php">      
            <?php
            $empl_type = $_SESSION['empType'];
            if ($empl_type == "Admin") {
                ?>
                <table>
                    <tr>
                        <td style="border-top: 1px solid #d9d9d9;border-right:0;">
                            <input type="radio" name="reportType" value="employee" checked="checked" onclick="displayContactReportCombo(this.value);"/> Employee Wise Contact Report
                        </td>
                        <td style="border-top: 1px solid #d9d9d9;">
                            <input type="radio" name="reportType" value="company" onclick="displayContactReportCombo(this.value);" /> Company Wise Contact Report
                        </td>                        
                    </tr>
                    <tr>
                        <td style="border-right: 0;">
                            <input type="radio" name="reportType" value="stream" onclick="displayContactReportCombo(this.value);" /> Stream Wise Contact Report
                        </td>
                        <td>
                            <input type="radio" name="reportType" value="streamCompany" onclick="displayContactReportCombo(this.value);" /> Company + Stream Wise Contact Report
                        </td>
                    </tr>
                    <tr>
                        <td style="border-right: 0;">      
                            <input type="radio" name="reportType" value="event" onclick="displayContactReportCombo(this.value);" /> Event Wise Contact Report                        
                        </td>
                        <td></td>
                    </tr>
                </table>

                <p id="emplCmbPara">
                    <label>Select an Employee Name</label>
                    <select class="input-short" style='width: 40%' name="cmbEmpLst" onchange="displayContactDetailsReport(this.value, 'employee');">                 
                        <option value="NA">Select an employee</option>
                        <?php
                        $sql_empl_lst = "SELECT `empl_id`,`empl_name` FROM `tbl_employee` order by empl_name";
                        $res_empl_lst = mysqli_query($con, $sql_empl_lst);

                        while ($row_empl_lst = mysqli_fetch_array($res_empl_lst)) {
                            echo "<option value='$row_empl_lst[0]'>$row_empl_lst[1]</option>";
                        }
                        ?>
                    </select>
                </p>
                <p id="eventPara" style="display: none;">
                    <label>Select an Event</label>
                    <select class="input-short" style='width: 40%' name="cmbEventLst" onchange="displayContactDetailsReport(this.value, 'event');">  
                        <option value="">Select an Event</option>                                
                        <option value="Finance Conclave">Finance Conclave</option>                                
                        <option value="HR Conclave">HR Conclave</option>
                        <option value="Marketing Conclve">Marketing Conclave</option>
                        <option value="NMC">NMC</option>
                        <option value="Seminar">Seminar</option>                       
                    </select>
                </p>
                <p id="CompCmbPara" style="display: none;">
                    <label>Select a Company Name</label>
                    <select class="input-short" style='width: 40%' name="cmbCompanyLst" onchange="displayContactDetailsReport(this.value, 'company');">                 
                        <option value="NA">Select a company</option>
                        <?php
                        $sql_comp_lst = "SELECT `comp_id`,`comp_name` FROM `tbl_company` order by comp_name";
                        $res_comp_lst = mysqli_query($con, $sql_comp_lst);

                        while ($row_comp_lst = mysqli_fetch_array($res_comp_lst)) {
                            echo "<option value='$row_comp_lst[0]'>$row_comp_lst[1]</option>";
                        }
                        ?>
                    </select>
                </p>
                <p id="streamCmbPara" style="display: none;">
                    <label>Select a Stream Name</label>
                    <select class="input-short" style='width: 40%' name="cmbStreamLst" onchange="displayContactDetailsReport(this.value, 'stream');">                 
                        <option value="NA">Select a stream</option>
                        <?php
                        $sql_stream_lst = "SELECT distinct `stream` FROM `tbl_stream` order by stream";
                        $res_stream_lst = mysqli_query($con, $sql_stream_lst);

                        while ($row_stream_lst = mysqli_fetch_array($res_stream_lst)) {
                            echo "<option value='$row_stream_lst[0]'>$row_stream_lst[0]</option>";
                        }
                        ?>
                    </select>
                </p>
                <p id="companyStreamCmbPara" style="display: none;">
                    <label>Select a Company Name</label>
                    <select class="input-short" style='width: 40%' name="cmbCompanyLst" id="cmbCompanyLst" onchange="displayContactDetailsReport(this.value, 'companyStream');">                 
                        <option value="NA">Select a company</option>
                        <?php
                        $sql_comp_lst = "SELECT `comp_id`,`comp_name` FROM `tbl_company` order by comp_name";
                        $res_comp_lst = mysqli_query($con, $sql_comp_lst);

                        while ($row_comp_lst = mysqli_fetch_array($res_comp_lst)) {
                            echo "<option value='$row_comp_lst[0]'>$row_comp_lst[1]</option>";
                        }
                        ?>
                    </select>
                </p>
            <?php } ?>  
            <div id="report-div">
                <input type="hidden" name="hidReportFor" value="employee" />
                <p style="display: <?php
                if ($empl_type == "Admin") {
                    echo "none";
                } else {
                    echo "block";
                }
                ?>;">
                    <label>Select a Company Name</label>
                    <select class="input-short" style='width: 70%' name="cmbCompLst" onchange="displayContactDetailsByCompany(this.value);">                 
                        <option value="all">All Companies</option>
                        <?php
                        $sql_comp_lst = "SELECT `comp_id`,`comp_name` FROM `tbl_company` WHERE `comp_id` IN (SELECT distinct(comp_id) FROM `tbl_contact` WHERE empl_id=$empId)";
                        $res_comp_lst = mysqli_query($con, $sql_comp_lst);

                        while ($row_comp_lst = mysqli_fetch_array($res_comp_lst)) {
                            echo "<option value='$row_comp_lst[0]'>$row_comp_lst[1]</option>";
                        }
                        ?>
                    </select>
                </p>                                                                                                                  

                <div class="grid_6" style="width: 100%;display: <?php
                if ($empl_type == "Admin") {
                    echo "none";
                } else {
                    echo "block";
                }
                ?>;height: 370px; margin-left: 0px;" id="divContactDetails">
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
                </div>
            </div>                         
        </form>
        <?php
    }
}
?>