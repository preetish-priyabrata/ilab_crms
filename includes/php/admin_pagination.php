<?php

session_start();

/**
 * this function will run only once when the user click on the corresponding view section
 * @param string $sql is the sql statement of the corresponding view section
 * @param type $column_head is the header list of the column
 * @param type $first_time this parameter is to be deleted in future
 */
function viewAdminPagination($admin_functionality) {
    //include_once 'db.php';;
    $con = mysqli_connect("localhost", "root", "", "cr");
    //$con = mysqli_connect("localhost", "kiitcrm_debesh", "dnayak382", "kiitcrm_cr");
    $table_body = '';
    $table_header = '';
    $center_pages = '';
    $pagination_display = '';

    $pagination_sql = $_SESSION['sql'];    
    $column_head = $_SESSION['column_head'];
    $table_heading = $_SESSION['table_heading'];

    $result_set = mysqli_query($con, $pagination_sql);

    $num_row = mysqli_num_rows($result_set);

    $page_num = 1;

    $items_per_page = 10;

    $last_page = ceil($num_row / $items_per_page);

    $add1 = $page_num + 1;

    if ($page_num == 1) {
        $center_pages .= "<span>Page:</span> <span class = 'current'>" . $page_num . "</span><span>|</span>";
        $center_pages .= "<a href='javascript:void(0)' onclick='adminPaginationMaker(this.innerHTML,\"$admin_functionality\")'>" . $add1 . "</a>";
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
            $pagination_display .= "<a href='javascript:void(0)' class='button' onclick='adminPaginationMaker($nextPage,\"$admin_functionality\")'><span>&nbsp;<img src='arrow-000-small.gif' height='9' width='12' alt='Next' /></span></a>";
            $pagination_display .= "<a href='javascript:void(0)' class='button last' onclick='adminPaginationMaker($last_page,\"$admin_functionality\")'><span>&nbsp;<img src='arrow-stop-000-small.gif' height='9' width='12' alt='Last' /></span></a>";
        }
    } else if ($num_row == 0) {
        $table_body .= "<tr><td colspan='$column_no'><i>No data available.</i></td></tr>";
    }

    $rs = mysqli_query($con, $final_sql);
    $row_no = (($page_num - 1) * $items_per_page) + 1;
    $row_color = "";

    $category = json_encode(strtolower($table_heading));

    while ($row = mysqli_fetch_array($rs)) {
        if ($row_no % 2 == 0)
            $row_color = "odd";
        else
            $row_color = "even";
        $table_body .= "<tr class='$row_color'><td class='align-center'>$row_no</td>";
        for ($i = 1, $count = ($column_no - 1); $i < $count; $i++) {
            $table_body .= "<td>$row[$i]</td>";
        }
        $row1 = json_encode($row);
        $table_body .= "<td><a href='javascript:void(0)'><img src='pencil.gif' width='16' height='16' alt='edit' title='Edit' onclick='rowEdit($row1, $category)' /></a>
                            <a href='javascript:void(0)'><img src='minus-circle.gif' width='16' height='16' alt='delete' title='Delete' onclick='rowDelete($row[0], $category)' /></a></td></tr>";
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

function todoAdminPagination() {
    //include_once 'db.php';;
    $table_body = '';
    $table_header = '';
    $center_pages = '';
    $pagination_display = '';

    $pagination_sql = $_SESSION['sql'];
    $column_head = $_SESSION['column_head'];
    $table_heading = $_SESSION['table_heading'];

    $con = mysqli_connect("localhost", "root", "", "cr");
    //$con = mysqli_connect("localhost", "kiitcrm_debesh", "dnayak382", "kiitcrm_cr");
    $result_set = mysqli_query($con, $pagination_sql);

    $num_row = mysqli_num_rows($result_set);

    $page_num = 1;

    $items_per_page = 10;

    $last_page = ceil($num_row / $items_per_page);

    $add1 = $page_num + 1;

    if ($page_num == 1) {
        $center_pages .= "<span>Page:</span> <span class = 'current'>" . $page_num . "</span><span>|</span>";
        $center_pages .= "<a href='javascript:void(0)' onclick='adminTodoPaginationMaker(this.innerHTML)'>" . $add1 . "</a>";
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
            $pagination_display .= "<a href='javascript:void(0)' class='button' onclick='adminTodoPaginationMaker($nextPage)'><span>Next <img src='arrow-000-small.gif' height='9' width='12' alt='Next' /></span></a>";
            $pagination_display .= "<a href='javascript:void(0)' class='button last' onclick='adminTodoPaginationMaker($last_page)'><span>Last <img src='arrow-stop-000-small.gif' height='9' width='12' alt='Last' /></span></a>";
        }
    } else if ($num_row == 0) {
        $table_body .= "<tr><td colspan='$column_no'><i>No data available.</i></td></tr>";
    }

    $rs = mysqli_query($con, $final_sql);
    $row_no = (($page_num - 1) * $items_per_page) + 1;
    $row_color = "";

    $category = json_encode(strtolower($table_heading));

    while ($row = mysqli_fetch_array($rs)) {
        if ($row_no % 2 == 0)
            $row_color = "odd";
        else
            $row_color = "even";
        $table_body .= "<tr class='$row_color'><td class='align-center'>$row_no</td>";
        for ($i = 1, $count = ($column_no - 1); $i < $count; $i++) {
            $table_body .= "<td>$row[$i]</td>";
        }
        $row1 = json_encode($row);
        $table_body .= "<td><a href='javascript:void(0)'><img src='pencil.gif' width='16' height='16' alt='edit' title='Edit' onclick='rowEdit($row1, $category);' /></a>";
        if ($row[3] == 'Open') {
            $table_body .= "<a href='javascript:void(0)'><img src='notification-slash.gif' width='16' height='16' alt='delete' title='Cancel' onclick='adminTodoCancel($row[0]);' /></a></td></tr>";
        }
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

?>