<?php
include_once '../db.php';
session_start();
$empId = $_SESSION['empId'];
if (isset($_GET['cntAction']) && !empty($_GET['cntAction'])) {
    if ($_GET['cntAction'] == 'activities by contact') {
        ?>
        <div id="contactMessageDisplay"></div>

        <div id="divCompanyList">
            <p id="txtCmpPara">
                <label id="comanyLabel">Company</label>
                <input type="text" name="txtCompSearchBox" class="input-short" placeholder="Search company here" autocomplete="off" onkeyup="autopopulate(this, 'activities by contact')" style="width: 70%;">
                <span id='companySearchAjax'></span>
                <br />
                <span id="companySuggested" style="z-index: 1; position: absolute; width: 26%;"></span>
            </p>

        </div>
        <p id="chekCompLstPara">
            <input type="checkbox" name="chkCmbCompanyLst" id="chkCmbCompanyLst" onchange="displayCompanyComboListByType('activities by contact', this)">
            <span> Display Company List</span>
        </p>
        <p>
            <label>
                <a href="javascript:void(0)" class="button" onclick="if (document.getElementsByName('txtCompSearchBox').length == 2)
                                    fetchContactListByComp(document.getElementsByName('txtCompSearchBox')[1].value, 'radio', 'activity');
                                else
                                    fetchContactListByComp(document.getElementsByName('txtCompSearchBox')[0].value, 'radio', 'activity');">
                    <span>Refresh Contact List<img src="bin.gif" width="12" height="9" alt="Fetch Office List" /></span>
                </a>
            </label><br /><br />
        </p>
        <div  class="grid_6" style="width: 20%">
            <div class="module">
                <h2><span>Contact List</span></h2>

                <div class="module-body" id='contactContainer'>

                </div>

            </div>
        </div>
        <div class="grid_6" style="width: 73%;">
            <div class="module">
                <h2><span>Activity Details<img src="download.jpg" height="20" width="20" title="Download Report" onclick="window.location = 'includes/php/downloadContactActivityExcel.php';"  style="float: right; margin-right: 10px;" /></span></h2>

                <div class="module-table-body" style="height: 400px;overflow-y: auto;">
                    <table id="myTable" class="tablesorter">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th>Name</th>
                                <th>Activity Details</th>
                                <th>Contacted By</th>
                                <th>Activity Type</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id='activityDetailContainer'>
                            <tr>
                                <td colspan="7">Please select respective Company and Contacts to view details</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }

    if ($_GET['cntAction'] == 'activities by member') {
        $sqlMember = "select * from tbl_employee where empl_status='Active'";
        $resMember = mysqli_query($con, $sqlMember);
        ?>
        <div  class="grid_6" style="width: 20%">
            <div class="module">
                <h2><span>Member List</span></h2>

                <div class="module-body" id='contactContainer'>

                    <?php
                    while ($rowMember = mysqli_fetch_array($resMember)) {
                        ?>
                        <input type="radio" name="contactList" value="<?php echo $rowMember['empl_id'] ?>" /><?php echo $rowMember['empl_name'] ?><br />
                        <?php
                    }
                    ?>
                    <br />
                    <a href="javascript:void(0)" class="button" onclick="fetchActivityDetail(document.getElementsByName('contactList'), 'empl_id');">
                        <span>Fetch Activity Details<img src="bin.gif" width="12" height="9" alt="Fetch Office List" /></span>
                    </a>
                </div>

            </div>
        </div>
        <div class="grid_6" style="width: 73%;">
            <div class="module">
                <h2><span>Activity Details<img src="download.jpg" height="20" width="20" title="Download Report" onclick="window.location = 'includes/php/downloadMemberActivityExcel.php';"  style="float: right; margin-right: 10px;" /></span></h2>

                <div class="module-table-body" style="height: 644px;overflow-y: auto;">
                    <table id="myTable" class="tablesorter">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th>Contact Name</th>
                                <th>Activity Details</th>
                                <th>Company Name</th>
                                <th>Activity Type</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id='activityDetailContainer'>
                            <tr>
                                <td colspan="7">Please select respective CR Member to view details</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }
}

if (isset($_POST['task']) && $_POST['task'] == 'activityDetail') {
    $contactId = $_POST['contactId'];
    $fetchBy = $_POST['by'];
    if ($fetchBy == 'cont_id') {
        $sql = "select * from tbl_contact tc, tbl_employee te, tbl_emp_contact tec where tc.cont_id=tec.cont_id and te.empl_id=tec.empl_id and tec.cont_id=$contactId order by act_date desc";
    } else {
        $sql = "select * from tbl_contact tc,tbl_company tco, tbl_emp_contact tec where tc.comp_id=tco.comp_id and tc.cont_id=tec.cont_id and tec.empl_id=$contactId order by act_date desc";
    }
    $res = mysqli_query($con, $sql);
    $_SESSION['queryDownload'] = $sql;
    $_SESSION['companyName'] = $_POST['companyName'];
    //echo $sql;
    $i = 1;
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_array($res)) {
            if ($fetchBy == 'cont_id') {
            $empl_id = $row[14];
            }else{
                $empl_id = $contactId;
            }            
            $activity_id = $row['empl_cont_id'];
            if ($i % 2 == 0) {
                $rowType = 'class="even"';
            } else {
                $rowType = 'class="odd"';
            }
            ?>
            <tr <?php echo $rowType; ?>>
                <td><?php echo $i ?></td>
                <td><?php echo $row['cont_name'] ?></td>
                <td><?php echo $row['act_detail'] ?></td>
                <td><?php
                    if ($fetchBy == 'cont_id') {
                        echo $row['empl_name'];
                    } else {
                        echo $row['comp_name'];
                    }
                    ?></td>
                <td><?php echo $row['act_type'] ?></td>
                <td><?php echo date("d-m-Y",  strtotime($row['act_date'])); ?></td>
                <td>
                    <a id="toDo<?php echo $i; ?>" href="includes/php/ajax/ajax_AddToDoList.php?include=style&contId=<?php echo $row['cont_id'] ?>" onclick="return displayModalBox(this.id);"><img src="pencil.gif" width="16" height="16" alt="Add a Reminder List for this Contact" title="Add a Reminder List for this Contact" /></a>
                    <a  id="activity<?php echo $i; ?>" href="includes/php/ajax/ajax_addActivity.php?contId=<?php echo $row['cont_id'] ?>" onclick="return displayModalBox(this.id);"><img src="balloon.gif" width="16" height="16" alt="Add an Activity wrt this Contact" title="Add an Activity wrt this Contact" /></a>
                    <?php
                    if ($_SESSION['empType'] == "Admin" || $_SESSION['empId'] == $empl_id) {
                        echo "<br>"
                        . "<a id='activity$activity_id' href='includes/php/ajax/ajax_editActivity.php?activityId=$activity_id' title='Edit this Activity' onclick='return displayModalBox(this.id);'>"
                        . "<img src='edit-image.jpg' width='50' alt='Edit Activity' title='Edit Activity' />"
                        . "</a>";
                    }
                    ?>
                </td>                
            </tr>
            <?php
            $i++;
        }
    } else {
        ?>
        <tr>
            <td colspan="7">No activities found as per your selection</td>
        </tr>
        <?php
    }
}
?>