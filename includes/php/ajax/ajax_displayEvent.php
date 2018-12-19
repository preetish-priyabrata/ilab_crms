<?php
include_once '../db.php';

if (isset($_GET['eventId']) && !empty($_GET['eventId'])) {
    $eventId = $_GET['eventId'];
    $divId = $_GET['divId'];
    $moduleBody = $_GET['moduleBody'];

    if ($divId == 'normalNoticeBoard') {
        $bodyHeight = 350;
    } else if ($divId == 'normalEventBoard') {
        $bodyHeight = 350;
    }
    $sql = "SELECT * FROM `tbl_event` WHERE `evet_id` = $eventId ";
    $res = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($res)) {
        if (strlen($row['evet_heading']) > 50)
            $heading = substr($row['evet_heading'], 0, 50) . "...";
        else
            $heading = $row['evet_heading'];
        $date = date('d-m-Y', $row['evet_date']);
        if ($row['evet_link'] != "") {
            if (!isset($_GET['divId'])) 
                $link = "<a href='../../../images/notice/" . $row['evet_link'] . "' target='_blank'>Click Here</a> to download the file.";
            else
                $link = "<a href='images/notice/" . $row['evet_link'] . "' target='_blank'>Click Here</a> to download the file.";
        }
        else
            $link = "";
        ?><h2><span><?php
                echo $heading;
                if (isset($_GET['divId'])) {
                    ?><a href="javascript:void(0)" onclick="displayPrevScreen('<?php echo $divId; ?>')"><img src = 'close-button.png' height='20' width='20' style='float: right; margin-right: 10px;' title='Exit'/></a>
                        <?php
                    }
                    ?></span></h2>   
        <?php
        echo "<div class='module-body' style='display: block; height: " . $bodyHeight . "px'; overflow-y: auto;' id='$moduleBody'><strong>Subject: </strong>" . $row['evet_heading'] . "<br><br><strong>Published On: </strong>" . $date . "<br><br><strong>Description: </strong>" . $row['evet_details'] . "<br><br>" . $link . "<br></div>";
    }
}
?>
