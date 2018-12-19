<?php
session_start();
include_once '../db.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>CRM :: Edit Reminder</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- List of style sheets to be included-->
        <!-- CSS Reset -->
        <link rel="stylesheet" type="text/css" href="../../../reset.css" media="screen" />

        <!-- Fluid 960 Grid System - CSS framework -->
        <link rel="stylesheet" type="text/css" href="../../../grid.css" media="screen" />        

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
        <style type="text/css">
            .companySuggestTr td:hover {
                background: #FCF;  
                color: #000;   
                font-weight: bold;
                cursor: pointer;
            } 
        </style>
    </head>
    <body>        
        <div class="grid_6" style="margin-top: 10px;width: 95%" >
            <div class="module">
                <h2><span>Edit Reminder</span></h2>
                <div class="module-body">
                    <?php
                    // if the user click on the update button
                    if (isset($_POST['btnUpdateTodo']) && !empty($_POST['btnUpdateTodo'])) {
                        $todo_id = $_POST['hidTodoId'];
                        $todo_end_date = mysqli_real_escape_string($con, $_POST['txtCloseDay']);
                        $formattted_end_time = date("Y-m-d", strtotime($todo_end_date));
                        $todo_type = mysqli_real_escape_string($con, $_POST['todoType']);
                        $todo_description = mysqli_real_escape_string($con, $_POST['txtAreaDesc']);

                        $todo_cont_id = "";
                        if ($_POST['chkCorporate'] == "on") {
                            $todo_cont_id = $_POST['cmpContactId'];
                            if ($todo_cont_id == "NA" || $todo_cont_id == "") {
                                $todo_cont_id = $_POST['hidTodoContId'];
                            }
                        } else {
                            $todo_cont_id = "";
                        }

                        $sql = "UPDATE `tbl_todo` SET `todo_endDate` = '$formattted_end_time', "
                                . "`todo_type` = '$todo_type', `todo_detail` = '$todo_description', "
                                . "`cont_id` = '$todo_cont_id' WHERE `todo_id` = $todo_id;";
                        $res = mysqli_query($con, $sql);
                        if (mysqli_affected_rows($con) > 0) {
                            ?>
                            <div>
                                <span class='notification n-success'>Reminder updated successfully.</span>                                
                            </div>
                            <script type="text/javascript">
                                $(function() {
                                    // to access all the elements of the parent using window.paren property
                                    var parentWindow = window.parent;
                                    // if the "Reminder Edit" section has been opened from the "Calendar" section then refresh the caldenar
                                    if (parentWindow.document.getElementById("btnDateChange")) {
                                        //call the changeCalendar() function with the default parameters to refresh the calendar to reflect the updated value
                                        parentWindow.changeCalendar('goButton', 0, 0);                                        
                                    } 
                                    // if the Reminder Edit section has been opened from the "Reminder List --> View" section, then refresh the reminder table
                                    else if (parentWindow.document.getElementById("rdoOther")) {
                                        // call the todoRefreshList() function with the default paremeters to refresh the table of Reminder List --> View section to reflect the updated value
                                        parentWindow.todoRefreshList('ViewList', 'no');                                        
                                    } 
                                    // if the Reminder Edit section has been opened from the "Reports --> Schedule Items" section, then refresh the Schedule Itmes Report table
                                    else if (parentWindow.document.getElementById("hidCompId")) {
                                        // get the company id from hidden element of the "Schedule Item" section
                                        var compId = parentWindow.document.getElementById("hidCompId").value;                                    
                                        // call the fetchToDoDetailsByCompany() function to refresh the generated schedule details report of Report --> Schedule Items section
                                        parentWindow.fetchToDoDetailsByCompany(compId);
                                    }
                                    // get the value of the right side reminder sort value
                                    var sortBy = parentWindow.document.getElementById("rightPanelTodoSort").value;
                                    // displayOnlyByType() function will refresh the right side reminder list section after the reminder updated successfully
                                    parentWindow.displayOnlyByType(sortBy);
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
                    // if the user click on the edit button of the corresponding reminder edit
                    else if (isset($_GET['todoId']) && !empty($_GET['todoId'])) {
                        $todo_id = $_GET['todoId'];
                        $todo_cont_name = $_GET['contName'];
                        $todo_comp_name = $_GET['compName'];
                        //query which fetch the data of that particular activity id
                        $sql = "SELECT * FROM `tbl_todo` WHERE `todo_id` = $todo_id;";
                        $res = mysqli_query($con, $sql);
                        $row = mysqli_fetch_array($res);

                        //collect all the value form the database which will be updatable
                        $todo_date = $row['todo_date'];
                        $formattted_todo_date = date("d-m-Y", strtotime($todo_date));
                        $todo_end_date = $row['todo_endDate'];
                        $formatted_todo_end_date = date("d-m-Y", strtotime($todo_end_date));
                        $todo_type = $row['todo_type'];
                        $todo_description = $row['todo_detail'];
                        $todo_cont = $row['cont_id'];
                        ?>                                                                                                     
                        <div id="notificationMsg"></div>                                                       
                        <form name='frmReminderEdit' id='frmReminderEdit' method='post' id='frmActivityEdit' onsubmit="return validateEditTodoForm();">
                            <input type='hidden' name='hidTodoId' id='todoId' value='<?php echo $todo_id; ?>' />  
                            <input type="hidden" name="hidTodoContId" value="<?php echo $todo_cont; ?>" />
                            <p>
                                <label>Reminder Published Date</label>
                                <input type='text' name='txtToDay' class='input-short' value="<?php echo $formattted_todo_date; ?>" style='width: 50%' readonly />
                            </p>
                            <p>
                                <label>Expected Closing Date</label>
                                <input type='text' name='txtCloseDay' size='12' id='inputField' value="<?php echo $formatted_todo_end_date; ?>" class='input-short' placeholder='Click here to select' style='width: 50%'/>
                            </p>   
                            <p>
                                <label>Reminder Type</label>
                                <select class="input-short" style='width: 50%' name="todoType">
                                    <option <?php
                                    if ($todo_type == "Telephone / Conversation") {
                                        echo "selected='selected'";
                                    }
                                    ?>>Telephone / Conversation</option>
                                    <option <?php
                                    if ($todo_type == "Email") {
                                        echo "selected='selected'";
                                    }
                                    ?>>Email</option>
                                    <option <?php
                                    if ($todo_type == "Meeting") {
                                        echo "selected='selected'";
                                    }
                                    ?>>Meeting</option>
                                </select>
                            </p>
                            <p>
                                <label>Description</label>
                                <textarea rows='7' cols='90' name='txtAreaDesc' class='input-short' placeholder='Write your description here' style='resize: none; width: 70%;'><?php echo $todo_description; ?></textarea>
                            </p>     
                            <?php
                            if ($todo_cont != 0) {
                                ?>
                                <p>                                      
                                    <input type="checkbox" name="chkCorporate" id="chkCorporate" onchange="displayContactList();" checked /> This item corresponds to a Corporate Contact                
                                </p>
                                <div id="corporateDeatils">
                                    <div id="divCompanyList">
                                        <p id="txtCmpPara">
                                            <label id="comanyLabel">Company: <b><?php echo $todo_comp_name; ?></b></label>
                                            <input type="text" name="txtCompSearchBox" class="input-short" autocomplete="off" placeholder="Search company here" onkeyup="autopopulate(this, 'Reminders')" style="width: 70%;">
                                            <span id="companySearchAjax"></span>
                                            <br>
                                            <span id="companySuggested" style="z-index: 1; position: absolute; width: 50%;"></span>
                                        </p>
                                    </div>
                                    <p id="chekCompLstPara">
                                        <input type="checkbox" name="chkCmbCompanyLst" id="chkCmbCompanyLst" onchange="displayCompanyComboList('new')">
                                        <span> Display Company List</span>
                                    </p>
                                    <p id="cntPara">
                                        <label id="contactLabel">Contact: <b><?php echo $todo_cont_name; ?></b></label>
                                        <select class="input-short" disabled="true" name="cmpContactId" id="contactLst" style="width: 80%;">
                                            <option value="NA">Enter a Company First</option>
                                        </select>
                                    </p>
                                </div>   
                            <?php } else { ?>
                                <p>                                      
                                    <input type="checkbox" name="chkCorporate" id="chkCorporate" onchange="displayContactList();" /> This item corresponds to a Corporate Contact                
                                </p>
                                <div id="corporateDeatils">                            
                                </div>   
                            <?php } ?>
                            <div class="grid_6">
                                <fieldset style="padding-left: 200px;">
                                    <input class="submit-green" type="submit" name="btnUpdateTodo" value="Update" style="margin-left:30px;" />
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
            function _(e) {
                return document.getElementById(e);
            }
            function validateEditTodoForm() {
                var msgDiv = document.getElementById("notificationMsg");
                var msgSpan = document.createElement("span");
                msgSpan.setAttribute("class", "notification n-error");
                if (document.getElementById("notificationMsg").innerHTML !== "") {
                    msgDiv.innerHTML = "";
                }

                var form = document.getElementById("frmReminderEdit");
                if (form.txtCloseDay.value === "" || form.txtCloseDay.value === "01-01-1970") {
                    msgSpan.innerHTML = "Please select a reminder closing date.";
                    msgDiv.appendChild(msgSpan);
                    form.txtCloseDay.focus();
                    return false;
                }
                if (form.txtAreaDesc.value === "") {
                    msgSpan.innerHTML = "Reminder descripton cannot be left empty";
                    msgDiv.appendChild(msgSpan);
                    form.txtAreaDesc.focus();
                    return false;
                }
                else {
                    return true;
                }
            }

            function displayContactList() {
                var frmToDo = _("corporateDeatils");
                if (_('chkCorporate').checked) {
                    //company list div element
                    var eleDivCompanyLst = document.createElement("div");
                    eleDivCompanyLst.id = "divCompanyList";
                    frmToDo.appendChild(eleDivCompanyLst);

                    //paragraph for containing company list checkbox 
                    var eleCompChekPara = document.createElement('p');
                    eleCompChekPara.id = "chekCompLstPara";
                    var chkCompany = document.createElement("input");
                    chkCompany.setAttribute("type", "checkbox");
                    chkCompany.name = "chkCmbCompanyLst";
                    chkCompany.id = "chkCmbCompanyLst";
                    chkCompany.setAttribute("onchange", "displayCompanyComboList('new')");
                    eleCompChekPara.appendChild(chkCompany);
                    var eleCompChkText = document.createElement("span");
                    eleCompChkText.innerHTML = " Display Company List";
                    eleCompChekPara.appendChild(eleCompChkText);
                    frmToDo.appendChild(eleCompChekPara);

                    //company search text box
                    var txtCompPara = document.createElement('p');
                    txtCompPara.id = "txtCmpPara";
                    var eleCompLabel = document.createElement('label');
                    eleCompLabel.id = "comanyLabel";
                    eleCompLabel.innerHTML = "Company";
                    txtCompPara.appendChild(eleCompLabel);
                    var txtComp = document.createElement('input');
                    txtComp.setAttribute("type", "text");
                    txtComp.name = "txtCompSearchBox";
                    txtComp.setAttribute("class", "input-short");
                    txtComp.setAttribute("autocomplete", "off");
                    txtComp.style.width = "70%";
                    txtComp.setAttribute("placeholder", "Search company here");
                    txtComp.setAttribute("onkeyup", "autopopulate(this,'Reminders')");
                    txtCompPara.appendChild(txtComp);

                    //span to display company search ajax loader
                    var spanCompSearch = document.createElement('span');
                    spanCompSearch.id = "companySearchAjax";
                    txtCompPara.appendChild(spanCompSearch);

                    //company search suggestion table
                    var spanSuggestion = document.createElement("span");
                    spanSuggestion.id = "companySuggested";
                    spanSuggestion.style.zIndex = "1";
                    spanSuggestion.style.position = "absolute";
                    spanSuggestion.style.width = "50%";
                    var eleBr = document.createElement("br");
                    txtCompPara.appendChild(eleBr);
                    txtCompPara.appendChild(spanSuggestion);
                    eleDivCompanyLst.appendChild(txtCompPara);

                    //contact combobox
                    var eleContSelect = document.createElement('select');
                    eleContSelect.setAttribute("class", "input-short");
                    eleContSelect.setAttribute("disabled", "true");
                    eleContSelect.name = "cmpContactId";
                    eleContSelect.id = "contactLst";
                    eleContSelect.style.width = "80%";

                    //option node for NA value
                    var eleContOpt = document.createElement('option');
                    eleContOpt.text = "Enter a Company First";
                    eleContOpt.value = "NA";
                    eleContSelect.options.add(eleContOpt);

                    //paragraph which contain Contact List
                    var eleContPara = document.createElement('p');
                    eleContPara.id = "cntPara";
                    var eleContLabel = document.createElement('label');
                    eleContLabel.id = "contactLabel";
                    eleContLabel.innerHTML = "Contact";
                    eleContPara.appendChild(eleContLabel);
                    eleContPara.appendChild(eleContSelect);
                    frmToDo.appendChild(eleContPara);
                }
                else {
                    var divCmpLst = _("divCompanyList");
                    var cntPara = _("cntPara");
                    var chkCompLst = _("chekCompLstPara");
                    frmToDo.removeChild(divCmpLst);
                    frmToDo.removeChild(cntPara);
                    frmToDo.removeChild(chkCompLst);
                }
            }

            function autopopulate(word, onChangeFunction) {
                var suggstDiv = _('companySuggested');
                if (word.value) {
                    var keyword = word.value;
                    var newString = keyword.split("&");
                    keyword = newString.join("zxtwuqmtz");
                    var url = 'ajax_retriveCompanySuggest.php?callFunc=' + onChangeFunction + '&keyword=' + keyword;
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
                            suggstDiv.innerHTML = xmlhttp.responseText;
                            _("companySearchAjax").innerHTML = "";
                        }
                        else {
                            _("companySearchAjax").innerHTML = "<img src='../../../ajax-search-loader.gif' width='16' height='16' />";
                        }
                    };
                    xmlhttp.open('GET', url, true);
                    xmlhttp.send();
                }
                else {
                    /* Clearing the DIV with the response received */
                    suggstDiv.innerHTML = "";
                    if (_("contactLst")) {
                        selectContact("NA");
                    }
                }
            }

            function setText(searchValue, searchId, onChangeFunction) {
                if (searchValue === "no") {
                    _('companySuggested').innerHTML = "";
                    document.getElementsByName('txtCompSearchBox')[0].value = "";
                }
                else {
                    document.getElementsByName('txtCompSearchBox')[0].value = searchValue;
                    _('companySuggested').innerHTML = "";
                }

                var divCompanyList = _("divCompanyList");
                var hidCompLst = _("companyLst");
                if (hidCompLst) {
                    hidCompLst.parentElement.removeChild(hidCompLst);
                }
                var eleHidCompId = document.createElement("input");
                eleHidCompId.setAttribute("type", "hidden");
                eleHidCompId.id = "companyLst";
                eleHidCompId.name = "txtCompSearchBox";
                eleHidCompId.value = searchId;
                divCompanyList.appendChild(eleHidCompId);

                selectContact(searchId);
            }

            function selectContact(cid) {
                var x = _("contactLst");
                if (cid === "NA") {
                    x.disabled = true;
                    x.innerHTML = '<option value="NA">Select a Company First</option>';
                }
                else {
                    x.disabled = false;
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
                            x.innerHTML = xmlhttp.responseText;
                        }
                    };
                    xmlhttp.open('GET', 'ajax_contactComboList.php?companyId=' + cid, true);
                    xmlhttp.send();
                }
            }

            function displayCompanyComboList(tabName) {
                var companyListDiv = _("divCompanyList");
                companyListDiv.innerHTML = "";
                var cmbContLst = _("contactLst");
                var eleCompPara = document.createElement('p');

                if (_("chkCmbCompanyLst").checked === true) {
                    //company combobox        
                    var eleCompSelect = document.createElement('select');
                    eleCompSelect.setAttribute("class", "input-short");
                    eleCompSelect.name = "companyId";
                    eleCompSelect.id = "companyLst";
                    if (tabName === "new")
                        eleCompSelect.style.width = "70%";
                    else
                        eleCompSelect.style.width = "100%";
                    eleCompSelect.setAttribute("onchange", "selectContact(this.value)");

                    //paragraph which contain Company List        
                    eleCompPara.id = "cmpPara";
                    var eleCompLabel = document.createElement('label');
                    eleCompLabel.id = "comanyLabel";
                    eleCompLabel.innerHTML = "Company";
                    eleCompPara.appendChild(eleCompLabel);
                    eleCompPara.appendChild(eleCompSelect);
                    var eleAjaxLoader = document.createElement("span");
                    eleCompPara.appendChild(eleAjaxLoader);

                    var url = 'ajax_companyComboList.php?compLst=' + 'yes';
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
                            eleCompSelect.innerHTML = xmlhttp.responseText;
                            eleAjaxLoader.innerHTML = "";
                        }
                        else {
                            eleAjaxLoader.innerHTML = " <img src='../../../ajax-loader.gif' height=15 width=15 /> Loading company list. Please wait...";
                        }
                    };
                    xmlhttp.open('GET', url, true);
                    xmlhttp.send();

                    cmbContLst.innerHTML = "<option value='NA'>Select a Company First</option>";
                    cmbContLst.disabled = true;
                }
                else {
                    //company search text box        
                    eleCompPara.id = "txtCmpPara";
                    var eleCompLabel = document.createElement('label');
                    eleCompLabel.id = "comanyLabel";
                    eleCompLabel.innerHTML = "Company";
                    eleCompPara.appendChild(eleCompLabel);
                    var txtComp = document.createElement('input');
                    txtComp.setAttribute("type", "text");
                    txtComp.name = "txtCompSearchBox";
                    txtComp.setAttribute("class", "input-short");
                    txtComp.style.width = "70%";
                    txtComp.setAttribute("placeholder", "Seach Company here");
                    txtComp.setAttribute("onkeyup", "autopopulate(this,'Reminders')");
                    eleCompPara.appendChild(txtComp);

                    //company search suggestion table
                    var spanSuggestion = document.createElement("div");
                    spanSuggestion.id = "companySuggested";
                    spanSuggestion.style.zIndex = "1";
                    spanSuggestion.style.position = "absolute";
                    spanSuggestion.style.width = "50%";
                    eleCompPara.appendChild(spanSuggestion);

                    cmbContLst.innerHTML = "<option value='NA'>Enter a Company First</option>";
                    cmbContLst.disabled = true;
                }
                companyListDiv.appendChild(eleCompPara);
            }
        </script>
    </body>
</html>