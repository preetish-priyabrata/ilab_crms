<?php
session_start();
include_once '../db.php';
include_once '../admin_pagination.php';
include_once '../state_country_list.php';
if (isset($_GET['cntAction']) && !empty($_GET['cntAction'])) {
    $eventContent = $_GET['cntAction'];
    if ($eventContent == "view") {
        ?>
        <table style="border: 0px">
            <tbody>
                <tr width="100%">
                    <td width="50%" style="border-right: none">   
                        &nbsp;
                        <div class="grid_5" style="width: 98%">
                            <div class="module" id="normalNoticeBoard">
                                <h2><span>Notice</span></h2>

                                <div class="module-body" style="height: 350px; overflow-y: auto;" id="normalNoticeBoardBody">
                                    <?php
                                    $sql = "SELECT `evet_id`,`evet_heading` FROM `tbl_event` WHERE `evet_type` = 'Notice' and `evet_status`='Active' order by `evet_date` desc ";
                                    $res = mysqli_query($con, $sql);
                                    if (mysqli_num_rows($res) > 0) {
                                        echo "<ul>";
                                        while ($row = mysqli_fetch_array($res)) {
                                            ?>
                                            <li>
                                                <img src="notification-information.gif" width="16" height="16" alt="Event Bullate" style="float: left; margin-right: 10px; margin-bottom: 10px" /> <a href="javascript:void(0)" onclick = "displayNoticeEvent(<?php echo $row[0]; ?>, 'normalNoticeBoard', 'normalNoticeBoardBody');" ><h5 style="color: blue"><?php echo $row[1]; ?></h5></a>
                                            </li>
                                            <?php
                                        }
                                        echo "</ul>";
                                    } else {
                                        echo "<h5 style='color: red'>No Notice has been added yet.</h5>";
                                    }
                                    ?>
                                </div>
                            </div>                
                        </div> <!-- End .grid_5 -->
                    </td>                
                    <td width="50%" style="border-right: none">
                        &nbsp;
                        <div class="grid_5" style="width: 98%">
                            <div class="module" id="normalEventBoard">
                                <h2><span>Event</span></h2>

                                <div class="module-body" style="height: 350px; overflow-y: auto;" id="normalEventBoardBody">
                                    <?php
                                    $sql = "SELECT `evet_id`,`evet_heading` FROM `tbl_event` WHERE `evet_type` = 'Event' and `evet_status`='Active' order by `evet_date` desc ";
                                    $res = mysqli_query($con, $sql);
                                    if (mysqli_num_rows($res) > 0) {
                                        echo "<ul>";
                                        while ($row = mysqli_fetch_array($res)) {
                                            ?>
                                            <li>
                                                <img src="notification-exclamation.gif" width="16" height="16" alt="Event Bullate" style="float: left; margin-right: 10px; margin-bottom: 10px" /> <a href="javascript:void(0)" onclick = "displayNoticeEvent(<?php echo $row[0]; ?>, 'normalEventBoard', 'normalEventBoardBody');" ><h5 style="color: green"><?php echo $row[1]; ?></h5></a>
                                            </li>
                                            <?php
                                        }
                                        echo "</ul>";
                                    } else {
                                        echo "<h5 style='color: red'>No Event has been added yet.</h5>";
                                    }
                                    ?>
                                </div>
                            </div>                
                        </div> <!-- End .grid_5 -->
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
    } else if ($eventContent == "delete") {
        if ($_SESSION['empType'] == "Admin") {
            ?>          
            <table style="border: 0px">
                <tbody>
                    <tr width="100%">                        
                        <td width="50%" style="border-right: none">   
                            &nbsp;
                            <div class="grid_5" style="width: 98%">
                                <div id="adminSuccessMsg1"></div>
                                <div class="module">
                                    <h2><span>Notice</span></h2>

                                    <div class="module-table-body" style="height: 350px; overflow-y: auto;">
                                        <form action='' name='frmNotice'>
                                            <table id="myTable" class="tablesorter">
                                                <thead>
                                                    <tr>
                                                        <th style="width:5%">#</th>
                                                        <th style="width:20%">Date</th>
                                                        <th style="width:45%">Heading</th>
                                                        <th style="width:10%">Status</th>
                                                        <th style="width:15%">Remove</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="noticeTableBody">
                                                    <?php
                                                    $sql = "SELECT `evet_id`,`evet_heading`,`evet_date`,`evet_status` FROM `tbl_event` WHERE `evet_type` = 'Notice' order by `evet_date` desc ";
                                                    $res = mysqli_query($con, $sql);
                                                    if (mysqli_num_rows($res) > 0) {
                                                        $row_no = 1;
                                                        while ($row = mysqli_fetch_array($res)) {
                                                            $date = date('d-m-Y', $row['evet_date']);
                                                            if (strlen($row['evet_heading']) > 40)
                                                                $heading = substr($row['evet_heading'], 0, 40) . "...";
                                                            else
                                                                $heading = $row['evet_heading'];

                                                            if ($row_no % 2 == 0)
                                                                $row_color = "odd";
                                                            else
                                                                $row_color = "even";
                                                            echo "<tr class='$row_color'>";
                                                            ?>
                                                        <td class="align-center">
                                                            <input type="checkbox" style="margin-top:10px;" name="chkEvent" value="<?php echo $row['evet_id']; ?>" />
                                                        </td>
                                                        <td class="align-center">
                                                            <?php echo $date; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $heading; ?>
                                                        </td>
                                                        <td class="align-center">
                                                            <a href="javascript:void(0)" onclick="changeEventStatus('<?php echo $row['evet_id']; ?>', '<?php echo $row['evet_status'] ?>', 'Notice', 'adminSuccessMsg1', 'noticeTableBody');"><?php echo $row['evet_status'] ?></a>
                                                        </td>
                                                        <td class="align-center">
                                                            <a href="javascript:void(0)" onclick="removeEvent('<?php echo $row['evet_id']; ?>', 'Notice', 'adminSuccessMsg1', 'noticeTableBody');">Delete</a>
                                                        </td>
                                                        </tr>                                                                      
                                                        <?php
                                                        $row_no++;
                                                    }
                                                }
                                                else {
                                                    echo "<tr><td colspan='5'>No Notice has been added yet.</td></tr>";
                                                }
                                                ?> 
                                                </tbody>
                                            </table>   
                                        </form>
                                    </div>                                
                                </div>    
                                <div style="text-align: center;"><input type="button" class="submit-green" value="Delete Notice" name="btnDeleteNotice" onclick="deleteSelectedEvent('Notice', 'adminSuccessMsg1', 'noticeTableBody');"/></div>
                            </div> <!-- End .grid_5 -->
                        </td>                
                        <td width="50%" style="border-right: none">
                            &nbsp;
                            <div class="grid_5" style="width: 98%">
                                <div id="adminSuccessMsg2"></div>
                                <div class="module">
                                    <h2><span>Event</span></h2>

                                    <div class="module-table-body" style="height: 350px; overflow-y: auto;">
                                        <form action='' name='frmEvent'>
                                            <table id="myTable" class="tablesorter">
                                                <thead>
                                                    <tr>
                                                        <th style="width:5%">#</th>
                                                        <th style="width:20%">Date</th>
                                                        <th style="width:45%">Heading</th>
                                                        <th style="width:10%">Status</th>
                                                        <th style="width:15%">Remove</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="eventTableBody">
                                                    <?php
                                                    $sql = "SELECT `evet_id`,`evet_heading`,`evet_date`,`evet_status` FROM `tbl_event` WHERE `evet_type` = 'Event' order by `evet_date` desc ";
                                                    $res = mysqli_query($con, $sql);
                                                    if (mysqli_num_rows($res) > 0) {
                                                        $row_no = 1;
                                                        while ($row = mysqli_fetch_array($res)) {
                                                            $date = date('d-m-Y', $row['evet_date']);
                                                            if (strlen($row['evet_heading']) > 40)
                                                                $heading = substr($row['evet_heading'], 0, 40) . "...";
                                                            else
                                                                $heading = $row['evet_heading'];

                                                            if ($row_no % 2 == 0)
                                                                $row_color = "odd";
                                                            else
                                                                $row_color = "even";
                                                            echo "<tr class='$row_color'>";
                                                            ?>
                                                        <td class="align-center">
                                                            <input type="checkbox" style="margin-top:10px;" name="chkEvent" value="<?php echo $row['evet_id']; ?>" />
                                                        </td>
                                                        <td class="align-center">
                                                            <?php echo $date; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $heading; ?>
                                                        </td>
                                                        <td class="align-center">
                                                            <a href="javascript:void(0)" onclick="changeEventStatus('<?php echo $row['evet_id']; ?>', '<?php echo $row['evet_status'] ?>', 'Event', 'adminSuccessMsg2', 'eventTableBody');"><?php echo $row['evet_status'] ?></a>
                                                        </td>
                                                        <td class="align-center">
                                                            <a href="javascript:void(0)" onclick="removeEvent('<?php echo $row['evet_id']; ?>', 'Event', 'adminSuccessMsg2', 'eventTableBody');">Delete</a>
                                                        </td>
                                                        </tr>                                                                      
                                                        <?php
                                                        $row_no++;
                                                    }
                                                }
                                                else {
                                                    echo "<tr><td colspan='5'>No Event has been added yet.</td></tr>";
                                                }
                                                ?> 
                                                </tbody>
                                            </table>   
                                        </form>
                                    </div>                                
                                </div>    
                                <div style="text-align: center;"><input type="button" class="submit-green" value="Delete Event" name="btnDeleteEvent" onclick="deleteSelectedEvent('Event', 'adminSuccessMsg2', 'eventTableBody');"/></div>
                            </div> <!-- End .grid_5 -->
                        </td>
                    </tr>
                </tbody>
            </table>                  
            <?php
        } else {
            echo "You need Admin Privilage to access this Delete Notice/Event section.";
        }
    }
}
?>