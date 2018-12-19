<?php
include_once '../db.php';
if (isset($_GET['eventType']) && !empty($_GET['eventType'])) {
    $eventType = $_GET['eventType'];
    $msgDivId = $_GET['msgDivId'];
    $tblId = $_GET['tblId'];
    $sql = "SELECT `evet_id`,`evet_heading`,`evet_date`,`evet_status` FROM `tbl_event` WHERE `evet_type` = '$eventType' order by `evet_date` desc ";
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
                <a href="javascript:void(0)" onclick="changeEventStatus('<?php echo $row['evet_id']; ?>', '<?php echo $row['evet_status'] ?>', '<?php echo $eventType; ?>', '<?php echo $msgDivId; ?>', '<?php echo $tblId; ?>');"><?php echo $row['evet_status'] ?></a>
            </td>
            <td class="align-center">
                <a href="javascript:void(0)" onclick="removeEvent('<?php echo $row['evet_id']; ?>', '<?php echo $eventType; ?>', '<?php echo $msgDivId; ?>', '<?php echo $tblId; ?>');">Delete</a>
            </td>
            </tr>                                                                      
            <?php
            $row_no++;
        }
    }
    else {
        echo "<tr><td colspan='5'>No $eventType has been added yet.</td></tr>";
    }
}
?>
