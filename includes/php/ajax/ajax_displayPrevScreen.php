<?php
include_once '../db.php';

if (isset($_GET['divId']) && !empty($_GET['divId'])) {
    if ($_GET['divId'] == 'merqueeNoticeBoard') {
        ?>
        <table style="width: 100%">
            <tr style="width: 100%">
                <td style="width: 95%;">
            <marquee align="absmiddle" scrollamount="2" truespeed="truespeed" scrolldelay="45" onmouseover="this.stop();" onmouseout="this.start();" style="margin-top:5px; ">
                <?php
                $sql = "SELECT `evet_id` , `evet_heading` , `evet_type`, `evet_date` FROM `tbl_event` WHERE `evet_status`='Active' ORDER BY `evet_date` ";
                $res = mysqli_query($con, $sql);
                if (mysqli_num_rows($res) > 0) {
                    //echo "<ul>";
                    while ($row = mysqli_fetch_array($res)) {
                        //to display the new.gif image for 24hour within the publication of notice or event
                        /* $published_date = $row['evet_date'];
                          $now = time();
                          $time_diff = $now - $published_date;
                          if (($time_diff / (60 * 60 * 24)) < 1)
                          $new = "<img src='new.gif' width='30' height='15' />";
                          else
                          $new = ""; */
                        if ($row['evet_type'] == 'Event') {
                            ?>                                        
                            <img src="notification-exclamation.gif" width="16" height="16" alt="Event Bullate" style=" margin-right: 10px;margin-left: 10px; " /><a id="event<?php echo $row[0]; ?>" href="includes/php/ajax/ajax_displayEvent.php?eventId=<?php echo $row[0]; ?>" onclick = "return displayModalBox(this.id);" ><span style="color: green"><?php echo $row[1]; ?></span><?php echo $new; ?></a>
                            <?php
                        } else {
                            ?>
                            <img src="notification-information.gif" width="16" height="16" alt="Event Bullate" style="margin-right: 10px;margin-left: 10px; " /><a id="event<?php echo $row[0]; ?>" href="includes/php/ajax/ajax_displayEvent.php?eventId=<?php echo $row[0]; ?>" onclick = "return displayModalBox(this.id);" ><span style="color: blue"><?php echo $row[1]; ?></span><?php echo $new; ?></a>
                            <?php
                        }
                    }
                } else {
                    echo "No Event/Notice has been added yet.";
                }
                ?>
            </marquee>
        </td>
        <td style="width: 5%; vertical-align: top;text-align: center;background: rgb(246, 246, 246);background: rgba(246, 246, 246, 1);">
            <span style="font-size:xx-small; text-align: center;">Refresh After: <span id="time">5:00</span></span> 
        </td>
        </tr>
        </table>   <?php
    } else if ($_GET['divId'] == 'normalNoticeBoard') {
        echo "<h2><span>Notice</span></h2>
                <div class='module-body' style='height: 350px; overflow-y: auto;' id='normalNoticeBoardBody'>";

        $sql = "SELECT `evet_id`,`evet_heading` FROM `tbl_event` WHERE `evet_type` = 'Notice' and `evet_status`='Active' order by `evet_date` desc ";
        $res = mysqli_query($con, $sql);
        if (mysqli_num_rows($res) > 0) {
            echo "<ul>";
            while ($row = mysqli_fetch_array($res)) {
                ?>
                <li>
                    <img src="notification-information.gif" width="16" height="16" alt="Event Bullate" style="float: left; margin-right: 10px; margin-bottom: 10px" /> <a href="javascript:void(0)" onclick = "displayNoticeEvent(<?php echo $row[0]; ?>, 'normalNoticeBoard', 'normalNoticeBoardBody')" ><h5 style="color: blue"><?php echo $row[1]; ?></h5></a>
                </li>
                <?php
            }
            echo "</ul>";
        } else {
            echo "<h5 style='color: red'>No Notice has been added yet.</h5>";
        }
        echo "</div>";
    } else if ($_GET['divId'] == 'normalEventBoard') {
        echo "<h2><span>Event</span></h2>
             <div class='module-body' style='height: 350px; overflow-y: auto;' id='normalEventBoardBody'>";
        $sql = "SELECT `evet_id`,`evet_heading` FROM `tbl_event` WHERE `evet_type` = 'Event' and `evet_status`='Active' order by `evet_date` desc ";
        $res = mysqli_query($con, $sql);
        if (mysqli_num_rows($res) > 0) {
            echo "<ul>";
            while ($row = mysqli_fetch_array($res)) {
                ?>
                <li>
                    <img src="notification-exclamation.gif" width="16" height="16" alt="Event Bullate" style="float: left; margin-right: 10px; margin-bottom: 10px" /> <a href="javascript:void(0)" onclick = "displayNoticeEvent(<?php echo $row[0]; ?>, 'normalEventBoard', 'normalEventBoardBody')" ><h5 style="color: green"><?php echo $row[1]; ?></h5></a>
                </li>
                <?php
            }
            echo '</ul>';
        } else {
            echo "<h5 style='color: red'>No Event has been added yet.</h5>";
        }
        echo "</div>";
    }
}
?>