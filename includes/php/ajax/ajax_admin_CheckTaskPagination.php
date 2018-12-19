<?php

include_once '../admin_pagination.php';
if (isset($_SESSION['column_head']))
    unset($_SESSION['column_head']);
if (isset($_SESSION['table_heading']))
    unset($_SESSION['table_heading']);
if (isset($_SESSION['sql']))
    unset($_SESSION['sql']);
if (isset($_SESSION['selectedEmployeeName']))
    unset($_SESSION['selectedEmployeeName']);
if (isset($_GET['sett_type']) && !empty($_GET['sett_type'])) {
    $sett_type = $_GET['sett_type'];
    $adminContent = "";
    if ($sett_type == 'comp_type') {
        $_SESSION['table_heading'] = "Company Type List";
        $_SESSION['column_head'] = array("#" => "10", "Company Type" => "30", "Type Status" => "30", "Edit Panel" => "30");
        $adminContent = "company type";
    }
    if ($sett_type == 'offi_type') {
        $_SESSION['table_heading'] = "Office Type List";
        $_SESSION['column_head'] = array("#" => "10", "Office Type" => "30", "Office Status" => "30", "Edit Panel" => "30");
        $adminContent = "office type";
    }
    if ($sett_type == 'acti_status') {
        $_SESSION['table_heading'] = "Company Status List";
        $_SESSION['column_head'] = array("#" => "10", "Company Status Type" => "30", "Status" => "30", "Edit Panel" => "30");
        $adminContent = "company status";
    }
    if ($sett_type == 'add_relation') {
        $_SESSION['table_heading'] = "Additional Relationship List";
        $_SESSION['column_head'] = array("#" => "10", "Additional Relationship" => "30", "Status" => "30", "Edit Panel" => "30");
        $adminContent = "contact relationship";
    }
    if ($sett_type == 'func_dept') {
        $_SESSION['table_heading'] = "Functional Departments List";
        $_SESSION['column_head'] = array("#" => "10", "Functional Departments" => "30", "Status" => "30", "Edit Panel" => "30");
        $adminContent = "department";
    }
    if ($sett_type == 'salute') {
        $_SESSION['table_heading'] = "Salutation List";
        $_SESSION['column_head'] = array("#" => "10", "Salutation" => "30", "Status" => "30", "Edit Panel" => "30");
        $adminContent = "salutation";
    }
    if ($sett_type == 'college') {
        $_SESSION['table_heading'] = "College List";
        $_SESSION['column_head'] = array("#" => "10", "College Name" => "30", "Status" => "30", "Edit Panel" => "30");
        $adminContent = "college list";
    }
    if ($sett_type == 'stream') {
        $_SESSION['table_heading'] = "Stream List";
        $_SESSION['column_head'] = array("#" => "10", "Stream Name" => "30", "Status" => "30", "Edit Panel" => "30");
        $adminContent = "stream";
    }
    if ($sett_type == 'travel') {
        $_SESSION['table_heading'] = "Travel Destination List";
        $_SESSION['column_head'] = array("#" => "10", "Travel Destination" => "30", "Status" => "30", "Edit Panel" => "30");
        $adminContent = "travel destination";
    }
    $_SESSION['sql'] = "select sett_id, sett_value, sett_status, sett_type from tbl_setting where sett_type='$sett_type' order by sett_value asc";
    viewAdminPagination($adminContent);
}
if (isset($_GET['companyList']) && !empty($_GET['companyList'])) {
    $_SESSION['table_heading'] = "Company List";
    $_SESSION['column_head'] = array("#" => "5", "Company Name" => "25", "Company Type" => "25", "Company Website" => "25", "Edit Panel" => "20");
    $_SESSION['sql'] = "SELECT comp_id, comp_name, comp_type, comp_website, comp_type FROM tbl_company ORDER BY comp_name ASC ";
    viewAdminPagination('company');
}
if (isset($_GET['districtList']) && !empty($_GET['districtList'])) {
    $_SESSION['table_heading'] = "District List";
    $_SESSION['column_head'] = array("#" => "5", "City" => "20", "State" => "30", "Country" => "30", "Edit Panel" => "15");
    $_SESSION['sql'] = "SELECT * FROM `tbl_address` order by addr_city asc ";
    viewAdminPagination('district');
}
if (isset($_GET['empId']) && !empty($_GET['empId'])) {
    $emp_id = $_GET['empId'];
    $start_date = $_GET['startDate'];
    $end_date = $_GET['endDate'];
    

    $_SESSION['selectedEmployeeName'] = $emp_id;

    $_SESSION['table_heading'] = "Reminder List";
    $_SESSION['column_head'] = array("#" => "5", "Published Date" => "15", "Closing Date" => "15", "Status" => "15", "Company" => "20", "Contact" => "20", "Edit Panel" => "10");
    if ($start_date != "" && $end_date != "") {
        $formatted_start_date = date("Y-m-d",  strtotime($start_date));
        $formatted_end_date = date("Y-m-d", strtotime($end_date));
        $_SESSION['sql'] = "SELECT todo.todo_id, DATE_FORMAT(todo.todo_date, '%d-%m-%Y') start_date, DATE_FORMAT(todo.todo_endDate, '%d-%m-%Y') end_date, todo.todo_status, cmp.comp_name, cnt.cont_name, todo.todo_detail, todo.cont_id FROM tbl_todo AS todo left outer JOIN tbl_contact AS cnt ON todo.cont_id = cnt.cont_id LEFT OUTER JOIN tbl_company AS cmp ON cnt.comp_id = cmp.comp_id WHERE todo.empl_id = $emp_id and todo_endDate between '$formatted_start_date' and '$formatted_end_date' ORDER BY todo.todo_date DESC";        
    } else if ($start_date != "") {
        $formatted_start_date = date("Y-m-d",  strtotime($start_date));
        $_SESSION['sql'] = "SELECT todo.todo_id, DATE_FORMAT(todo.todo_date, '%d-%m-%Y') start_date, DATE_FORMAT(todo.todo_endDate, '%d-%m-%Y') end_date, todo.todo_status, cmp.comp_name, cnt.cont_name, todo.todo_detail, todo.cont_id FROM tbl_todo AS todo left outer JOIN tbl_contact AS cnt ON todo.cont_id = cnt.cont_id LEFT OUTER JOIN tbl_company AS cmp ON cnt.comp_id = cmp.comp_id WHERE todo.empl_id = $emp_id and todo_endDate>'$formatted_start_date' ORDER BY todo.todo_date DESC";        
    } else if ($end_date != "") {
        $formatted_end_date = date("Y-m-d", strtotime($end_date));
        $_SESSION['sql'] = "SELECT todo.todo_id, DATE_FORMAT(todo.todo_date, '%d-%m-%Y') start_date, DATE_FORMAT(todo.todo_endDate, '%d-%m-%Y') end_date, todo.todo_status, cmp.comp_name, cnt.cont_name, todo.todo_detail, todo.cont_id FROM tbl_todo AS todo left outer JOIN tbl_contact AS cnt ON todo.cont_id = cnt.cont_id LEFT OUTER JOIN tbl_company AS cmp ON cnt.comp_id = cmp.comp_id WHERE todo.empl_id = $emp_id and todo_endDate<'$formatted_end_date' ORDER BY todo.todo_date DESC";        
    } else {
        $_SESSION['sql'] = "SELECT todo.todo_id, DATE_FORMAT(todo.todo_date, '%d-%m-%Y') start_date, DATE_FORMAT(todo.todo_endDate, '%d-%m-%Y') end_date, todo.todo_status, cmp.comp_name, cnt.cont_name, todo.todo_detail, todo.cont_id FROM tbl_todo AS todo left outer JOIN tbl_contact AS cnt ON todo.cont_id = cnt.cont_id LEFT OUTER JOIN tbl_company AS cmp ON cnt.comp_id = cmp.comp_id WHERE todo.empl_id = $emp_id ORDER BY todo.todo_date DESC";
    }
    todoAdminPagination();
}
if (isset($_GET['userList']) && !empty($_GET['userList'])) {
    $_SESSION['table_heading'] = "User List";
    $_SESSION['column_head'] = array("#" => "3", "User Name" => "7", "User ID" => "7", "Password" => "10", "User Type" => "10", "DOB" => "16", "Email" => "15", "Mobile" => "10", "Address" => "10", "Status" => "8", "Edit Panel" => "5");
    $_SESSION['sql'] = 'select empl_id, empl_name, empl_userId, password, empl_type, DATE_FORMAT(empl_dob,"%d-%m-%Y"), empl_email, empl_mobile, empl_address, empl_status from tbl_employee order by empl_name asc ';
    viewAdminPagination('manage user');
}
?>