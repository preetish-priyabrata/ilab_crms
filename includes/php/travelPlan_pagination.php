<?php

session_start();

function viewTravelPlanPagination() {
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
        $center_pages .= "<a href='javascript:void(0)' onclick='travelPlanPaginationMaker(this.innerHTML);'>" . $add1 . "</a>";
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
            $pagination_display .= "<a href='javascript:void(0)' class='button' onclick='travelPlanPaginationMaker($nextPage);'><span>&nbsp; <img src='arrow-000-small.gif' height='9' width='12' alt='Next' /></span></a>";
            $pagination_display .= "<a href='javascript:void(0)' class='button last' onclick='travelPlanPaginationMaker($last_page);'><span>&nbsp; <img src='arrow-stop-000-small.gif' height='9' width='12' alt='Last' /></span></a>";
        }
    } else if ($num_row == 0) {
        $table_body .= "<tr><td colspan='$column_no'><i>No data available.</i></td></tr>";
    }

    $rs = mysqli_query($con, $final_sql);
    $row_no = (($page_num - 1) * $items_per_page) + 1;
    $row_color = "";    

    $j = 1;
    while ($row = mysqli_fetch_array($rs)) {
        //$rowArray = [];
        $rowArray[0] = $row[0];
        if ($row_no % 2 == 0){
            $row_color = "odd";
        }
        else{
            $row_color = "even";
        }
        $table_body .= "<tr class='$row_color'><td class='align-center'>$row_no</td>";
        for ($i = 1, $count = ($column_no - 1); $i < $count; $i++) {            
            if($i == 1){
                $startDate = date("d-m-Y",  strtotime($row[$i]));
                $startTime = date("h:i A",strtotime($row[$i]));
                $table_body .= "<td>$startDate<br>($startTime)</td>";
                $rowArray[$i] = $startDate; 
            }
            if($i == 2){
                $endDate = date("d-m-Y",  strtotime($row[$i]));
                $endTime = date("h:i A",  strtotime($row[$i]));
                $table_body .= "<td>$endDate<br>($endTime)</td>";
                $rowArray[$i] = $endDate;
            }
            if($i == 4) {
                $table_body .= '<td onclick="displayFullString(' . $j . ', \'trav-\');"><div style=\'cursor:pointer\'>' . substr($row[$i], 0, 30) . '<span id="trav-' . $j . '" style="display: none;">' . substr($row[$i], 30) . '</span></div></td>';
                $j++;
                $rowArray[$i] = $row[$i];
            }
            if($i == 3){
                $table_body .= "<td>$row[$i]</td>";                
            }            
        }
        $rowArray[3] = $row[5];
        $rowArray[5] = date("h:i A", strtotime($row[1]));
        $rowArray[6] = date("h:i A", strtotime($row[2]));
        $row1 = json_encode($rowArray);
        $table_body .= "<td><a href='javascript:void(0)'><img src='pencil.gif' width='16' height='16' alt='edit' title='Edit' onclick='travelRowEdit($row1);' /></a>
            <a href='javascript:void(0)'><img src='minus-circle.gif' width='16' height='16' alt='delete' title='Delete' onclick='travelRowDelete($row[0]);' /></a></td></tr>";
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