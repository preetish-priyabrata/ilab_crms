<?php
include_once '../db.php';
session_start();
$empId = $_SESSION['empId'];
$display_activity_form = true;

if (isset($_POST['btnCloseReminder']) && !empty($_POST['btnCloseReminder'])) {
    $todo_id = $_POST['todoId'];
    $sqlUpdate = "update tbl_todo set todo_status='Closed' where todo_id=$todo_id";
    $resUpdate = mysqli_query($con, $sqlUpdate);
    if (mysqli_affected_rows($con) > 0) {
        $htmlToDo = "Success";
    } else {
        $htmlToDo = "Fail";
    }
    if (isset($htmlToDo)) {
        ?>
        <div id="toDoSuccessMsg" class="notification n-success">
            Reminder has been Closed.
        </div>
        <?php
    }
}

if (isset($_POST['btnActSubmit']) && !empty($_POST['btnActSubmit'])) {
    $toDay = mysqli_real_escape_string($con, $_POST['txtToDay']);
    $closeDay = mysqli_real_escape_string($con, $_POST['txtCloseDay']);
    if ((strtotime($toDay) > strtotime($closeDay)) && ($_POST['add_todo'] == "Yes")) {
        $display_activity_form = FALSE;
        ?>
        <div id="toDoSuccessMsg" class="notification n-error">
            You have choosen an incorrect reminder closing date. Please try again.
        </div>
        <?php
    } else {
        // close the reminder
        if (isset($_POST['todoId']) && !empty($_POST['todoId'])) {
            $todo_id = $_POST['todoId'];
            $sqlUpdate = "update tbl_todo set todo_status='Closed' where todo_id=$todo_id";
            $resUpdate = mysqli_query($con, $sqlUpdate);
            if (mysqli_affected_rows($con) > 0) {
                $htmlToDo = "Success";
            } else {
                $htmlToDo = "Fail";
            }
            if (isset($htmlToDo)) {
                ?>
                <div id="toDoSuccessMsg" class="notification n-success">
                    Reminder has been closed.
                </div>
                <?php
            }
        }
        //then add the activity
        $activityDate = mysqli_real_escape_string($con, $_POST['actDate']);
        $activityType = mysqli_real_escape_string($con, $_POST['actType']);
        $activityDetail = mysqli_real_escape_string($con, $_POST['actDetail']);
        $contactId = mysqli_real_escape_string($con, $_POST['cmpContactId']);
        $companyId = mysqli_real_escape_string($con, $_POST['cmpCompanyId']);
        $officeId = mysqli_real_escape_string($con, $_POST['cmpOfficeId']);
        $contStatusOld = $_POST['contStatus'];
        if ($_POST['change_status'] == "Yes") {
            $contStatusNew = $_POST['cont_status'];
            $contStatusNegative = $_POST['statusComment'];
            $contStatusPositive = $_POST['visitingMonth'] . "," . $_POST['visitingYear'];
            $statusCommentSave = ($contStatusPositive == "NA,NA") ? $contStatusNegative : $contStatusPositive;
        } else {
            $statusCommentSave = "";
            $contStatusNew = $contStatusOld;
        }

        $sql = "INSERT INTO `tbl_emp_contact` VALUES (NULL, '$activityDate', '$activityType', '$activityDetail', '$contactId', '$empId', '$companyId','$officeId','$contStatusOld','$contStatusNew','$statusCommentSave');";
        $res = mysqli_query($con, $sql);
        if ($res) {
            $htmlActivity = "Success";
            if ($_POST['ajaxRequest'] != "False") {
                echo $htmlActivity;
            }
        } else {
            $htmlActivity = "Fail";
            if ($_POST['ajaxRequest'] != "False") {
                echo $htmlActivity;
            }
        }
        if ($_POST['add_todo'] == "Yes") {
            $desc = mysqli_real_escape_string($con, $_POST['txtAreaDesc']);
            $status = mysqli_real_escape_string($con, $_POST['cmbStatus']);
            $cmbTodoType = $_POST['cmbTodoType'];

            //echo "<br />",$toDay,"<br />",$closeDay;

            $toDayDay = substr($toDay, 0, 2);
            $toDayMonth = substr($toDay, 3, 2);
            $toDayYear = substr($toDay, 6, 4);
            $toDay = $toDayYear . "-" . $toDayMonth . "-" . $toDayDay;

            $sql = "INSERT INTO `tbl_todo` (`todo_id`, `todo_date`, `todo_endDate`, `todo_detail`, `todo_status`, `todo_comment`, `empl_id`, `cont_id`, `todo_type`) VALUES (NULL, '$toDay', '$closeDay', '$desc', '$status', '', '$empId', '$contactId', '$cmbTodoType');";
            $res = mysqli_query($con, $sql);
            if ($res) {
                $htmlToDo = "Success";
                if ($_POST['ajaxRequest'] != "False") {
                    echo $htmlToDo;
                }
            } else {
                $htmlToDo = "Fail";
                if ($_POST['ajaxRequest'] != "False") {
                    echo $htmlToDo;
                }
            }
        }
    }
}

