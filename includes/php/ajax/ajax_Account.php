<?php
include_once '../db.php';
session_start();
$empId = $_SESSION['empId'];
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (isset($_GET['cntAction']) && !empty($_GET['cntAction'])) {
    if ($_GET['cntAction'] == 'details by office') {
        ?>
        <div id="accountMessageDisplay"></div>
        <div id="divCompanyList">
            <p id="txtCmpPara">
                <label id="comanyLabel">Company</label>
                <input type="text" name="txtCompSearchBox" class="input-short" placeholder="Search company here" onkeyup="autopopulate(this, 'details by office')" style="width: 70%;" autocomplete="off">
                <span id='companySearchAjax'></span>
                <br />
                <span id="companySuggested" style="z-index: 1; position: absolute; width: 26%;"></span>
            </p>
        </div>
        <p id="chekCompLstPara">
            <input type="checkbox" name="chkCmbCompanyLst" id="chkCmbCompanyLst" onchange="displayCompanyComboListByType('details by office', this)">
            <span> Display Company List</span>
        </p>
        <p>
            <label>
                <a href="javascript:void(0)" class="button" onclick="if (document.getElementsByName('txtCompSearchBox').length == 2)
                                    fetchOfficeList(document.getElementsByName('txtCompSearchBox')[1].value, 'checkbox');
                                else
                                    fetchOfficeList(document.getElementsByName('txtCompSearchBox')[0].value, 'checkbox');">
                    <span>Fetch Office List<img src="bin.gif" width="12" height="9" alt="Fetch Office List" /></span>
                </a>
            </label><br /><br />
        </p>
        <div  class="grid_6" style="width: 20%">
            <div class="module">
                <h2><span>Office List</span></h2>

                <div class="module-body" id='officeListContainer'>
                </div>

            </div>
        </div>
        <div class="grid_6" style="width: 73%;">
            <div class="module">
                <h2><span>Office and their Respective Contact Details<img src="download.jpg" height="20" width="20" title="Download Report"  onclick="window.location = 'includes/php/downloadOfficeExcel.php';" style="float: right; margin-right: 10px;" /></span></h2>

                <div class="module-table-body" style="overflow-y: auto; height: 400px;">
                    <table id="myTable" class="tablesorter">
                        <thead>
                            <tr>
                                <th style="width: 5%;" rowspan="2">#</th>
                                <th rowspan="2">Office Type</th>
                                <th rowspan="2">Address</th>
                                <th rowspan="2">City</th>
                                <th colspan="4">Contacts Details</th>
                            </tr>
                            <tr>
                                <th width="15%">Name</th>
                                <th>Position</th>
                                <th>Contact Details</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id='officeDetailContainer'>
                            <tr>
                                <td colspan="8">Please select a Company and respective Office to view details</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }

    if ($_GET['cntAction'] == 'activities by company') {
        ?>
        <div id="accountMessageDisplay"></div>

        <div id="divCompanyList">
            <p id="txtCmpPara">
                <label id="comanyLabel">Company</label>
                <input type="text" name="txtCompSearchBox" class="input-short" placeholder="Search company here" onkeyup="autopopulate(this, 'activities by company')" style="width: 70%;" autocomplete="off">
                <span id='companySearchAjax'></span>
                <br />
                <span id="companySuggested" style="z-index: 1; position: absolute; width: 26%;"></span>
            </p>

        </div>
        <p id="chekCompLstPara">
            <input type="checkbox" name="chkCmbCompanyLst" id="chkCmbCompanyLst" onchange="displayCompanyComboListByType('activities by company', this)">
            <span> Display Company List</span>
        </p>
        <p>
            Select a Date Range <br />
            From :
            <input type="text" name='startDate' size="12" id="inputField" class='input-short' placeholder="Click here to select" />
            To 
            <input type="text" name='endDate' size="12" id="inputField1" class='input-short' placeholder="Click here to select" />
        </p>
        <p>
            <label>
                <a href="javascript:void(0)" class="button" onclick="if (document.getElementsByName('txtCompSearchBox').length == 2)
                                    fetchActivityDetailsByCompany(document.getElementsByName('txtCompSearchBox')[1].value);
                                else
                                    fetchActivityDetailsByCompany(document.getElementsByName('txtCompSearchBox')[0].value);">
                    <span>Fetch Activity Details<img src="bin.gif" width="12" height="9" alt="Fetch Office List" /></span>
                </a>
            </label><br /><br />
        </p>

        <div class="grid_6" style="width: 96%;">
            <div class="module">
                <h2><span>Activity Details<img src="download.jpg" height="20" width="20" title="Download Report" onclick="window.location = 'includes/php/downloadCompanyActivityExcel.php';" style="float: right; margin-right: 10px;" /></span></h2>

                <div class="module-table-body" style="overflow-y: auto; height: 400px;">
                    <table id="myTable" class="tablesorter">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th>Name</th>
                                <th>Primary Contacts</th>
                                <th>Job Position</th>
                                <th>Office Details</th>
                                <th>Contacted By</th>
                                <th>Activity type</th>
                                <th>Activity Date</th>
                                <th>Activity Detail</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id='activityDetailContainer'>
                            <tr>
                                <td colspan="10">
                                    Please select a company to view the Activity Details
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }

    if ($_GET['cntAction'] == 'scheduled items') {
        ?>
        <div id="accountMessageDisplay"></div>

        <div id="divCompanyList">
            <p id="txtCmpPara">
                <label id="comanyLabel">Company</label>
                <input type="text" name="txtCompSearchBox" class="input-short" placeholder="Search company here" onkeyup="autopopulate(this, 'scheduled items')" style="width: 70%;" autocomplete="off">
                <span id='companySearchAjax'></span>
                <br />
                <span id="companySuggested" style="z-index: 1; position: absolute; width: 26%;"></span>
            </p>

        </div>
        <p id="chekCompLstPara">
            <input type="checkbox" name="chkCmbCompanyLst" id="chkCmbCompanyLst" onchange="displayCompanyComboListByType('scheduled items', this)">
            <span> Display Company List</span>
        </p>
        <p>
            Select a Date Range <br />
            From :
            <input type="text" name='startDate' size="12" id="inputField" class='input-short' placeholder="Click here to select" />
            To 
            <input type="text" name='endDate' size="12" id="inputField1" class='input-short' placeholder="Click here to select" />
        </p>
        <p>
            <label>
                <a href="javascript:void(0)" class="button" onclick="if (document.getElementsByName('txtCompSearchBox').length == 2)
                                    fetchToDoDetailsByCompany(document.getElementsByName('txtCompSearchBox')[1].value);
                                else
                                    fetchToDoDetailsByCompany(document.getElementsByName('txtCompSearchBox')[0].value);">
                    <span>Fetch Scheduled Task Details<img src="bin.gif" width="12" height="9" alt="Fetch Office List" /></span>
                </a>
            </label><br /><br />
        </p>
        <div class="grid_6" style="width: 96%;">
            <div class="module">
                <h2><span>Schedule Details<img src="download.jpg" height="20" width="20" title="Download Report" onclick="window.location = 'includes/php/downloadScheduleExcel.php';"  style="float: right; margin-right: 10px;" /></span></h2>

                <div class="module-table-body" style="overflow-y: auto; height: 400px;">
                    <table id="myTable" class="tablesorter">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th>Name</th>
                                <th>Primary Contacts</th>
                                <th>Job Position</th>
                                <th>Office Details</th>
                                <th>Scheduled By</th>
                                <th>Schedule Date</th>
                                <th>Reminder Detail</th>
                                <th>Closure Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id='toDoDetailContainer'>
                            <tr>
                                <td colspan="10">
                                    Please select a company to view the Scheduling Details
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }
}

