<?php

session_start();

/**
 * this function will run only once when the user click on the corresponding view button
 * @param string $sql is the sql statement of the corresponding view section
 * @param type $column_head is the header list of the column
 * @param type $first_time this parameter is to be deleted in future
 */
function todoViewPagination($frmName) {
    $user_name = $_SESSION['empId'];
    $con = mysqli_connect("localhost", "root", "", "cr");
    //$con = mysqli_connect("localhost", "kiitcrm_debesh", "dnayak382", "kiitcrm_cr");
    //include_once 'db.php';;
    $table_body = '';
    $table_header = '';
    $center_pages = '';
    $pagination_display = '';

    $pagination_sql = $_SESSION['sql'];
    $sortType = $_SESSION['todoSortByType'];
    echo "<input type='hidden' id='todoTabulationSortingType' value='$sortType'/>";

    $column_head = $_SESSION['column_head'];
    $table_heading = $_SESSION['table_heading'];
    $pagination_sql = stripslashes($pagination_sql);
    $result_set = mysqli_query($con, $pagination_sql);

    $num_row = mysqli_num_rows($result_set);

    $page_num = 1;

    $items_per_page = 10;

    $last_page = ceil($num_row / $items_per_page);

    $add1 = $page_num + 1;

    if ($page_num == 1) {
        $center_pages .= "<span>Page:</span> <span class = 'current'>" . $page_num . "</span><span>|</span>";
        $center_pages .= '<a href="javascript:void(0)" onclick="todoPaginationMaker(this.innerHTML, \'' . $frmName . '\');">' . $add1 . "</a>";
    }
    $limit = ' LIMIT ' . ($page_num - 1) * $items_per_page . ',' . $items_per_page;
    $final_sql = $pagination_sql . $limit;

    $column_no = 0;
    foreach ($column_head as $heading => $head_size) {
        $table_header .= "<th style='width:$head_size%'>$heading</th>";
        $column_no++;
    }
    if ($last_page != 1 && $num_row != 0) {
        $pagination_display .= '<div class="numbers">' . $center_pages . '</div>';
        if ($page_num != $last_page) {
            $nextPage = $page_num + 1;
            $pagination_display .= '<a href="javascript:void(0)" class="button" onclick="todoPaginationMaker(' . $nextPage . ', \'' . $frmName . '\');"><span>&nbsp; <img src="arrow-000-small.gif" height="9" width="12" alt="Next" /></span></a>';
            $pagination_display .= '<a href="javascript:void(0)" class="button last" onclick="todoPaginationMaker(' . $last_page . ', \'' . $frmName . '\');"><span>&nbsp; <img src="arrow-stop-000-small.gif" height="9" width="12" alt="Last" /></span></a>';
        }
    } else if ($num_row == 0) {
        $table_body .= "<tr><td colspan='$column_no'><i>No data available.</i></td></tr>";
    }

    $rs = mysqli_query($con, $final_sql);
    $row_no = (($page_num - 1) * $items_per_page) + 1;
    $row_color = "";

    $j = 1;
    while ($row = mysqli_fetch_array($rs)) {
        if ($row_no % 2 == 0) {
            $row_color = "odd";
        } else {
            $row_color = "even";
        }
        if ($row[5] == "Open") {
            $table_body .= "<tr class='$row_color' style='font-weight: bold;'><td class='align-center'>$row_no</td>";
        } else {
            $table_body .= "<tr class='$row_color'><td class='align-center'>$row_no</td>";
        }
        for ($i = 1, $count = ($column_no); $i < $count; $i++) {
            if ($i == 4) {
                $table_body .= '<td onclick="displayFullString(' . $j . ', \'todo-\')"><div style=\'cursor:pointer\'>' . substr($row[$i], 0, 20) . '<span id="todo-' . $j . '" style="display: none;">' . substr($row[$i], 20) . '</span></div></td>';
                $j++;
            } else if ($i == 6 && $row[$i] != "") {
                $table_body .= "<td>$row[$i]<br>($row[8])</td>";
            } else if ($i == 7) {
                $todo_id = $row['todo_id'];                
                $cont_name = $row['cont_name'];
                $comp_name = $row['comp_name'];
                $td_value = "";
                if ($_SESSION['empType'] == "Admin" || $row[9] == $user_name) {
                    $td_value .= "<a id='todo$todo_id' href='includes/php/ajax/ajax_editTodo.php?todoId=$todo_id&contName=$cont_name&compName=$comp_name' title='View all Reminder List' onclick='return displayModalBox(this.id);'>"
                                . "<img src='pencil.gif' width='16' height='16' alt='Edit Reminder' title='Edit Reminder'"
                                . "</a>";                                               
                }
                if ($row[9] == $user_name && $row[5] == "Open") {
                    if ($row['cont_id'] != 0) {
                        //$table_body .= '<td> <a  id="todo' . $i . '" href="includes/php/ajax/ajax_addActivity.php?contId=' . $row[7] . '&toDoId=' . $row[0] . '&toDoType=' . $row[3] . '" onclick="if(confirm(\'Do you wish to set the Reminder status to complete? Press OK to continue OR Cancel to revert the Action.\') == false){return false;}else {return displayTodoModalBox(this.id);} "><img src="cross-on-white.gif" width="16" height="16" alt="Close this" title="Close this" /></a></td>';
                        $td_value .= '<a id="todo' . $j . '" href="includes/php/ajax/ajax_addActivity.php?contId=' . $row[7] . '&toDoId=' . $row[0] . '&toDoType=' . $row[3] . '" onclick="return displayTodoModalBox(this.id);"><img src="cross-on-white.gif" width="16" height="16" alt="Close this" title="Close this" /></a>';
                    } else {
                        //$table_body .= '<td><a id="todo' . $i . '" href="includes/php/ajax/ajax_addActivity.php?contId=&toDoId=' . $row[0] . '&toDoType=' . $row[3] . '" onclick="if(confirm(\'Do you wish to set the Reminder status to complete? Press OK to continue OR Cancel to revert the Action.\') == false){return false;}else {return displayTodoModalBox(this.id); }"><img src="cross-on-white.gif" width="16" height="16" alt="Close this" title="Close this" /></a></td>';
                        $td_value .= '<a id="todo' . $j . '" href="includes/php/ajax/ajax_addActivity.php?contId=&toDoId=' . $row[0] . '&toDoType=' . $row[3] . '" onclick="return displayTodoModalBox(this.id);"><img src="cross-on-white.gif" width="16" height="16" alt="Close this" title="Close this" /></a>';
                    }
                } else {
                    $td_value .= "";
                }
                $table_body .="<td>$td_value</td>";
            } else {
                if (isset($row[10]) && $i == 8) {
                    $table_body .= "<td>$row[10]</td>";
                } else {
                    $table_body .= "<td>$row[$i]</td>";
                }
            }
        }
        $table_body .= "</tr>";
        $row_no++;
    }
    echo "
<br>
<div class = 'pagination'>
    $pagination_display
</div>
<div class = 'module' style='margin-bottom: 5px;'>
    <h2><span>$table_heading</span></h2>
    <div class = 'module-table-body'>
        <form action = ''>
            <table id = 'myTable' class = 'tablesorter'>
                <thead>
                    <tr>
                         $table_header
                    </tr>
                </thead>
                <tbody>
                    $table_body
                </tbody>
            </table>
        </form>
    </div> <!--End .module-table-body-->
</div>
<div class = 'pagination'>
    $pagination_display
</div>";
}