if (isset($_GET['contId']) && $display_activity_form == TRUE) {
    ?>
    <!-- List of style sheets to be included -->
    <!-- CSS Reset -->
    <link rel="stylesheet" type="text/css" href="../../../reset.css" media="screen" />

    <!-- Fluid 960 Grid System - CSS framework -->
    <link rel="stylesheet" type="text/css" href="../../../grid.css" media="screen" />
    <script src="../../../js/contactAction.js"></script>
    <!-- IE Hacks for the Fluid 960 Grid System -->
    <!--[if IE 6]><link rel="stylesheet" type="text/css" href="../../../ie6.css"  media="screen" /><![endif]-->
    <!--[if IE 7]><link rel="stylesheet" type="text/css" href="../../../ie.css" media="screen" /><![endif]-->

    <!-- Main stylesheet -->
    <link rel="stylesheet" type="text/css" href="../../../styles.css"  media="screen" />
    <!--<link rel="stylesheet" type="text/css" media="all" href="../../../css/jsDatePick_ltr.min.css" />-->  
    <!-- Datepicker JavaScript -->
    <!--    <script type="text/javascript" src="../../../js/jsDatePick.min.1.3.js"></script>
    <script type="text/javascript">
        var showDatePicker = function(field) {
            new JsDatePick({
                useMode: 2,
                target: field,
                dateFormat: "%Y-%m-%d"
            });
        };

    </script> -->
    <!-- JQuery Modal Scripts -->        
    <script type="text/javascript" src="../../../js/js/jquery.js"></script>
    <script type="text/javascript" src="../../../js/js/jquery-ui.min.js"></script>
    <link type="text/css" rel="Stylesheet" href="../../../css/css/jquery-ui.min.css" />

    <script type="text/javascript">
        $(function() {
            $("#inputField").datepicker({
                dateFormat: "yy-mm-dd",
                showOtherMonths: true,
                selectOtherMonths: true,
                // numberOfMonths: 3,
                showAnim: "slide"
            });
        });
        function displayDatePicker() {
            $(function() {
                $("#inputFieldToDo").datepicker({
                    dateFormat: "yy-mm-dd",
                    showOtherMonths: true,
                    selectOtherMonths: true,
                    showAnim: "slide"
                });
            });
        }
    </script>
    <style>
        .classname { 
            border:solid 1px #2d2d2d;  
            text-align:center;  
            padding:25px 12px 25px 12px;  
            -moz-border-radius: 5px;  
            -webkit-border-radius: 5px; 
            border-radius: 5px;
            margin: 0px 20px 0px 20px;
        }

        /* =Your Generated css 
        |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/
        .classname{
            border:inset 1px #000000;
            -moz-border-radius: 8px;
            -webkit-border-radius: 8px;
            border-radius: 8px;}
        /* End of Your Generated css 
        |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/
    </style>        
    <?php
    if (isset($_GET['toDoId']) && empty($_GET['contId'])) {
        $todoItem = $_GET['toDoId'];
        /* $sqlUpdate = "update tbl_todo set todo_status='Closed' where todo_id=$todoItem";
          $resUpdate = mysqli_query($con, $sqlUpdate);
          if ($resUpdate) {
          $htmlToDo = "Success";
          } else {
          $htmlToDo = "Fail";
          } */
        echo "<input type='hidden' name='todoId' id='todoId' value='$todoItem' />";
        //if (isset($htmlToDo)) {
        ?>
        <div id="toDoSuccessMsg" class="notification n-success" style="display: none;">
            <!-- Reminder has been Closed. -->
        </div> 
        <div class="classname notification n-attention" id="revertMsg">
            <strong>Are you sure to close this reminder?</strong><br><br><br>                
            <input class="submit-gray" type='button' name='btnYes' value='Yes' onclick='revertTodoStatus(this.value);' />
            <input class="submit-green" type='button' name='btnNo' value='No' onclick='revertTodoStatus(this.value);' />
        </div>
        <?php
        //}
    }
    if (!empty($_GET['contId'])) {
        $todoItem = $_GET['toDoId'];
        $contactId = $_GET['contId'];
        $toDoType = $_GET['toDoType'];
        $sqlContact = "select * from tbl_contact,tbl_company where tbl_contact.comp_id=tbl_company.comp_id and tbl_contact.cont_id=$contactId";
        $resContact = mysqli_query($con, $sqlContact);
        $rowContact = mysqli_fetch_array($resContact);
        $sqlContactStatus = "select * from tbl_emp_contact where cont_id = $contactId and empl_id = $empId order by empl_cont_id desc limit 0,1";
        //echo $sqlContactStatus;
        $resContactStatus = mysqli_query($con, $sqlContactStatus);
        $rowContactStatus = mysqli_fetch_array($resContactStatus);
        //$sqlTodo = "select * from tbl_contact,tbl_todo where tbl_contact.cont_id=tbl_todo.cont_id and tbl_contact.cont_id=$contactId and tbl_todo.empl_id=$empId and tbl_todo.todo_status='Open'";
        //$resTodo = mysqli_query($con, $sqlTodo);
        //$numTodo = mysqli_num_rows($resTodo);
        if (isset($htmlActivity)) {
            ?>
            <div id="activitySuccessMsg" class="notification n-success">
                Activity has been added successfully.
            </div>
        <?php } ?>
        <div id="toDoSuccessMsg"></div>
        <div id="hiddenSection"></div>
        <div class="container_16" id='addActivityDiv' style="width:110%">
            <div class="grid_12" style="margin-top: 10px;" >
                <div class="module">                    
                    <h2><span>Add Activity Details for <b><?php echo $rowContact['cont_name'] ?></b> of <b><?php echo $rowContact['comp_name'] ?></b> company</span></h2>
                    <div class="module-body" style="display:block;">
                        <form name='frmToDo' method='post' action="ajax_addActivity.php?contId=<?php echo $contactId ?>" id='addNewContactForm' onsubmit="return validateAddActivityForm();"> 
                            <input type='hidden' name='todoId' id='todoId' value='<?php echo $todoItem; ?>' />
                            <input type="hidden" name="cmpContactId" value="<?php echo $contactId ?>" />
                            <input type="hidden" name="cmpCompanyId" value="<?php echo $rowContact['comp_id'] ?>" />
                            <input type="hidden" name="cmpOfficeId" value="<?php echo $rowContact['offi_id'] ?>" />
                            <input type="hidden" name="contStatus" value="<?php echo $rowContactStatus['act_newStatus'] ?>" />
                            <input type="hidden" name="ajaxRequest" value="False" />
                            <p>
                                <label>Please select an Activity Date</label>
                                <input type="text" name='actDate' size="12" id="inputField" class='input-short' placeholder="Click here to select" style='width: 50%' />
                            </p>   
                            <p>
                                <label>Select an Activity Type</label>
                                <select class="input-short" style='width: 50%' name="actType">
                                    <option>Telephone / Conversation</option>
                                    <option <?php if ($toDoType == "Email") echo "selected='selected'"; ?>>Email</option>
                                    <option <?php if ($toDoType == "Meeting") echo "selected='selected'"; ?>>Meeting</option>
                                </select>
                            </p>
                            <p>
                                <label>Description</label>
                                <textarea rows="7" cols="90" name='actDetail' class="input-short" placeholder="Write your description here" style="resize: none; width: 70%;"></textarea>
                            </p> 
                            <hr />
                            <p>
                                <label>Do you wish to change the Status of this Contact?</label>
                                <input type="radio" name='change_status' value='Yes' onclick="displayChangeStatusForm('Yes')"/>Yes 
                                <input type="radio" name='change_status' value='No' checked='checked' onclick="displayChangeStatusForm('No')" />No<br />
                            </p>
                            <div id="changeStatusForm" style="display: none;">                                
                                <label>The current status of <b><?php echo $rowContact['cont_name'] ?></b> is <b><?php echo $rowContactStatus['act_newStatus'] ?></b> </label>
                                Change To <select class="input-medium" name="cont_status" onchange="displayCommentFormByStatus(this.value)"><?php include_once './ajax_contactStatusComboList.php'; ?></select><br />
                                <textarea class="input-long" rows="10" id="statusComment" style="display:none;" name="statusComment" placeholder="Enter the summarized comments for the selected status"></textarea>
                                <p  style="display:none;" id="positiveLabel">Enter the probable Month of visit</p>
                                <select style="display:none;" name="visitingMonth" id="visitingMonth">
                                    <option value="NA">Select</option>
                                    <option>January</option>
                                    <option>February</option>
                                    <option>March</option>
                                    <option>April</option>
                                    <option>May</option>
                                    <option>June</option>
                                    <option>July</option>
                                    <option>August</option>
                                    <option>September</option>
                                    <option>October</option>
                                    <option>November</option>
                                    <option>December</option>
                                </select>
                                <select style="display:none;" name="visitingYear" id="visitingYear" >
                                    <option value="NA">Select</option>
                                    <?php
                                    $year_limit = (int) (date("Y") + 5);
//                                    $year = $year_limit+1;
                                    for ($year = 2013; $year < $year_limit; $year++) {
                                        echo "<option>$year</option>";
                                    }
                                    ?>
                                </select>                                
                            </div>
                            <hr />
                            <?php if (isset($todoItem)) { ?>
                                <p>
                                    <label>Do you wish to add a Reminder with respect to this contact as well?</label>
                                    <input type="radio" name='add_todo' value='Yes' onclick="displayAddToDoForm('Yes', '<?php echo $contactId ?>')"/>Yes 
                                    <input type="radio" name='add_todo' value='No' checked='checked' onclick="displayAddToDoForm('No', '<?php echo $contactId ?>')" />No<br />
                                </p>
                                <div id="addToDoContainer">

                                </div>
                                <hr />
                            <?php } ?>
                            <fieldset>
                                <input class="submit-green" type="submit" name="btnActSubmit" value="Add" /> 
                                <?php
                                if (isset($todoItem)) {
                                    ?>
                                    <input class = "submit-green" type ="submit" name="btnCloseReminder" value="Close Reminder" />
                                    <?php
                                }
                                ?>                                
                                <input class="submit-gray" type="reset"  name="btnActClear" value="Clear" onclick="resetAddActivityForm();"/>
                            </fieldset> 
                        </form>  
                    </div>
                </div>
            </div>
        </div>         
        <?php
    }
    ?>
    <script>
        function resetAddActivityForm() {
            if (document.getElementsByName("btnCloseReminder")[0]) {
                document.getElementById("addToDoContainer").innerHTML = "";
            }
            document.getElementById("changeStatusForm").style.display = "none";
        }

        var revertTodoStatus = function(btnValue) {
            var parentWindow = window.parent;
            var rvtMsgDiv = document.getElementById("revertMsg");
            var successMsgDiv = document.getElementById("toDoSuccessMsg");
            if (btnValue === "Yes") {
                rvtMsgDiv.parentNode.removeChild(rvtMsgDiv);
                var todoId = document.getElementById("todoId").value;
                var postData = 'todo_id=' + todoId + '&action=' + 'update';
            }
            else if (btnValue === "No") {
                rvtMsgDiv.parentNode.removeChild(rvtMsgDiv);
                successMsgDiv.innerHTML = "Reminder has been Successfully Closed.";
                var addActvDiv = document.getElementById("addActivityDiv");
                if (addActvDiv) {
                    addActvDiv.style.display = "block";
                }
                return;
            }
            var rspText;
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            }
            else
            {
                xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
            }
            xmlhttp.onreadystatechange = function()
            {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
                {
                    rspText = xmlhttp.responseText;
                    //console.log(rspText);
                    if (rspText === "Success") {
                        successMsgDiv.style.display = "block";
                        successMsgDiv.innerHTML = "Reminder has been closed.";
                        var sortBy = parentWindow.document.getElementById("rightPanelTodoSort").value;
                        parentWindow.displayOnlyByType(sortBy);
                    }
                    else {
                        successMsgDiv.style.display = "block";
                        successMsgDiv.className = "notification n-error";
                        successMsgDiv.innerHTML = "Error occured while reverting Reminder Status.";
                    }
                }
            };
            //alert(postData);
            xmlhttp.open('POST', 'ajax_update_todo.php', true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(postData);
        };
        function validateAddActivityForm() {
            var successMsgDiv = document.getElementById("toDoSuccessMsg");
            if (document.getElementById("inputField").value === "") {
                successMsgDiv.style.display = "block";
                successMsgDiv.className = "notification n-error";
                successMsgDiv.innerHTML = "Please select an Activity Date.";
                document.getElementById("inputField").focus();
                return false;
            }
        }
    </script>
    <?php
}
?>