if (isset($_POST['task']) && $_POST['task'] == "officeDetail") {
    $officeList = implode(",", $_POST['officeList']);
    $sqlOfficeDetail = "SELECT * , (
                            SELECT COUNT( * ) 
                            FROM tbl_contact tci
                            WHERE tci.offi_id = tof.offi_id
                        ) AS total
                        FROM tbl_contact tc, tbl_office tof
                        WHERE tc.offi_id = tof.offi_id
                        AND tof.offi_id
                        IN ( $officeList ) 
                        ORDER BY tof.offi_id
                        ";
    //echo $sqlOfficeDetail;
    $_SESSION['queryDownload'] = $sqlOfficeDetail;
    $_SESSION['companyName'] = $_POST['companyName'];
    $resOfficeDetail = mysqli_query($con, $sqlOfficeDetail);
    $i = 1;
    $j = 1;
    $officeId = 0;
    if (mysqli_num_rows($resOfficeDetail) > 0) {
        while ($rowOfficeDetail = mysqli_fetch_array($resOfficeDetail)) {
            if ($j % 2 == 0)
                $rowType = 'class="even"';
            else
                $rowType = 'class="odd"';
            ?>
            <tr <?php echo $rowType; ?>>
                <?php
                if ($rowOfficeDetail['offi_id'] != $officeId) {
                    ?>
                    <td rowspan="<?php echo $rowOfficeDetail['total'] ?>"><?php echo $i ?></td>
                    <td rowspan="<?php echo $rowOfficeDetail['total'] ?>"><?php echo $rowOfficeDetail['offi_type'] ?></td>
                    <td rowspan="<?php echo $rowOfficeDetail['total'] ?>"><?php echo $rowOfficeDetail['offi_address'] . " <br />" . $rowOfficeDetail['offi_city'] . " <br />" . $rowOfficeDetail['offi_country'] ?><br />
                        <!-- <a id="editOffice<?php echo $j; ?>" href="includes/php/ajax/ajax_editOffice.php?officeId=<?php echo $rowOfficeDetail['offi_id'] ?>" onclick="return displayUpdateContactModalBox(this.id);"><img src="edit-image.jpg" width="50"  alt="Eidt Office Details" title="Eidt Office Details" /></a> -->
                    </td>
                    <td rowspan="<?php echo $rowOfficeDetail['total'] ?>"><?php echo $rowOfficeDetail['offi_city'] ?></td>

                    <?php
                    $officeId = $rowOfficeDetail['offi_id'];
                    $i++;
                }
                ?>
                <td><?php echo $rowOfficeDetail['cont_name'] ?></td>
                <td><?php echo "Designation: " . $rowOfficeDetail['cont_desg'] . "<br />Department: " . $rowOfficeDetail['cont_dept'] ?></td>
                <td><?php
                    if ($_SESSION['contViewSetting'] == "No" && $empId != $rowOfficeDetail['empl_id'])
                        echo "Not Authorized";
                    else
                        echo "Email: " . $rowOfficeDetail['cont_email'] . "<hr />Mobile:" . $rowOfficeDetail['cont_mobile'] . "<hr />Direct: " . $rowOfficeDetail['cont_direct'] . "<hr />Extension: " . $rowOfficeDetail['cont_ext']
                        ?></td>
                <td><a id="toDo<?php echo $j; ?>" href="includes/php/ajax/ajax_AddToDoList.php?include=style&contId=<?php echo $rowOfficeDetail['cont_id'] ?>" onclick="return displayModalBox(this.id);"><img src="pencil.gif" width="16" height="16" alt="Add a Reminder List for this Contact" title="Add a Reminder List for this Contact" /></a>
                    <a  id="activity<?php echo $j; ?>" href="includes/php/ajax/ajax_addActivity.php?contId=<?php echo $rowOfficeDetail['cont_id'] ?>" onclick="return displayModalBox(this.id);"><img src="balloon.gif" width="16" height="16" alt="Add an Activity wrt this Contact" title="Add an Activity wrt this Contact" /></a><?php
                    /* if ($_SESSION['contViewSetting'] == "No" && $empId != $rowOfficeDetail['empl_id']) {
                      ?>
                      <a id="editContact<?php echo $j; ?>" href="javascript:void(0)" onclick="alert('Sorry. You are not authorized to edit this Contact Details');"><img src="edit-image.jpg" width="50"  alt="Edit Contact Details" title="Edit Contact Details" /></a>
                      <?php
                      } else {
                      ?>
                      <a id="editContact<?php echo $j; ?>" href="includes/php/ajax/ajax_editContact.php?contId=<?php echo $rowOfficeDetail['cont_id'] ?>" onclick="return displayUpdateContactModalBox(this.id);"><img src="edit-image.jpg" width="50"  alt="Edit Contact Details" title="Edit Contact Details" /></a>
                      <?php
                      } */
                    ?>
                </td>
            </tr>
            <?php
            $j++;
        }
    } else {
        ?>
        <tr>
            <td colspan="9">No Contacts exist in this Office</td>
        </tr>
        <?php
    }
}

