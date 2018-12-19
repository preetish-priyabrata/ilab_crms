<?php

include_once '../db.php';
include_once '../todo_view_pagination.php';

session_start();

if (isset($_SESSION['column_head']))
    unset($_SESSION['column_head']);
if (isset($_SESSION['table_heading']))
    unset($_SESSION['table_heading']);
if (isset($_SESSION['sql']))
    unset($_SESSION['sql']);
if (isset($_SESSION['todoSortByType']))
    unset($_SESSION['todoSortByType']);

if (isset($_GET['dynamic']) && !empty($_GET['dynamic'])) {
    $frmName = $_GET['frmName'];
    $_SESSION['table_heading'] = "Reminder List View";
    $condition = $_GET['dynamic'];
    $member = $_GET['member'];
    $_SESSION['todoSortByType'] = $_GET['sortBy'];
    if ($member == "Self") {
        $_SESSION['column_head'] = array("#" => "5", "Published Date" => "13", "Closing Date" => "13", "Reminder Type" => "20", "Details" => "19", "Status" => "10", "Contact" => "20", "Action" => "10");

        $_SESSION['sql'] = "SELECT todo.todo_id, DATE_FORMAT(todo.todo_date, '%d-%m-%Y'), DATE_FORMAT(todo.todo_endDate, '%d-%m-%Y'), todo.todo_type, todo.todo_detail, todo.todo_status,  cmp.comp_name, todo.cont_id, cnt.cont_name, todo.empl_id FROM tbl_todo AS todo LEFT OUTER JOIN tbl_contact AS cnt ON todo.cont_id = cnt.cont_id LEFT OUTER JOIN tbl_company AS cmp ON cnt.comp_id = cmp.comp_id WHERE 1 " . $condition . " and todo.todo_endDate > '2013-06-30' ORDER BY todo.todo_date DESC";
        //echo $_SESSION['sql'];
    } else if ($member == "Other") {
        $_SESSION['column_head'] = array("#" => "5", "Published Date" => "10", "Closing Date" => "10", "Reminder Type" => "10", "Details" => "20", "Status" => "10", "Contact" => "20", "Action" => "10", "User Name" => "15");

        $_SESSION['sql'] = "SELECT todo.todo_id, DATE_FORMAT(todo.todo_date, '%d-%m-%Y'), DATE_FORMAT(todo.todo_endDate, '%d-%m-%Y'), todo.todo_type, todo.todo_detail, todo.todo_status,  cmp.comp_name, todo.cont_id, cnt.cont_name, todo.empl_id, empl.empl_name FROM tbl_todo AS todo left outer JOIN tbl_contact AS cnt ON todo.cont_id = cnt.cont_id LEFT OUTER JOIN tbl_company AS cmp ON cnt.comp_id = cmp.comp_id left outer join tbl_employee as empl on todo.empl_id = empl.empl_id WHERE 1 " . $condition . " and todo.todo_endDate > '2013-06-30' ORDER BY todo.todo_date DESC";
    }
    todoViewPagination($frmName);
}
?>