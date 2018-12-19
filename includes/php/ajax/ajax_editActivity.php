<?php
session_start();
include_once '../db.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>CRM :: Edit Activity</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- List of style sheets to be included-->
        <!-- CSS Reset -->
        <link rel="stylesheet" type="text/css" href="../../../reset.css" media="screen" />

        <!-- Fluid 960 Grid System - CSS framework -->
        <link rel="stylesheet" type="text/css" href="../../../grid.css" media="screen" />
        <script type="text/javascript" src="../../../js/contactAction.js"></script>

        <!-- IE Hacks for the Fluid 960 Grid System -->
        <!--[if IE 6]><link rel="stylesheet" type="text/css" href="../../ie6.css"  media="screen" /><![endif]-->
        <!--[if IE 7]><link rel="stylesheet" type="text/css" href="../../ie.css" media="screen" /><![endif]-->

        <!-- Main stylesheet -->
        <link rel="stylesheet" type="text/css" href="../../../styles.css"  media="screen" />
        <!-- JQuery Modal Scripts -->        
        <script type="text/javascript" src="../../../js/js/jquery.js"></script>
        <script type="text/javascript" src="../../../js/js/jquery-ui.min.js"></script>        
        <link type="text/css" rel="Stylesheet" href="../../../css/css/jquery-ui.min.css" />

        <script type="text/javascript">
            $(function() {
                $("#inputField").datepicker({
                    dateFormat: "dd-mm-yy",
                    //buttonImage: "/images/date.png",
                    //buttonImageOnly: true,                    
                    showAnim: "slide",
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "2013:2033",
                    showOtherMonths: true,
                    selectOtherMonths: true,
                    //numberOfMonths: 3,
                    //maxDate: "2020-mm-dd"                    
                });
            });
        </script>
    </head>
    <body>        
        <div class="grid_6" style="margin-top: 10px;width: 95%" >
            <div class="module">
                <h2><span>Edit Activity</span></h2>
                <div class="module-body">
                    <?php
                    // if the user click on the update button 
                    if (isset($_POST['btnUpdateActivity']) && !empty($_POST['btnUpdateActivity'])) {
                        $activity_id = mysqli_real_escape_string($con, $_POST['activityId']);
                        $activity_date = mysqli_real_escape_string($con, $_POST['actDate']);
                        $formattted_time = date("Y-m-d", strtotime($activity_date));
                        $activity_type = mysqli_real_escape_string($con, $_POST['actType']);
                        $activity_details = mysqli_real_escape_string($con, $_POST['actDetail']);
                        $contStatusNew = mysqli_real_escape_string($con, $_POST['cont_status']);
                        if (strpos($contStatusNew, "Negetive")) {
                            $statusCommentSave = mysqli_real_escape_string($con, $_POST['statusComment']);
                        } else if (strpos($contStatusNew, "Positive")) {
                            $statusCommentSave = $_POST['visitingMonth'] . "," . $_POST['visitingYear'];
                        } else {
                            $statusCommentSave = "";
                        }
                        $sql = "UPDATE `tbl_emp_contact` SET `act_date` = '$formattted_time', "
                                . "`act_type` = '$activity_type', `act_detail` = '$activity_details', "
                                . "`act_newStatus` = '$contStatusNew', `act_statusComment` = '$statusCommentSave' WHERE `empl_cont_id` = $activity_id;";
                        $res = mysqli_query($con, $sql);
                        if (mysqli_affected_rows($con) > 0) {
                            ?>
                            <div>
                                <span class='notification n-success'>Activity updated successfully.</span>
                            </div>
                            <script type="text/javascript">
                                $(function() {
                                    // to access all the elements of the parent using window.paren property
                                    var parentWindow = window.parent;
                                    // if the "Activity Edit" section is opened from the "Calendar" section then refresh the caldenar
                                    if (parentWindow.document.getElementById("btnDateChange")) {
                                        //call the changeCalendar() function with the default parameters to refresh the calendar to reflect the updated value                                        
                                        parentWindow.changeCalendar('goButton', 0, 0);
                                    }
                                    // if the Activity Edit section has been opened from the "Reports --> Activity by Company" section, then refresh the Activity Report table
                                    else if (parentWindow.document.getElementById("hidCompId")) {
                                        // get the company id from hidden element of the "Activity by Company" section
                                        var compId = parentWindow.document.getElementById("hidCompId").value;
                                        // call the fetchActivityDetailsByCompany() function to refresh the generated activity details report of "Report --> Activity by Company" section
                                        parentWindow.fetchActivityDetailsByCompany(compId);
                                    } else if ((parentWindow.document.getElementById('contactContainer')) && (parentWindow.document.getElementById('companySearchAjax'))) {
                                        parentWindow.fetchActivityDetail(parentWindow.document.getElementsByName('contactList'), 'cont_id');
                                    }else if((parentWindow.document.getElementById('contactContainer'))){
                                        parentWindow.fetchActivityDetail(parentWindow.document.getElementsByName('contactList'), 'empl_id')
                                    }
                                });
                            </script>
                            <?php
                        } else {
                            ?>
                            <div>
                                <span class='notification n-error'>No updates done.</span>
                            </div>
                            <?php
                        }
                    }
                    // if the user click on "Edit" button of the corresponding activity this section will be executed
                    else if (isset($_GET['activityId']) && !empty($_GET['activityId'])) {
                        $act_id = $_GET['activityId'];
                        //query which fetch the data of that particular activity id
                        $sql = "SELECT * FROM `tbl_emp_contact` WHERE `empl_cont_id` = $act_id;";
                        $res = mysqli_query($con, $sql);
                        $row = mysqli_fetch_array($res);

                        //collect all the value form the database which will be updatable
                        $act_date = $row['act_date'];
                        $formatted_act_date = date("d-m-Y", strtotime($act_date));
                        $act_type = $row['act_type'];
                        $act_details = $row['act_detail'];
                        $act_new_status = $row['act_newStatus'];
                        $act_comment = $row['act_statusComment'];
                        ?>                                                                                                     
                        <div id="notificationMsg"></div>                                                       
                        <form name='frmActivityEdit' method='post' id='frmActivityEdit' onsubmit="return validateEditActivityFrom();">
                            <input type='hidden' name='activityId' id='activityId' value='<?php echo $act_id; ?>' />                                                        
                            <p>
                                <label>Activity Date</label>
                                <input type="text" name='actDate' size="12" id="inputField" 
                                       class='input-short' value="<?php echo $formatted_act_date; ?>" placeholder="Click here to select" style='width: 50%' />
                            </p>   
                            <p>
                                <label>Activity Type</label>
                                <select class="input-short" style='width: 50%' name="actType">
                                    <option <?php
                                    if ($act_type == "Telephone / Conversation") {
                                        echo "selected='selected'";
                                    }
                                    ?>>Telephone / Conversation</option>
                                    <option <?php
                                    if ($act_type == "Email") {
                                        echo "selected='selected'";
                                    }
                                    ?>>Email</option>
                                    <option <?php
                                    if ($act_type == "Meeting") {
                                        echo "selected='selected'";
                                    }
                                    ?>>Meeting</option>
                                </select>
                            </p>
                            <p>
                                <label>Description</label>
                                <textarea rows="7" cols="90" name='actDetail' class="input-short" placeholder="Write your description here" style="resize: none; width: 70%;"><?php echo $act_details; ?></textarea>
                            </p>                             
                            <!-- This section will display the existing status of the contact -->
                            <p>
                                <label>Status of the Contact</label>
                                <select class="input-long" name="cont_status" onchange="commentOnStatusChange(this.value)"> 
    <!--<select class="input-long" name="cont_status" disabled>-->
                                    <?php
                                    $sqlStream = "select sett_value from tbl_setting where sett_type='acti_status'";
                                    $resStream = mysqli_query($con, $sqlStream);

                                    $status = "<option value='NA'>Select</option>";
                                    while ($row = mysqli_fetch_array($resStream)) {
                                        if ($row[0] == $act_new_status) {
                                            $status.="<option selected>" . $row[0] . "</option>";
                                        } else {
                                            $status.="<option>" . $row[0] . "</option>";
                                        }
                                    }
                                    echo $status;
                                    ?>
                                </select>                                
                            </p>
                            <?php
                            // if the status of the contact is Negetive, then displayt he 
                            if (strpos($act_new_status, "Negetive")) {
                                ?>                                                              
                                <textarea class="input-long" rows="10" id="statusComment" 
                                          name="statusComment" placeholder="Enter the summarized comments for the selected status"><?php echo $act_comment; ?>'</textarea>                                
                                <p  id="positiveLabel" style="display:none">Enter the probable Month of visit</p>
                                <select name="visitingMonth" id="visitingMonth" style="display:none">
                                    <option value="NA">Select</option>
                                    <?php
                                    for ($monthNum = 1; $monthNum <= 12; $monthNum++) {
                                        //this convert a month number to a month name
                                        $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
                                        echo "<option>$monthName</option>";
                                    }
                                    ?>                                                                        
                                </select>
                                <select name="visitingYear" id="visitingYear" style="display:none">
                                    <option value="NA">Select</option>
                                    <?php
                                    $year_limit = (int) (date("Y") + 5);
//                                    $year = $year_limit+1;
                                    for ($year = 2013; $year < $year_limit; $year++) {
                                        echo "<option>$year</option>";
                                    }
                                    ?>                                    
                                </select>
                                <?php
                            }
                            // if the status of the contact is "positive", then display the positive text area with the month and year in the combobox                            
                            else if (strpos($act_new_status, "Positive")) {
                                $date = explode(",", $act_comment);
                                $comm_month = $date[0];
                                $comm_year = $date[1];
                                ?>                                                                
                                <textarea class="input-long" rows="10" id="statusComment" name="statusComment" placeholder="Enter the summarized comments for the selected status" style="display: none;"></textarea>
                                <p  id="positiveLabel">Enter the probable Month of visit
                                    <select name="visitingMonth" id="visitingMonth">
                                        <option value="NA">Select</option>
                                        <?php
                                        for ($monthNum = 1; $monthNum <= 12; $monthNum++) {
                                            //this convert a month number to a month name
                                            $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
                                            //if current month
                                            if ($monthName == $comm_month) {
                                                echo "<option selected>$monthName</option>";
                                            } else {
                                                echo "<option>$monthName</option>";
                                            }
                                        }
                                        ?>                                                                        
                                    </select>                                
                                    <select name="visitingYear" id="visitingYear">
                                        <option value="NA">Select</option>
                                        <?php
                                        $year_limit = (int) (date("Y") + 5);
//                                    $year = $year_limit+1;
                                        for ($year = 2013; $year < $year_limit; $year++) {
                                            if ($year == $comm_year) {
                                                echo "<option selected>$year</option>";
                                            }
                                            echo "<option>$year</option>";
                                        }
                                        ?>                                   
                                    </select> 
                                </p>
                            <?php } else { ?>
                                <textarea class="input-long" rows="10" id="statusComment" name="statusComment" placeholder="Enter the summarized comments for the selected status" style="display: none;"></textarea>
                                <p  id="positiveLabel" style="display:none">Enter the probable Month of visit</p>
                                <select name="visitingMonth" id="visitingMonth" style="display:none">
                                    <option value="NA">Select</option>
                                    <?php
                                    for ($monthNum = 1; $monthNum <= 12; $monthNum++) {
                                        //this convert a month number to a month name
                                        $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
                                        echo "<option>$monthName</option>";
                                    }
                                    ?>                                                                        
                                </select>
                                <select name="visitingYear" id="visitingYear" style="display:none">
                                    <option value="NA">Select</option>
                                    <?php
                                    $year_limit = (int) (date("Y") + 5);
//                                    $year = $year_limit+1;
                                    for ($year = 2013; $year < $year_limit; $year++) {
                                        echo "<option>$year</option>";
                                    }
                                    ?>                                    
                                </select>
                                <?php
                            }
                            ?>                 
                            <div class="grid_6">
                                <fieldset style="padding-left: 200px;">
                                    <input class="submit-green" type="submit" name="btnUpdateActivity" value="Update" style="margin-left:30px;" />
                                </fieldset> 
                            </div>
                        </form>                      
                        <?php
                    }
                    ?>
                </div> 
            </div>
        </div>
        <script>
            function validateEditActivityFrom() {
                //                       , activityId, actDate, actType, actDetail, cont_status, visitingMonth, visitingYear, statusComment  
                var msgDiv = document.getElementById("notificationMsg");
                var msgSpan = document.createElement("span");
                msgSpan.setAttribute("class", "notification n-error");
                if (document.getElementById("notificationMsg").innerHTML !== "") {
                    msgDiv.innerHTML = "";
                }

                var form = document.getElementById("frmActivityEdit");
                if (form.actDate.value === "" || form.actDate.value === "01-01-1970") {
                    msgSpan.innerHTML = "Please select a activity date.";
                    msgDiv.appendChild(msgSpan);
                    form.actDate.focus();
                    return false;
                }
                if (form.actDetail.value === "") {
                    msgSpan.innerHTML = "Activity descripton cannot be left empty";
                    msgDiv.appendChild(msgSpan);
                    form.actDetail.focus();
                    return false;
                }
                if (form.cont_status.value === "NA") {
                    msgSpan.innerHTML = "Please select a the status of the contact.";
                    msgDiv.appendChild(msgSpan);
                    form.cont_status.focus();
                    return false;
                }
                else {
                    return true;
                }
            }

            function commentOnStatusChange(statusType) {
                if (statusType.indexOf("Positive") > -1) {
                    document.getElementById("statusComment").style.display = "none";
                    document.getElementById("positiveLabel").style.display = "inline";
                    document.getElementById("visitingMonth").style.display = "inline";
                    document.getElementById("visitingYear").style.display = "inline";
                } else if (statusType.indexOf("Negetive") > -1) {
                    document.getElementById("statusComment").style.display = "inline";
                    document.getElementById("visitingMonth").style.display = "none";
                    document.getElementById("visitingYear").style.display = "none";
                    document.getElementById("positiveLabel").style.display = "none";
                } else {
                    document.getElementById("statusComment").style.display = "none";
                    document.getElementById("visitingMonth").style.display = "none";
                    document.getElementById("visitingYear").style.display = "none";
                    document.getElementById("positiveLabel").style.display = "none";
                }
            }
        </script>
    </body>
</html>