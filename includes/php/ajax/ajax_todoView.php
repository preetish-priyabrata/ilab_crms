<?php
session_start();
include_once '../db.php';
$empl_id = $_SESSION['empId'];

if(isset($_SESSION['rightPanelTodoSortedBy'])){
    unset($_SESSION['rightPanelTodoSortedBy']);
}
if ((isset($_GET['type']) && !empty($_GET['type'])) || (isset($_GET['date']) && !empty($_GET['date']))) {
    $type = $_GET['type'];
    $_SESSION['rightPanelTodoSortedBy'] = $type;
    $where_statement = "";
    if ($type == 'call') {
        $where_statement = " AND todo.todo_type='Telephone / Conversation' ";
    } else if ($type == 'meeting') {
        $where_statement = " AND todo.todo_type='Meeting' ";
    } else if ($type == 'mail') {
        $where_statement = " AND todo.todo_type='Email' ";
    } else {
        $where_statement = "";
    }
    if (isset($_GET['date'])) {
        $date = $_GET['date'];
        $where_statement = " AND todo.todo_endDate='$date' ";
    }
    ?>
    <table style="width: 100%; border: 0px">  
        <?php
        $sql = "SELECT DATE_FORMAT(todo.todo_endDate, '%d-%m-%Y'), todo.todo_type, todo.todo_detail, cmp.comp_name, cnt.cont_name, todo.todo_id, todo.cont_id,cnt.comp_id FROM tbl_todo AS todo left outer JOIN tbl_contact AS cnt ON todo.cont_id = cnt.cont_id LEFT OUTER JOIN tbl_company AS cmp ON cnt.comp_id = cmp.comp_id WHERE todo.empl_id =$empl_id and todo.todo_status='Open' and todo.todo_endDate > '2013-06-30' $where_statement ORDER BY todo.todo_endDate DESC";
        $res = mysqli_query($con, $sql);
        $i = 1;
        if (mysqli_num_rows($res)) {
            while ($row = mysqli_fetch_array($res)) {
                ?>
                <tr>
                    <td style="border-bottom: 1px solid #d9d9d9;border-right: none;">
                        <b>Closing Date: </b><?php echo $row[0]; ?>
                        <?php                                               
                        if ($row[6] != 0) {
                            //echo '<a  id="todo' . $i . '" href="includes/php/ajax/ajax_addActivity.php?contId=' . $row[6] . '&toDoId=' . $row[5] . '&toDoType=' . $row[1] . '" onclick="if(confirm(\'Do you wish to set the Reminder status to complete? Press OK to continue OR Cancel to revert the Action.\') == false){return false;}else {return displayTodoModalBox(this.id);}" style="float: right; margin-right: 1px;"><img src="cross-on-white.gif" width="16" height="16" alt="Close this" title="Close this" /></a>';
                            echo '<a  id="todo' . $i . '" href="includes/php/ajax/ajax_addActivity.php?contId=' . $row[6] . '&toDoId=' . $row[5] . '&toDoType=' . $row[1] . '" onclick="return displayTodoModalBox(this.id);" style="float: right; margin-right: 1px;"><img src="cross-on-white.gif" width="16" height="16" alt="Close this" title="Close this" /></a>';
                        } else {
                            //echo '<a id="todo' . $i . '" href="includes/php/ajax/ajax_addActivity.php?contId=&toDoId=' . $row[5] . '&toDoType=' . $row[1] . '" onclick="if(confirm(\'Do you wish to set the Reminder status to complete? Press OK to continue OR Cancel to revert the Action.\') == false){return false;}else {return displayTodoModalBox(this.id);}"style="float: right; margin-right: 1px;"><img src="cross-on-white.gif" width="16" height="16" alt="Close this" title="Close this" /></a>';
                            echo '<a id="todo' . $i . '" href="includes/php/ajax/ajax_addActivity.php?contId=&toDoId=' . $row[5] . '&toDoType=' . $row[1] . '" onclick="return displayTodoModalBox(this.id);" style="float: right; margin-right: 1px;"><img src="cross-on-white.gif" width="16" height="16" alt="Close this" title="Close this" /></a>';
                        }
                        ?>
                        <br>
                        <?php if ($row[1] != "") { ?>
                            <b>Type: </b><?php echo $row[1]; ?><br>
                        <?php } ?>
                        <?php if (isset($row[3])) { ?>
                            <b>Contact: </b><?php echo $row[3]; ?><br>
                            <!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<?php //echo "<a id='rightPanelContact$row[6]' href='includes/php/ajax/ajax_displayContactDetails.php?contId=$row[6]' title='$row[4] - $row[3]' onclick='return displayModalBox(this.id);' class='modal-contact'>$row[4]</a>"; ?>)<br> -->
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<?php echo "<a href='javascript:void(0)' onclick='displayContactDetails($row[6],\"$row[3]\",$row[7]);' class='modal-contact'>$row[4]</a>"; ?>)<br>
                        <?php } ?>
                        <div style="cursor: pointer;" onclick="displayFullString('<?php echo $row[5]; ?>', 'todoAdmin-');"><b>Details: </b><?php echo substr($row[2], 0, 20); ?><span id="todoAdmin-<?php echo $row[5] ?>" style="display:none;"><?php echo substr($row[2], 20); ?></span></div></td>
                    </td>
                </tr>  
                <?php
                $i++;
            }
        } else {
            echo "<tr><td><b><i>No Reminder found</i></b></td></tr>";
        }
        ?>
    </table>
    <?php
}
?>
