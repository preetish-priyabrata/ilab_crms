<?php
include_once '../db.php';
include_once '../todo_view_pagination.php';
session_start();
$user_name = $_SESSION['empId'];

if (isset($_GET['cntAction']) && !empty($_GET['cntAction'])) {
    $todoContent = $_GET['cntAction'];
    if ($todoContent == "new") {
        ?>
        <div id="todoSuccessMsg"></div>         
        <table style="border: 0px;width:50%">
            <tr width="100%">
                <td width="30%" style="border-right: none">
                    <form name='frmToDo' method='post' id='newToDo'>                        
                        <p>
                            <label>Today's Date</label>
                            <input type='text' name='txtToDay' class='input-short' style='width: 70%' value ='<?php echo date('d-m-Y'); ?>' readonly /> 
                        </p>   
                        <p>
                            <label>Expected Closing Date</label>
<!--                            <input type="text" name='txtCloseDay' size="12" id="inputField" class='input-short' placeholder="Click here to select" style='width: 70%' onfocus="showDatePicker('inputField');" />-->
                            <input type="text" name='txtCloseDay' size="12" id="inputField" class='input-short' placeholder="Click here to select" style='width: 70%' />
                        </p>
                        <p>
                            <label>Select a Reminder Type</label>
                            <select class="input-short" style='width: 70%' name="cmbTodoType">
                                <option value="NA">Select Reminder Type</option>
                                <option value="Telephone / Conversation">Telephone / Conversation</option>
                                <option value="Email">Email</option>
                                <option value="Meeting">Meeting</option>
                            </select>
                        </p>                         
                        <p>
                            <label>Description</label>
                            <textarea rows="7" cols="90" name='txtAreaDesc' class="input-short" placeholder="Write your description here" style="resize: none; width: 70%;"></textarea>
                        </p>            
                        <p>
                            <label>Current Status</label>
                            <select class="input-short" style='width: 50%' name="cmbStatus">
                                <option value="Open">Open</option>
                            </select>
                        </p>        
                        <p>                
                            <input type="checkbox" name="chkCorporate" id="chkCorporate" onchange="displayContactList();" /> This item corresponds to a Corporate Contact                
                        </p>
                        <div id="corporateDeatils"></div>
                        <fieldset>
                            <input class="submit-green" type="button" onclick="saveToDoForm();" name="btnTodoSubmit" value="Add" /> 
                            <input class="submit-gray" type="button" onclick="clearSaveToDoForm();" name="btnTodoClear" value="Clear" />
                        </fieldset>            
                    </form>  
                </td>                
                <?php
                /*
                  <td width="70%" style="border-right: none">
                  <!--Refresh Button -->
                  <div>
                  <a href="javascript:void(0)" class="button" onclick="todoRefreshList('AddList');">
                  <span id="refreshButton">Refresh Reminder List <img src="bin.gif" width="16" height="12" alt="Display Reminder List" title="Display Reminder List"/></span>
                  </a>
                  </div>
                  &nbsp;
                  <div id="paginationTable">
                  <?php
                  $user_name = $_SESSION['empId'];
                  if (isset($_SESSION['column_head']))
                  unset($_SESSION['column_head']);
                  if (isset($_SESSION['table_heading']))
                  unset($_SESSION['table_heading']);
                  if (isset($_SESSION['sql']))
                  unset($_SESSION['sql']);
                  $_SESSION['table_heading'] = "Reminder List View";
                  $_SESSION['column_head'] = array("#" => "5", "Published Date" => "10", "Closing Date" => "10", "Reminder Type" => "10", "Details" => "25", "Status" => "10", "Contact" => "20", "Close Panel" => "20");
                  $_SESSION['sql'] = "SELECT todo.todo_id, DATE_FORMAT(todo.todo_date, '%d-%m-%Y'), DATE_FORMAT(todo.todo_endDate, '%d-%m-%Y'), todo.todo_type, todo.todo_detail, todo.todo_status, cmp.comp_name, todo.cont_id, cnt.cont_name, todo.empl_id FROM tbl_todo AS todo left outer JOIN tbl_contact AS cnt ON todo.cont_id = cnt.cont_id LEFT OUTER JOIN tbl_company AS cmp ON cnt.comp_id = cmp.comp_id WHERE todo.todo_endDate >= CURDATE() AND todo.empl_id =$user_name ORDER BY todo.todo_endDate ASC";
                  todoViewPagination('AddList');
                  ?>
                  </div>
                  </td>
                 * 
                 */
                ?>
            </tr>                    
        </table>
        <?php
    } 
    else if ($todoContent == "view") {
        ?>
        <input type="hidden" id="hiddenEmplId" name="eid" value="<?php echo $user_name; ?>" />
        <table style="border: 0px">
            <tr width="100%">
                <td width="20%" style="border-right: none; padding-top: 4em;">
                    <form name='frmViewToDo' method='post' id='frmViewToDo'>
                        <fieldset>
                            <legend>Select the Member</legend>
                            <ul>
                                <li><label><input type="radio" name="rdoMember" id="rdoSelf" checked="checked" value="self" /> Self</label></li>
                                <li><label><input type="radio" name="rdoMember" id="rdoOther" value="other" /> Others</label></li>
                            </ul>
                        </fieldset>
                        <fieldset>
                            <legend>Status of Reminder List</legend>
                            <ul>
                                <li><label><input type="checkbox" name="chkStatus" id="chkOpen" value="Open" checked /> Open</label></li>
                                <li><label><input type="checkbox" name="chkStatus" id="chkClose" value="Closed" />  Closed</label></li>
                                <li><label><input type="checkbox" name="chkStatus" id="chkCancel" value="Cancelled" />  Cancelled</label></li>
                            </ul>
                        </fieldset> 
                        <div id="divCompanyList">
                            <p>
                                <label>Company</label>
                                <input type="text" name="txtCompSearchBox" class="input-short" placeholder="Search company here" style="width: 100%" onkeyup="autopopulate(this,'Reminders');" /><span id="companySearchAjax"></span><br>                                
                                <span id="companySuggested" style="z-index: 1;position: absolute;width: 30%;"></span>
                            </p>                            
                        </div>
                        <p>
                            <input type="checkbox" name="chkCmbCompanyLst" id="chkCmbCompanyLst" onchange="displayCompanyComboList('view');"> Display Company List
                        </p>
                        <p>
                            <label>Contact</label>
                            <select class="input-short" style="width: 90%" id="contactLst" disabled>
                                <option value="NA">Select a Company First</option>
                            </select>
                        </p>   
                        <fieldset>
                            <input class="submit-green" type="button" onclick="todoRefreshList('ViewList','no');" name="btnTodoView" value="View" /> 
                            <input class="submit-gray" type="reset" name="btnTodoClear" value="Clear" />
                        </fieldset>                             
                    </form>  
                </td>                
                <td width="80%" style="border-right: none;padding-top: 0px;vertical-align: top;">
                    <!--Refresh Button -->
                    &nbsp;
                    <table style="border: 0px;">
                        <tr style="float: right; margin-right: 10px;">
                            <td style="border-right: none;">
                                <button type="submit"><img src="call.gif" height="20px" width="30px" title="Sort by Telephone/Conversation" onclick="todoRefreshList('ViewList','call');"/></button>
                            </td>
                            <td style="border-right: none;">
                                <button type="submit"><img src="meeting.gif" height="20px" width="30px" title="Sort by Meeting" onclick="todoRefreshList('ViewList','meeting');" /></button>
                            </td>
                            <td style="border-right: none;">
                                <button type="submit"><img src="mail.gif" height="20px" width="30px" title="Sort by Email" onclick="todoRefreshList('ViewList','mail');"/></button>
                            </td>
                            <td style="border-right: none;">
                                <button type="submit"><img src="reminder.gif" height="20px" width="30px" title="Show All Reminders" onclick="todoRefreshList('ViewList','all');"/></button>
                            </td>                            
                        </tr>
                        <tr>
                            <td style="border: 0px">
                                <div id="paginationTable">   
                                    <?php
                                    $user_name = $_SESSION['empId'];
                                    if (isset($_SESSION['column_head']))
                                        unset($_SESSION['column_head']);
                                    if (isset($_SESSION['table_heading']))
                                        unset($_SESSION['table_heading']);
                                    if (isset($_SESSION['sql']))
                                        unset($_SESSION['sql']);
                                    $_SESSION['table_heading'] = "Reminder List View";
                                    $_SESSION['column_head'] = array("#" => "5", "Published Date" => "13", "Closing Date" => "13", "Reminder Type" => "20", "Details" => "19", "Status" => "10", "Contact" => "20", "Action" => "10");
                                    $_SESSION['sql'] = "SELECT todo.todo_id, DATE_FORMAT(todo.todo_date, '%d-%m-%Y'), DATE_FORMAT(todo.todo_endDate, '%d-%m-%Y'), todo.todo_type, todo.todo_detail, todo.todo_status,  cmp.comp_name, todo.cont_id, cnt.cont_name, todo.empl_id FROM tbl_todo AS todo left outer JOIN tbl_contact AS cnt ON todo.cont_id = cnt.cont_id LEFT OUTER JOIN tbl_company AS cmp ON cnt.comp_id = cmp.comp_id WHERE todo.empl_id =$user_name and todo.todo_status='open' and todo.todo_endDate > '2013-06-30' ORDER BY todo.todo_date DESC";
                                    $_SESSION['todoSortByType'] = 'no';
                                    todoViewPagination('ViewList');
                                    ?>
                                </div> 
                            </td>
                        </tr>
                    </table>                   
                </td>
            </tr>                    
        </table>
        <?php
    }
}
?>
