<?php
include_once '../db.php';
session_start();
$empId = $_SESSION['empId'];
$tagType = $_GET['type'];
$infoType = $_GET['infoType'];
if (isset($_GET['companyId']) && !empty($_GET['companyId'])) {
    $cid = $_GET['companyId'];
    if (isset($_GET['myContact']) && !empty($_GET['myContact'])) {
        $sql = "SELECT cont_id, cont_name FROM `tbl_contact` WHERE comp_id=$cid and empl_id=$empId order by cont_name ";
    } else {
        $sql = "SELECT cont_id, cont_name FROM `tbl_contact` WHERE comp_id=$cid order by cont_name ";
    }
    $res = mysqli_query($con, $sql);
    if ($tagType == "checkbox" || $tagType == 'radio') {
        if (mysqli_num_rows($res) > 0) {
            if (isset($_GET['contId']) && !empty($_GET['contId'])) {
                while ($row = mysqli_fetch_array($res)) {
                    if ($row[0] == $_GET['contId']) {
                        echo "<input type='$tagType' value='$row[0]' name='contactList' onchange=\"deSelectAllEle('allContact',this.checked)\" checked='checked'  />$row[1]<br />";
                    } else {
                        echo "<input type='$tagType' value='$row[0]' name='contactList' onchange=\"deSelectAllEle('allContact',this.checked)\"  />$row[1]<br />";
                    }
                }
                if ($tagType == "checkbox") {
                    ?>
                    <br /><input type='<?php echo $tagType ?>' value='All' id='allContact' onchange="selectAllItems('allContact', document.getElementsByName('contactList'));"/>All The Contacts
                    <br /><br />
                    <?php
                }
            } else {
                while ($row = mysqli_fetch_array($res)) {
                    echo "<input type='$tagType' value='$row[0]' name='contactList' onchange=\"deSelectAllEle('allContact',this.checked)\" checked='checked'  />$row[1]<br />";
                }
                if ($tagType == "checkbox") {
                    ?>
                    <br /><input type='<?php echo $tagType ?>' value='All' id='allContact' checked='checked' onchange="selectAllItems('allContact', document.getElementsByName('contactList'));"/>All The Contacts
                    <br /><br />
                    <?php
                }
            }

            if ($infoType == "contact") {
                ?>
                <a href="javascript:void(0)" class="button" onclick="fetchContactDetail(document.getElementsByName('contactList'));">
                    <span>Fetch Contact Details<img src="bin.gif" width="12" height="9" alt="Fetch Office List" /></span>
                </a>
            <?php } elseif ($infoType == 'activity') { ?>
                <a href="javascript:void(0)" class="button" onclick="fetchActivityDetail(document.getElementsByName('contactList'), 'cont_id');">
                    <span>Fetch Activity Details<img src="bin.gif" width="12" height="9" alt="Fetch Office List" /></span>
                </a>
                <?php
            }
        } else {
            echo "No Contact Found";
        }
    } else {
        if (mysqli_num_rows($res) > 0) {
            echo "<option value='NA'>Select a Contact</option>";
            while ($row = mysqli_fetch_array($res)) {
                echo "<option value='$row[0]'>$row[1]</option>";
            }
        } else {
            echo "<option value='NA'>No Contact Found</option>";
        }
    }
}
?>