if (isset($_POST['task']) && $_POST['task'] == "activityDetail") {
    $companyId = $_POST['compId'];
    echo "<input type='hidden' id='hidCompId' value='$companyId' />";
    if (!empty($_POST['startDate'])) {
        $startDate = date("Y-m-d", strtotime($_POST['startDate']));
    } else {
        $startDate = "1970-01-01";
    }
    if (!empty($_POST['endDate'])) {
        $endDate = date("Y-m-d", strtotime($_POST['endDate']));
    } else {
        $endDate = date("Y-m-d");
    }
    $sqlActivity = "SELECT * FROM `tbl_emp_contact` tec, tbl_contact tc, tbl_employee te, tbl_office tof
                    WHERE 
                    tec.cont_id=tc.cont_id and
                    tec.empl_id=te.empl_id and
                    tof.offi_id=tc.offi_id and
                    tc.comp_id=$companyId and
                    tec.act_date between '$startDate' and '$endDate'
                    order by act_date desc";
    //echo $sqlActivity;
    $_SESSION['queryDownload'] = $sqlActivity;
    $_SESSION['companyName'] = $_POST['companyName'];
    $resActivity = mysqli_query($con, $sqlActivity);
    $i = 1;
    if (mysqli_num_rows($resActivity) > 0) {
        while ($rowActivity = mysqli_fetch_array($resActivity)) {
            $empl_id = $rowActivity[5];
            $activity_id = $rowActivity[0];
            if ($i % 2 == 0) {
                $rowType = 'class="even"';
            } else {
                $rowType = 'class="odd"';
            }
            ?>
            <tr <?php echo $rowType; ?>>
                <td><?php echo $i; ?></td>
                <td><?php echo $rowActivity['cont_name'] ?></td>
                <td><?php
                    if ($_SESSION['contViewSetting'] == "No" && $empId != $rowActivity['empl_id']) {
                        echo "Not Authorized";
                    } else {
                        echo "Email: " . $rowActivity['cont_email'] . "<hr />Mobile:" . $rowActivity['cont_mobile'] . "<hr />Direct: " . $rowActivity['cont_direct'] . "<hr />Extension: " . $rowActivity['cont_ext'];
                    }
                    ?></td>
                <td><?php echo "Designation: " . $rowActivity['cont_desg'] . "<br />Department: " . $rowActivity['cont_dept'] ?></td>
                <td><?php echo $rowActivity['offi_address'] . "<br />" . $rowActivity['offi_city'] . ", " . $rowActivity['offi_country'] ?></td>
                <td><?php echo $rowActivity['empl_name'] ?></td>
                <td><?php echo $rowActivity['act_type'] ?></td>
                <td><?php echo date("d M Y", strtotime($rowActivity['act_date'])); ?></td>
                <td><?php echo $rowActivity['act_detail'] ?></td>
                <td>
                    <a id="toDo<?php echo $i; ?>" href="includes/php/ajax/ajax_AddToDoList.php?include=style&contId=<?php echo $rowActivity['cont_id'] ?>" onclick="return displayModalBox(this.id);"><img src="pencil.gif" width="16" height="16" alt="Add a Reminder List for this Contact" title="Add a Reminder List for this Contact" /></a>
                    <a  id="activity<?php echo $i; ?>" href="includes/php/ajax/ajax_addActivity.php?contId=<?php echo $rowActivity['cont_id'] ?>" onclick="return displayModalBox(this.id);"><img src="balloon.gif" width="16" height="16" alt="Add an Activity wrt this Contact" title="Add an Activity wrt this Contact" /></a>
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
            <td colspan="10">
                No Activity details were found with respect to the company selected
            </td>
        </tr>
        <?php
    }
}

if (isset($_POST['task']) && $_POST['task'] == "toDoDetail") {
    $companyId = $_POST['compId'];
    echo "<input type='hidden' id='hidCompId' value='$companyId' />";
    $sql = "select * from tbl_company where comp_id=$companyId";
    $res = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($res);
    $comp_name = $row['comp_name'];
    if (!empty($_POST['startDate'])) {
        $startDate = date("Y-m-d", strtotime($_POST['startDate']));
    } else {
        $startDate = "1970-01-01";
    }
    if (!empty($_POST['endDate'])) {
        $endDate = date("Y-m-d", strtotime($_POST['endDate']));
    } else {
        $endDate = "2099-12-31";
    }
    $sqlToDo = "SELECT * FROM tbl_todo td, tbl_contact tc, tbl_employee te, tbl_office tof
                    WHERE 
                    td.cont_id=tc.cont_id and
                    td.empl_id=te.empl_id and
                    tof.offi_id=tc.offi_id and
                    tc.comp_id=$companyId  and
                    td.todo_endDate between '$startDate' and '$endDate'
                    order by todo_endDate desc";
    //echo $sqlToDo;
    $_SESSION['queryDownload'] = $sqlToDo;
    $_SESSION['companyName'] = $_POST['companyName'];
    $resToDo = mysqli_query($con, $sqlToDo);
    $i = 1;
    if (mysqli_num_rows($resToDo)) {
        while ($rowToDo = mysqli_fetch_array($resToDo)) {
            $todo_id = $rowToDo['todo_id'];
            $empl_id = $rowToDo[7];
            $cont_name = $rowToDo['cont_name'];
            if ($i % 2 == 0) {
                $rowType = 'class="even"';
            } else {
                $rowType = 'class="odd"';
            }
            ?>
            <tr <?php echo $rowType; ?>>
                <td><?php echo $i; ?></td>
                <td><?php echo $rowToDo['cont_name'] ?></td>
                <td><?php
                    if ($_SESSION['contViewSetting'] == "No" && $empId != $rowToDo[22]) {
                        echo "Not Authorized";
                    } else {
                        echo "Email: " . $rowToDo['cont_email'] . "<br />Mobile:" . $rowToDo['cont_mobile'];
                    }
                    ?></td>
                <td><?php echo "Designation: " . $rowToDo['cont_desg'] . "<br />Department: " . $rowToDo['cont_dept'] ?></td>
                <td><?php echo $rowToDo['offi_address'] . "<br />" . $rowToDo['offi_city'] . ", " . $rowToDo['offi_country'] ?></td>
                <td><?php echo $rowToDo['empl_name'] ?></td>
                <td><?php echo date("d M Y", strtotime($rowToDo['todo_date'])); ?></td>
                <td><?php echo $rowToDo['todo_detail'] ?></td>
                <td><?php echo date("d M Y", strtotime($rowToDo['todo_endDate'])); ?></td>
                <td>
                    <a id="toDo<?php echo $i; ?>" href="includes/php/ajax/ajax_AddToDoList.php?include=style&contId=<?php echo $rowToDo['cont_id'] ?>" onclick="return displayModalBox(this.id);"><img src="pencil.gif" width="16" height="16" alt="Add a Reminder List for this Contact" title="Add a Reminder List for this Contact" /></a>
                    <a id="activity<?php echo $i; ?>" href="includes/php/ajax/ajax_addActivity.php?contId=<?php echo $rowToDo['cont_id'] ?>" onclick="return displayModalBox(this.id);"><img src="balloon.gif" width="16" height="16" alt="Add an Activity wrt this Contact" title="Add an Activity wrt this Contact" /></a>
                        <?php
                        if ($_SESSION['empType'] == "Admin" || $_SESSION['empId'] == $empl_id) {
                            echo "<br>"
                            . "<a id='todo$todo_id' href='includes/php/ajax/ajax_editTodo.php?todoId=$todo_id&contName=$cont_name&compName=$comp_name' title='Edit this Reminder' onclick='return displayModalBox(this.id);'>"
                            . "<img src='edit-image.jpg' width='50' alt='Edit Reminder' title='Edit Reminder' />"
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
            <td colspan="10">
                No Schedules / Reminders were found wrt the company selected
            </td>
        </tr>
        <?php
    }
}
?>
