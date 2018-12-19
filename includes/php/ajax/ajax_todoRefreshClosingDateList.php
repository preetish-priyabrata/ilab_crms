<?php
session_start();
include_once '../db.php';
$empl_id = $_SESSION['empId'];

$sql = "SELECT distinct(`todo_endDate`) FROM `tbl_todo` WHERE `empl_id`=$empl_id and todo_status='Open' and todo_endDate > '2013-06-30' order by todo_endDate ";
$res = mysqli_query($con, $sql);
if (mysqli_num_rows($res) > 0) {
    echo "<option value='NA'>Select a Closing Date</option>";
    while ($row = mysqli_fetch_array($res)) {
        $formatted_date = date("d M Y", strtotime($row['todo_endDate']));
        $date = $row['todo_endDate'];
        echo "<option value='$date'>$formatted_date</option>";
    }
} else {
    echo "<option value='NA'>No Closing Date to select</option>";
}
?>