<?php
include_once '../db.php';
session_start();
if (isset($_POST['btnTodoSubmit']) && !empty($_POST['btnTodoSubmit'])) {
    $toDay = mysqli_real_escape_string($con, $_POST['txtToDay']);
    $closeDay = mysqli_real_escape_string($con, $_POST['txtCloseDay']);
    $desc = mysqli_real_escape_string($con, $_POST['txtAreaDesc']);
    $status = mysqli_real_escape_string($con, $_POST['cmbStatus']);
    $contactId = mysqli_real_escape_string($con, $_POST['cmpContactId']);
    $cmbTodoType = $_POST['cmbTodoType'];
    $empId = $_SESSION['empId'];

    $toDayDay = substr($toDay, 0, 2);
    $toDayMonth = substr($toDay, 3, 2);
    $toDayYear = substr($toDay, 6, 4);
    $toDay = $toDayYear . "-" . $toDayMonth . "-" . $toDayDay;

    $closeDayDay = substr($closeDay, 0, 2);
    $closeDayMonth = substr($closeDay, 3, 2);
    $closeDayYear = substr($closeDay, 6, 4);
    $closeDay = $closeDayYear . "-" . $closeDayMonth . "-" . $closeDayDay;


    if (strtotime($toDay) > strtotime($closeDay)) {
        if ($_POST['ajaxRequest'] == "False") {
            $resultDisplay = '<div id="todoSuccessMsg"  class="notification n-error">
                                The closing date cannot be an earlier date
                            </div> "';
        } else
            echo "Wrong Date";
    } else {
        $sql = "INSERT INTO `tbl_todo` (`todo_id`, `todo_date`, `todo_endDate`, `todo_detail`, `todo_status`, `todo_comment`, `empl_id`, `cont_id`, `todo_type`) VALUES (NULL, '$toDay', '$closeDay', '$desc', '$status', '', '$empId', '$contactId', '$cmbTodoType');";
        $res = mysqli_query($con, $sql);
        if ($_POST['ajaxRequest'] == "False") {
            if ($res) {
                $resultDisplay = '<div id="todoSuccessMsg"  class="notification n-success">
                                The Reminder has been successfully added
                            </div> "';
            } else {
                $resultDisplay = '<div id="todoSuccessMsg"  class="notification n-success">
                                The Reminder could not be added.
                            </div> "';
            }
        } else {
            if ($res) {
                echo "Success";
            } else {
                echo "Fail";
            }
        }
    }
}

if ($_GET['include'] == "style") {
    ?>
    <!-- 
    List of style sheets to be included
    -->
    <!-- CSS Reset -->
    <link rel="stylesheet" type="text/css" href="../../../reset.css" media="screen" />

    <!-- Fluid 960 Grid System - CSS framework -->
    <link rel="stylesheet" type="text/css" href="../../../grid.css" media="screen" />

    <!-- IE Hacks for the Fluid 960 Grid System -->
    <!--[if IE 6]><link rel="stylesheet" type="text/css" href="../../../ie6.css"  media="screen" /><![endif]-->
    <!--[if IE 7]><link rel="stylesheet" type="text/css" href="../../../ie.css" media="screen" /><![endif]-->

    <!-- Main stylesheet -->
    <link rel="stylesheet" type="text/css" href="../../../styles.css"  media="screen" />
    <!-- JQuery Modal Scripts -->        
    <script type="text/javascript" src="../../../js/js/jquery.js"></script>
    <script type="text/javascript" src="../../../js/js/jquery-ui.min.js"></script>
    <link type="text/css" rel="Stylesheet" href="../../../css/css/jquery-ui.min.css" />

    <script type="text/javascript">
        $(function() {
            $("#inputFieldToDo").datepicker({
                dateFormat: "dd-mm-yy",
                showOtherMonths: true,
                selectOtherMonths: true,
                showAnim: "slide"
            });
        });
    </script>
    <!--<link rel="stylesheet" type="text/css" media="all" href="../../../css/jsDatePick_ltr.min.css" />-->    
    <!-- Datepicker JavaScript -->
    <!--    <script type="text/javascript" src="../../../js/jsDatePick.min.1.3.js"></script>
    <script type="text/javascript">
        var showDatePicker = function() {
            //alert("Mayank");
            new JsDatePick({
                useMode: 2,
                target: "inputFieldToDo",
                dateFormat: "%d-%m-%Y"
            });
        };
    </script> -->

    <?php
}

if (isset($_GET['contId']) && !empty($_GET['contId'])) {
    $contactId = $_GET['contId'];
    $sqlContact = "select * from tbl_contact,tbl_company where tbl_contact.comp_id=tbl_company.comp_id and tbl_contact.cont_id=$contactId";
    $resContact = mysqli_query($con, $sqlContact);
    $rowContact = mysqli_fetch_array($resContact);
    echo $resultDisplay;
    ?>
    <div id="toDoMsg"></div>
    <div id="hiddenSection"></div>
    <div class="container_16">
        <div class="grid_12" style="margin-top: 10px;margin-right:0px;width:100%" >

            <div class="module" style="">
                <h2><span>Add Reminder for <b><?php echo $rowContact['cont_name'] ?></b> of <b><?php echo $rowContact['comp_name'] ?></b> company</span></h2>
                <div class="module-body" style="display:block; ">

                    <form name='frmToDo' method='post' id='newToDo' onsubmit="return validateAddToDoForm();" > 
                        <input type="hidden" name="cmpContactId" value="<?php echo $contactId ?>" />
                        <input type="hidden" name="ajaxRequest" value="False" />
                        <p>
                            <label>Today's Date</label>
                            <input type='text' name='txtToDay' class='input-short' style='width: 50%' value ='<?php echo date('d-m-Y'); ?>' readonly /> 
                        </p>   
                        <p>
                            <label>Expected Closing Date</label>
                            <input type="text" name='txtCloseDay' size="12" id="inputFieldToDo" class='input-short' placeholder="Click here to select" style='width: 50%' />
                        </p>
                        <p>
                            <label>Reminder Type</label>
                            <select class="input-short" style='width: 50%' name="cmbTodoType">
                                <option>Telephone / Conversation</option>
                                <option>Email</option>
                                <option>Meeting</option>
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
                        <?php if ($_GET['include'] == "style") {
                            ?>
                            <fieldset>
                                <input class="submit-green" type="submit" name="btnTodoSubmit" value="Add" /> 
                                <input class="submit-gray" type="reset"  name="btnTodoClear" value="Clear" />
                            </fieldset>            
                        <?php } ?>
                    </form>  

                </div>
            </div>
        </div>
    </div>

    <script>
        function validateAddToDoForm() {
            var successMsgDiv = document.getElementById("toDoMsg");
            if (document.getElementById("inputFieldToDo").value === "") {
                successMsgDiv.style.display = "block";
                successMsgDiv.className = "notification n-error";
                successMsgDiv.innerHTML = "Please select a Reminder Closing Date.";
                document.getElementById("inputFieldToDo").focus();
                return false;
            }
        }
    </script>
    <?php
}
?>