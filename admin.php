<?php
include_once './protected.php';
include 'includes/php/db.php';
include_once 'includes/php/todo_view_pagination.php';
$_SESSION['titile'] = "CRM";
$empl_id = $_SESSION['empId'];
?>
<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset=utf-8" />
        <title id="titleChanger">
            <?php echo $_SESSION['titile']; ?>
        </title>

        <!-- CSS Reset -->
        <link rel="stylesheet" type="text/css" href="reset.css" media="screen" />

        <meta name="author" content="Innovadors Lab Pvt. Ltd" />
        <link rel="shortcut icon" href="login/image/favicon.ico"> 
        <!-- Fluid 960 Grid System - CSS framework -->
        <link rel="stylesheet" type="text/css" href="grid.css" media="screen" />        

        <!-- JQuery Modal Scripts -->        
        <script type="text/javascript" src="js/js/jquery.js"></script>
        <script type="text/javascript" src="js/js/jquery-ui.min.js"></script>
        <link type="text/css" rel="Stylesheet" href="css/css/jquery-ui.min.css" />

        <script type="text/javascript">
            $(function() {
                $("#inputField,#inputField1,#txtStartDate,#txtEndDate,#inputFieldToDo").datepicker({
                    dateFormat: "dd-mm-yy",                    
                    showOtherMonths: true,
                    selectOtherMonths: true,
                    numberOfMonths: 3,
                    showAnim: "slide"
                });
            });
            function displayDatePicker() {
                $(function() {
                    $("#inputField,#inputField1,#txtStartDate,#txtEndDate").datepicker({
                        dateFormat: "dd-mm-yy",                        
                        showOtherMonths: true,
                        selectOtherMonths: true,
                        numberOfMonths: 3,
                        showAnim: "slide"
                    });
                });
            }
        </script>  

        <!-- IE Hacks for the Fluid 960 Grid System -->
        <!--[if IE 6]><link rel="stylesheet" type="text/css" href="ie6.css"  media="screen" /><![endif]-->
        <!--[if IE 7]><link rel="stylesheet" type="text/css" href="ie.css" media="screen" /><![endif]-->

        <!-- Main stylesheet -->
        <link rel="stylesheet" type="text/css" href="styles.css"  media="screen" />
        <link type="text/css" rel="Stylesheet" href="css/custome.css" />   

        <!-- CRM Calendar -->
        <link rel="stylesheet" type="text/css" href="css/calendar_style.css" media="screen" /> 

        <!-- WYSIWYG editor stylesheet -->
        <!-- <link rel="stylesheet" type="text/css" href="jquery.wysiwyg.css" media="screen" /> -->

        <!-- Table sorter stylesheet -->
        <!-- <link rel="stylesheet" type="text/css" href="tablesorter.css" media="screen" /> -->

        <!-- Thickbox stylesheet -->
        <!-- <link rel="stylesheet" type="text/css" href="thickbox.css" media="screen" /> -->

        <!-- Themes. Below are several color themes. Uncomment the line of your choice to switch to different color. All styles commented out means blue theme. -->
        <!-- <link rel="stylesheet" type="text/css" href="theme-blue.css" media="screen" /> -->
        <!-- <link rel="stylesheet" type="text/css" href="css/theme-red.css" media="screen" /> -->
        <!-- <link rel="stylesheet" type="text/css" href="css/theme-yellow.css" media="screen" /> -->
        <!-- <link rel="stylesheet" type="text/css" href="css/theme-green.css" media="screen" /> -->
        <!-- <link rel="stylesheet" type="text/css" href="css/theme-graphite.css" media="screen"/> -->         

        <!-- Style Sheet related to DatePicker -->
        <!--<link rel="stylesheet" type="text/css" media="all" href="css/jsDatePick_ltr.min.css" />--> 

        <!-- JavaScript Section -->            
        <!-- Datepicker JavaScript -->
        <!-- <script type="text/javascript" src="js/jsDatePick.min.1.3.js"></script>
        <script type="text/javascript">
            var showDatePicker = function(field) {
                new JsDatePick({
                    useMode: 2,
                    target: field,
                    dateFormat: "%d-%m-%Y"
                });
            };
        </script>-->


        <!-- Display ModalBox -->
        <link type="text/css" rel="Stylesheet" href="css/modalBox_style.css" />        
        <script src="js/modalBox.js"></script>

        <!-- Display Submenu Script -->
        <script src ="js/displaySubMenu_1.js"></script>        
        <script src ="js/toDoListAction.js"></script>         
        <script src ="js/adminTaskAction.js"></script>                                 
        <script src ="js/adminPaginationMaker.js"></script> 
        <script src ="js/contactAction.js"></script>
        <script src ="js/activityAction.js"></script>
        <script src ="js/eventAction.js"></script>  
        <script src ="js/accountAction.js"></script>
        <script src="js/countdownTimer.js"></script>
        <script src="js/calanderAction.js"></script>    
        <script src="js/travelPlanAction.js"></script>
        <script src="js/reportAction.js"></script>          
    </head>
    <body oncontextmenu="return false"> 
        <input type="hidden" id="currDate" name="currDate" />
        <div id="simplemodal-overlay"></div>
        <div id="modalBox-Div"></div>
        <div id="ajaxLoaderProcessing" style="top:0px; left: 0px; height: 100%; width: 100%; position: absolute; display: table; background-color: darkseagreen; z-index: 1000; opacity: 0.5; filter: alpha(opacity=50);">
            <p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'>
                <img src='ajax-loader.gif' height='25' width='25'>
                <span style='font-size: 200%;color: #000000;'> Please Wait<img scr="search-loading.gif" height='25' width='100' />
                </span>
            </p>
        </div>        
        <!-- Header -->
        <a href="#" name="top"></a>
        <div id="header">
            <!-- Header. Status part -->
            <div id="header-status">
                <div class="container_12">                    
                    <div class="grid_3">
                        <span style="text-align: left;color: white;">Welcome, <?php echo $_SESSION['empName']; ?> || <a id='updateUser' href='includes/php/updateUser.php' onclick='return displayModalBox(this.id);' title="Edit Profile"style="color: yellow;">Edit Profile</a></span>&nbsp;
                    </div>
                    <div class="grid_7">
                        &nbsp;                      
                    </div>
                    <div class="grid_2">                        
                        <a href="login/logout.php" id="logout">Logout</a>
                    </div>
                </div>
                <div style="clear:both;"></div>
            </div> <!-- End #header-status -->                       
        </div> <!-- End #header -->

        <div class="container_12">                    
            <!-- Dashboard icons -->
            <div class="grid_7" style="width: 100%">
                <a href="javascript:void(0)" class="dashboard-module" name="contact"  onclick="contentSelector('contact');">
                    <span>Contacts</span>
                </a>                
                <a href="javascript:void(0)" class="dashboard-module" name="toDoList" onclick="contentSelector('toDoList');">

                    <span>Reminder List</span>
                </a>
                <!--a href="javascript:void(0)" class="dashboard-module" name="account" onclick="contentSelector(this);">

                    <span>Accounts</span>
                </a-->
                <!--a href="javascript:void(0)" class="dashboard-module" name="activity" onclick="contentSelector(this);">

                    <span>Activity</span>
                </a-->
                <a href="javascript:void(0)" class="dashboard-module" name="report"  onclick="contentSelector('report');">

                    <span>Reports</span>
                </a>
                <a href="javascript:void(0)" class="dashboard-module" name="calendar" onclick="contentSelector('calendar');">

                    <span>Calendar</span>
                </a>
                <?php
                if ($_SESSION['empType'] == "Admin") {
                    echo "<input type='hidden' id='empType' value='Admin'/>";
                    ?>                
                    <a href="javascript:void(0)" class="dashboard-module" name="admin" onclick="contentSelector('admin');">
                        <span>Administrator</span>
                    </a>
                    <a href="javascript:void(0)" class="dashboard-module" name="event" onclick="contentSelector('event');">

                        <span>Events/Notices</span>
                    </a>
                <?php } ?>
                <div style="clear: both"></div>
                <div id="merqueeNoticeBoard" style="height:30px; background-color: white;">
                                  <!--span>M<a href="javascript:void(0)" onclick='displayPrevScreen("merqueeNoticeBoard");'><img src="refresh.png" height="20" width="20" title="Refresh" style="float: right; margin-right: 10px;" /></a></span-->
                    <table style="width: 100%">
                        <tr style="width: 100%">
                            <td style="width: 95%;">
                        <marquee align="absmiddle" scrollamount="2" truespeed="truespeed" scrolldelay="45" onmouseover="this.stop();" onmouseout="this.start();" style="margin-top:5px; ">
                            <?php
                            $sql = "SELECT `evet_id` , `evet_heading` , `evet_type`, `evet_date` FROM `tbl_event` WHERE `evet_status`='Active' ORDER BY `evet_date` ";
                            $res = mysqli_query($con, $sql);
                            if (mysqli_num_rows($res) > 0) {
                                //echo "<ul>";
                                while ($row = mysqli_fetch_array($res)) {
                                    //to display the new.gif image for 24hour within the publication of notice or event
                                    /*
                                      $published_date = $row['evet_date'];
                                      $now = time();
                                      $time_diff = $now - $published_date;
                                      if (($time_diff / (60 * 60 * 24)) < 1)
                                      $new = "<img src='new.gif' width='30' height='15' />";
                                      else
                                      $new = "";
                                     */
                                    if ($row['evet_type'] == 'Event') {
                                        ?>                                        
                                        <img src="notification-exclamation.gif" width="16" height="16" alt="Event Bullate" style=" margin-right: 10px;margin-left: 10px; " /><a id="event<?php echo $row[0]; ?>" href="includes/php/ajax/ajax_displayEvent.php?eventId=<?php echo $row[0]; ?>" onclick = "return displayModalBox(this.id);" ><span style="color: green"><?php echo $row[1]; ?></span><?php //echo $new;             ?></a>

                                        <?php
                                    } else {
                                        ?>
                                        <img src="notification-information.gif" width="16" height="16" alt="Event Bullate" style="margin-right: 10px;margin-left: 10px; " /><a id="event<?php echo $row[0]; ?>" href="includes/php/ajax/ajax_displayEvent.php?eventId=<?php echo $row[0]; ?>" onclick = "return displayModalBox(this.id);" ><span style="color: blue"><?php echo $row[1]; ?></span><?php //echo $new;                      ?></a>
                                        <?php
                                    }
                                }
                            } else {
                                echo "No Event/Notice has been added yet.";
                            }
                            ?>
                        </marquee>
                        </td>
                        <td style="width: 5%; vertical-align: top;text-align: center;background: rgb(246, 246, 246);background: rgba(246, 246, 246, 1);">
                            <span style="font-size:xx-small; text-align: center;">Refresh After: <span id="time">5:00</span></span> 
                        </td>
                        </tr>
                    </table>                                        
                </div>
            </div> <!-- End .grid_7 -->                      
        </div> <!-- End .grid_5 -->

        <table style="width: 100%;">
            <tr>
                <td style="width: 80%; padding: 10px; vertical-align: top;">
                    <!-- Content Section -->
                    <div class="container_12">
                        <div class="grid_12">                        
                            <div id="logo">
                                <ul id="nav"></ul>

                            </div><!-- End. #Logo -->
                        </div><!-- End. .grid_12-->                    
                    </div><!-- End. .container_12 -->

                    <!-- Sub navigation --> 
                    <div class="grid_12" style="width: 100%;">
                        <div class="module">
                            <h2><span id="contentHeading"></span></h2>

                            <div class="module-body" id="mainContent" style="float: top;padding:20px 12px 20px 20px;">
                                <h1 align="center">Welcome to the <br />Corporate Relationship Management Application</h1>
                            </div> <!-- End .module-body -->

                        </div>  <!-- End .module -->
                        <div style="clear:both;"></div>
                    </div> <!-- End .grid_12 -->
                </td>
                <td style="width: 20%;padding: 10px; vertical-align: top;">
                    <!-- Reminder Section -->
                    <div class="grid_5" style="width: 100%; float: left; ">
                        <div class="module" >
                            <h2><span>My Reminder List</span></h2>
                            <div class="module-body" style="height: 400px;padding-top: 5px;">
                                <?php
                                if (isset($_SESSION['rightPanelTodoSortedBy'])){
                                    $rightPanelTodoSortedBy = $_SESSION['rightPanelTodoSortedBy'];
                                }
                                else{
                                    $rightPanelTodoSortedBy = 'all';
                                }
                                echo "<input type='hidden' id='rightPanelTodoSort' value='$rightPanelTodoSortedBy'/>";
                                ?>
                                <table style="width: 100%; border: 0px">
                                    <tr>
                                        <td style="border-right: none;">
                                            <button type="submit"><img src="call.gif" height="25px" width="25px" title="Sort by Telephone/Conversation" onclick="displayOnlyByType('call');"/></button>
                                        </td>
                                        <td style="border-right: none;">
                                            <button type="submit"><img src="meeting.gif" height="25px" width="25px" title="Sort by Meeting" onclick="displayOnlyByType('meeting');" /></button>
                                        </td>
                                        <td style="border-right: none;">
                                            <button type="submit"><img src="mail.gif" height="25px" width="25px" title="Sort by Email" onclick="displayOnlyByType('mail');"/></button>
                                        </td>
                                        <td style="border-right: none;">
                                            <button type="submit"><img src="reminder.gif" height="25px" width="25px" title="Show All Reminders" onclick="displayOnlyByType('all');"/></button>
                                        </td>
                                    </tr>                             
                                    <tr>
                                        <td colspan="4" style="border-bottom: 1px solid #d9d9d9;border-right: none;">
                                            <p>                                               
                                                <select class="input-short" style='width: 100%' name="cmbRemindClosingDate" onchange="displayReminderByDate(this);">
                                                    <?php
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
                                                </select>
                                            </p> 
                                        </td>
                                    </tr>
                                </table>
                                <div id="reminderTable" style="overflow-y: auto; height: 75%">
                                    <table style="width: 100%; border: 0px">  
                                        <?php
                                        $sql = "SELECT DATE_FORMAT(todo.todo_endDate, '%d-%m-%Y'), todo.todo_type, todo.todo_detail, cmp.comp_name, cnt.cont_name, todo.todo_id, todo.cont_id,cnt.comp_id FROM tbl_todo AS todo left outer JOIN tbl_contact AS cnt ON todo.cont_id = cnt.cont_id LEFT OUTER JOIN tbl_company AS cmp ON cnt.comp_id = cmp.comp_id WHERE todo.empl_id =$empl_id and todo.todo_status='Open' and todo.todo_endDate > '2013-06-30' ORDER BY todo.todo_endDate DESC";
                                        $res = mysqli_query($con, $sql);
                                        if (mysqli_num_rows($res)) {
                                            $i = 1;
                                            while ($row = mysqli_fetch_array($res)) {
                                                ?>
                                                <tr>
                                                    <td style="border-bottom: 1px solid #d9d9d9;border-right: none;">
                                                        <b>Closing Date: </b><?php echo $row[0]; ?>
                                                        <?php
                                                        if ($row[6] != 0) {
                                                            //echo '<a  id="todo' . $i . '" href="includes/php/ajax/ajax_addActivity.php?contId=' . $row[6] . '&toDoId=' . $row[5] . '&toDoType=' . $row[1] . '" onclick="if(confirm(\'Do you wish to set the Reminder status to complete? Press OK to continue OR Cancel to revert the Action.\') == false) {return false;}else {return displayTodoModalBox(this.id);}" style="float: right; margin-right: 1px;"><img src="cross-on-white.gif" width="16" height="16" alt="Close this" title="Close this" /></a>';
                                                            echo '<a  id="todo' . $i . '" href="includes/php/ajax/ajax_addActivity.php?contId=' . $row[6] . '&toDoId=' . $row[5] . '&toDoType=' . $row[1] . '" onclick="return displayTodoModalBox(this.id);" style="float: right; margin-right: 1px;"><img src="cross-on-white.gif" width="16" height="16" alt="Close this" title="Close this" /></a>';
                                                        } else {
                                                            //echo '<a id="todo' . $i . '" href="includes/php/ajax/ajax_addActivity.php?contId=&toDoId=' . $row[5] . '&toDoType=' . $row[1] . '" onclick="if(confirm(\'Do you wish to set the Reminder status to complete? Press OK to continue OR Cancel to revert the Action.\') == false){return false;}else {return displayTodoModalBox(this.id);}" style="float: right; margin-right: 1px;"><img src="cross-on-white.gif" width="16" height="16" alt="Close this" title="Close this" /></a>';
                                                            echo '<a id="todo' . $i . '" href="includes/php/ajax/ajax_addActivity.php?contId=&toDoId=' . $row[5] . '&toDoType=' . $row[1] . '" onclick="return displayTodoModalBox(this.id);" style="float: right; margin-right: 1px;"><img src="cross-on-white.gif" width="16" height="16" alt="Close this" title="Close this" /></a>';
                                                        }
                                                        ?><br>
                                                        <?php if ($row[1] != "") { ?>
                                                            <b>Type: </b><?php echo $row[1]; ?><br>
                                                        <?php } ?>
                                                        <?php if (isset($row[3])) { ?>
                                                            <b>Contact: </b><?php echo $row[3]; ?><br>
                                                            <!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<?php //echo "<a id='rightPanelContact$row[6]' href='includes/php/ajax/ajax_displayContactDetails.php?contId=$row[6]' title='$row[4] - $row[3]' onclick='return displayModalBox(this.id);' class='modal-contact'>$row[4]</a>";           ?>)<br>-->
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<?php echo "<a href='javascript:void(0)' onclick='displayContactDetails($row[6],\"$row[3]\",$row[7]);' class='modal-contact'>$row[4]</a>"; ?>)<br>
                                                        <?php } ?>
                                                        <div style="cursor: pointer;" onclick="displayFullString('<?php echo $row[5]; ?>', 'todoAdmin-');"><b>Details: </b><?php echo substr($row[2], 0, 20); ?><span id="todoAdmin-<?php echo $row[5] ?>" style="display:none;" ><?php echo substr($row[2], 21); ?></span></div></td>
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
                                </div>
                            </div> 
                        </div>
                    </div>
                </td>
            </tr>
        </table>        

        <!-- Footer -->        
        <div id="footer">
            <div class="container_12">
                <div class="grid_12">
                    <!-- You can change the copyright line for your own -->
                    <p>&copy; 2013. <a href="http://www.innovadorslab.com" title="Visit For More Details" target="_blank">Design and Developed by InnovadorsLab</a></p>
                </div>
            </div>
            <div style="clear:both;"></div>
        </div> <!-- End #footer -->
        <?php //usleep(10000000);  ?>
        <script>
            window.onload = function() {
                startCountDown();
                var ajaxLoader = document.getElementById("ajaxLoaderProcessing");
                ajaxLoader.innerHTML = "";
                ajaxLoader.innerHTML = "";
                ajaxLoader.style.top = "";
                ajaxLoader.style.left = "";
                ajaxLoader.style.width = "";
                ajaxLoader.style.height = "";
                ajaxLoader.style.position = "";
                ajaxLoader.style.display = "";
                ajaxLoader.style.backgroundColor = "";
                ajaxLoader.style.zIndex = "";
            };
            //window.onbeforeunload = confirmCloseCrm;
            var today = new Date();
            document.getElementById("currDate").value = today.getDate();
        </script>
    </body>
</html>
<?php
if (isset($_SESSION['ErrMsg']) && !empty($_SESSION['ErrMsg'])) {
    echo $_SESSION['ErrMsg'];
    unset($_SESSION['ErrMsg']);
}
?>