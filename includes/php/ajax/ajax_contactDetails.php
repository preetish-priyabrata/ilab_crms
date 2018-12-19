<?php
include_once '../db.php';
session_start();
$empId = $_SESSION['empId'];
if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $key = $_GET['keyword'];
    $keyword = str_replace("zxtwuqmtz", "&", $key);
    $sqlContactDetail = "SELECT * , (SELECT GROUP_CONCAT( DISTINCT (SELECT te.empl_name FROM tbl_employee te WHERE te.empl_id = tec.empl_id) ) FROM tbl_emp_contact tec WHERE tec.cont_id = tc.cont_id) AS touch, comp_name FROM tbl_contact tc, tbl_office tof, tbl_company tcom WHERE tc.offi_id = tof.offi_id and tc.comp_id = tcom.comp_id AND tc.cont_id IN (select distinct(cont_id) from tbl_contact where cont_name like '%$keyword%' union select distinct(cont_id) from tbl_contact where `cont_email` like '%$keyword%' union select distinct(cont_id) from tbl_contact where `cont_mobile` like '%$keyword%' union select distinct(cont_id) from tbl_contact where `cont_direct` like '%$keyword%' union select distinct(cont_id) from tbl_contact where `cont_ext` like '%$keyword%' union select distinct(cont_id) from tbl_contact where `cont_desg` like '%$keyword%' union select distinct(cont_id) from tbl_contact l left join tbl_company r on l.comp_id=r.comp_id where r.comp_name like '%$keyword%' union select distinct(cont_id) from tbl_contact l left join tbl_company r on l.comp_id=r.comp_id where r.comp_name in (SELECT comp_name FROM `tbl_company` WHERE comp_type='$keyword') union select distinct(cont_id) from tbl_contact l left join tbl_company r on l.comp_id=r.comp_id where r.comp_website like '%$keyword%' union select distinct(cont_id) from tbl_contact l left join tbl_office r on l.offi_id=r.offi_id where r.offi_boardline like '%$keyword%' union select distinct(cont_id) from tbl_contact l left join tbl_office r on l.offi_id=r.offi_id where r.offi_address like '%$keyword%' union select distinct(cont_id) from tbl_contact l left join tbl_office r on l.offi_id=r.offi_id where r.offi_city like '%$keyword%')";
    $resContactDetail = mysqli_query($con, $sqlContactDetail);
    $i = 1;
    ?>        
    <tr id="contact-search" style="font-weight: bold;">
        <td class="t-head">#</td>
        <td class="t-head">Name</td>
        <td class="t-head">Company</td>
        <td class="t-head">Primary Contacts</td>
        <td class="t-head">Other Contacts</td>
        <td class="t-head">Job Position</td>
        <td class="t-head">Contact(s) in Touch</td>
        <td class="t-head">Office Details</td>
        <td class="t-head">Actions</td>
    </tr>        
    <?php
    while ($rowContactDetail = mysqli_fetch_array($resContactDetail)) {
        if ($i % 2 == 0) {
            $rowType = 'class="even"';
        } else {
            $rowType = 'class="odd"';
        }
        ?>        
        <tr <?php echo $rowType; ?>>                
            <td style="width: 22px"><?php echo $i ?></td>
            <td style="width: 113px"><?php echo $rowContactDetail['cont_name'] ?></td>
            <td style="width: 141px"><?php echo $rowContactDetail['comp_name'] ?></td>
            <td style="width: 150px"><?php
                if ($_SESSION['contViewSetting'] == "No" && $empId != $rowContactDetail['empl_id'])
                    echo "Not Authorized";
                else
                    echo "Email: " . $rowContactDetail['cont_email'] . "<hr />Mobile:" . $rowContactDetail['cont_mobile'] . "<hr />Direct: " . $rowContactDetail['cont_direct'] . "<hr />Extension: " . $rowContactDetail['cont_ext']
                    ?></td>
            <td style="width: 138px"><?php echo "Board Line : " . $rowContactDetail['offi_boardline']; ?></td>
            <td style="width: 130px"><?php echo "Designation: " . $rowContactDetail['cont_desg'] . "<br />Department: " . $rowContactDetail['cont_dept'] ?></td>
            <td style="width: 116px"><?php echo str_replace(",", " ,<br />", $rowContactDetail['touch']) ?></td>
            <td style="width: 124px"><?php
                echo $rowContactDetail['offi_address'] . "<br />" . $rowContactDetail['offi_city'] . ", " . $rowContactDetail['offi_country'];
                if ($rowContactDetail['offi_pin'] != "") {
                    echo "<br>PIN: " . $rowContactDetail['offi_pin'];
                }
                ?></td>
            <td style="width: 78px">
                <?php if ($_SESSION['empType'] == "Admin") {
                    ?>                
                                                                                            <!-- <a id="toDo<?php echo $i; ?>" href="includes/php/ajax/ajax_viewContactSchedule.php?contactId=<?php echo $rowContactDetail['cont_id']; ?>&include=style&contactName=<?php echo $rowContactDetail['cont_name']; ?>&compName=<?php echo $companyName; ?>" onclick="return displayModalBox(this.id);"><img src="pencil.gif" width="16" height="16" alt="View All Reminders for this Contact" title="View All Reminders for this Contact" /></a>
                                                                                            <a  id="activity<?php echo $i; ?>" href="includes/php/ajax/ajax_viewContactActivity.php?contactId=<?php echo $rowContactDetail['cont_id']; ?>&include=style&contactName=<?php echo $rowContactDetail['cont_name']; ?>&compName=<?php echo $companyName; ?>" onclick="return displayModalBox(this.id);"><img src="balloon.gif" width="16" height="16" alt="View All Activities wrt this Contact" title="View All Activities wrt this Contact" /></a> 
                    -->
                    <?php
                } else {
                    ?>                
                    <a id="toDo<?php echo $i; ?>" href="includes/php/ajax/ajax_AddToDoList.php?include=style&contId=<?php echo $rowContactDetail['cont_id'] ?>" onclick="return displayModalBox(this.id);"><img src="pencil.gif" width="16" height="16" alt="Add a Reminder for this Contact" title="Add a Reminder for this Contact" /></a>
                    <a  id="activity<?php echo $i; ?>" href="includes/php/ajax/ajax_addActivity.php?contId=<?php echo $rowContactDetail['cont_id'] ?>" onclick="return displayModalBox(this.id);"><img src="balloon.gif" width="16" height="16" alt="Add an Activity wrt this Contact" title="Add an Activity wrt this Contact" /></a>
                    <?php
                }
                if ($_SESSION['empType'] == "Admin" || $_SESSION['contViewSetting'] == "Yes" || $empId == $rowContactDetail['empl_id']) {
                    ?>
                    <br /><a id="editContact<?php echo $i; ?>" href="includes/php/ajax/ajax_editContact.php?contId=<?php echo $rowContactDetail['cont_id'] ?>" onclick="return displayUpdateContactModalBox(this.id);"><img src="edit-image.jpg" width="50"  alt="Edit Contact Details" title="Edit Contact Details" /></a>
                    <br /><a id="toDoView<?php echo $i; ?>" href="includes/php/ajax/ajax_viewContactSchedule.php?contactId=<?php echo $rowContactDetail['cont_id']; ?>&include=style&contactName=<?php echo $rowContactDetail['cont_name']; ?>&compName=<?php echo $companyName; ?>" onclick="return displayModalBox(this.id);"><img src="reminder.png" width="50" alt="View All Reminders for this Contact" title="View All Reminders for this Contact" /></a>
                    <br /><a  id="activityView<?php echo $i; ?>" href="includes/php/ajax/ajax_viewContactActivity.php?contactId=<?php echo $rowContactDetail['cont_id']; ?>&include=style&contactName=<?php echo $rowContactDetail['cont_name']; ?>&compName=<?php echo $companyName; ?>" onclick="return displayModalBox(this.id);"><img src="activities.png" width="50" alt="View All Activities wrt this Contact" title="View All Activities wrt this Contact" /></a> 
                        <?php
                    }
                    ?>
            </td>
        </tr>        
        <?php
        $i++;
    }
}

