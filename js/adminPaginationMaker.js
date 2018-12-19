function _(e1){
    return document.getElementById(e1);
}

function adminPaginationMaker(page, adminFunctionality) {
    var adminType = "";
    if (adminFunctionality === "company")
        adminType = "Company";
    else if (adminFunctionality === "company type")
        adminType = "Company Type";
    else if (adminFunctionality === "office type")
        adminType = "Office";
    else if (adminFunctionality === "company status")
        adminType = "Company Status";
    else if (adminFunctionality === "contact relationship")
        adminType = "Additional Relationship";
    else if (adminFunctionality === "department")
        adminType = "Department";
    else if (adminFunctionality === "salutation")
        adminType = "Salutation";
    else if (adminFunctionality === "college list")
        adminType = "College List";
    else if (adminFunctionality === "stream")
        adminType = "Stream";
    else if (adminFunctionality === "district")
        adminType = "Address";
    else if (adminFunctionality === "manage user")
        adminType = "Users";
    else if (adminFunctionality === "travel destination")
        adminType = "Travel";
    var url = 'includes/php/ajax/ajax_admin_pagination.php?page_num=' + page + '&adminType=' + adminFunctionality;
    var viewList = _("paginationTable");
    viewList.innerHTML = "";
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
            viewList.innerHTML = xmlhttp.responseText;
        }
        else {
            _("paginationTable").innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading " + adminType + " List. Please Wait...</span></p></div>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}

function adminTodoPaginationMaker(page) {
    var url = 'includes/php/ajax/ajax_admin_todo_pagination.php?page_num=' + page;
    var viewList = _("paginationTable");
    viewList.innerHTML = "";
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
            viewList.innerHTML = xmlhttp.responseText;
        }
        else {
            _("paginationTable").innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading Reminder List. Please Wait...</span></p></div>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}

function rowEdit(row, category) {
    //alert(category);
    if (category === "reminder list") {
        var frmTodo = document.getElementsByName("frmUpdateTodo")[0];
        frmTodo.innerHTML = "";
        frmTodo.innerHTML = "\
<p>\n\
    <label>Reminder Published Date</label>\n\
    <input type='text' name='txtToDay' class='input-short' style='width: 50%' readonly />\n\
</p>\n\
<p>\n\
    <label>Expected Closing Date</label>\n\
    <input type='text' name='txtCloseDay' size='12' id='inputField' class='input-short' placeholder='Click here to select' style='width: 50%' onfocus='showDatePicker(\"inputField\")' />\n\
</p>\n\
<p>\n\
    <label>Description</label>\n\
    <textarea rows='7' cols='90' name='txtAreaDesc' class='input-short' placeholder='Write your description here' style='resize: none; width: 70%;'></textarea>\n\
</p>\n\
<p>\n\
    <label>Current Status</label>\n\
    <select class='input-short' style='width: 50%' name='cmbStatus'>\n\
        <option value='Open'>Open</option>\n\
        <option value='Closed'>Closed</option>\n\
        <option value='Cancelled'>Cancelled</option>\n\
    </select>\n\
</p>\n\
<p>\n\
    <input type='checkbox' name='chkCorporate' id='chkCorporate' onchange='displayContactList()' /> This item corresponds to a Corporate Contact\n\
</p>\n\
<input type='hidden' id='hidContId' />\n\
<div id='corporateDeatils'></div>\n\
<fieldset>\n\
    <input class='submit-green' type='button' onclick='updateToDoForm()' name='btnTodoUpdate' id='btnTodoUpdate' value='Update' />\n\
</fieldset>";
        if (document.getElementsByName("todoId")[0]) {
            _("hiddenSection").innerHTML = "";
        }
        var hiddenElement = document.createElement("input");
        hiddenElement.setAttribute("type", "hidden");
        hiddenElement.setAttribute("name", "todoId");
        hiddenElement.id = "todoId";
        hiddenElement.setAttribute("value", row['todo_id']);
        _("hiddenSection").appendChild(hiddenElement);

        document.frmUpdateTodo.txtToDay.value = row['start_date'];
        document.frmUpdateTodo.txtCloseDay.value = row['end_date'];
        document.frmUpdateTodo.txtAreaDesc.value = row['todo_detail'];
        document.frmUpdateTodo.cmbStatus.value = row['todo_status'];
        _("hidContId").value = row['cont_id'];
        if (row['cont_id'] != 0) {
            _('chkCorporate').checked = true;
            displayContactList();
            _("comanyLabel").innerHTML = "";
            _("comanyLabel").innerHTML = "Company: " + "<strong>" + row['comp_name'] + "</strong>";
            _("contactLabel").innerHTML = "";
            _("contactLabel").innerHTML = "Contact: " + "<strong>" + row['cont_name'] + "</strong>";
        }
    }
    if (category === "company list") {
        document.frmCompany.txtCmpName.value = row['comp_name'];
        document.frmCompany.cmbCmpType.value = row['comp_type'];
        document.frmCompany.txtWebSite.value = row['comp_website'];
        document.frmCompany.btnAdminCmpSubmit.value = "Update";
        document.frmCompany.btnAdminCmpSubmit.removeAttribute("onclick");
        document.frmCompany.btnAdminCmpSubmit.setAttribute("onclick", "updateCompany()");
        if (document.getElementsByName("btnAdminCompanyClear")[0]) {
            var clearButton = document.getElementsByName("btnAdminCompanyClear")[0];
            _("frmCompanFieldset").removeChild(clearButton);
        }
        if (document.getElementsByName("companyId")[0]) {
            _("hiddenSection").innerHTML = "";
        }
        var hiddenElement = document.createElement("input");
        hiddenElement.setAttribute("type", "hidden");
        hiddenElement.setAttribute("name", "companyId");
        hiddenElement.setAttribute("value", row['comp_id']);
        _("hiddenSection").appendChild(hiddenElement);
    }
    else if (category === "user list") {        
        //alert("debesh");
        document.frmManageUser.txtEmpName.value = row['empl_name'];
        document.frmManageUser.txtUserId.value = row['empl_userId'];
        document.frmManageUser.txtPassword.value = row['password'];
        document.frmManageUser.cmbUserType.value = row['empl_type'];
        //var dob = row['empl_dob'];
        //var dobDay = dob.substr(8, 2);
        //var dobMonth = dob.substr(5, 2);
        //var dobYear = dob.substr(0, 4);
        //dob = dobDay + "-" + dobMonth + "-" + dobYear;
        //document.frmManageUser.txtDob.value = dob;
        document.frmManageUser.txtDob.value = row['DATE_FORMAT(empl_dob,\"%d-%m-%Y\")']
        document.frmManageUser.txtEmail.value = row['empl_email'];
        document.frmManageUser.txtMobile.value = row['empl_mobile'];
        document.frmManageUser.txtAddress.value = row['empl_address'];
        if (_("empStatus")) {
            _("hidCmbStatus").innerHTML = "";
        }
        _("hidCmbStatus").innerHTML = "<p><label>Select Status</label><select class='input-short' style='width: 50%' name='cmbUserStatus' id='empStatus'><option value='NA'>Select User Status</option><option value='Active'>Active</option><option value='Inactive'>Inactive</option></select></p>";
        document.frmManageUser.cmbUserStatus.value = row['empl_status'];

        document.frmManageUser.btnFrmUserAdd.value = "Update";
        document.frmManageUser.btnFrmUserAdd.removeAttribute("onclick");
        document.frmManageUser.btnFrmUserAdd.setAttribute("onclick", "updateUser()");
        if (document.getElementsByName("btnFrmUserClear")[0]) {
            var clearButton = document.getElementsByName("btnFrmUserClear")[0];
            if (clearButton.parentNode) {
                clearButton.parentNode.removeChild(clearButton);
            }
        }
        if (document.getElementsByName("userId")[0]) {
            _("hiddenSection").innerHTML = "";
        }
        var hiddenElement = document.createElement("input");
        hiddenElement.setAttribute("type", "hidden");
        hiddenElement.setAttribute("name", "userId");
        hiddenElement.setAttribute("value", row['empl_id']);
        _("hiddenSection").appendChild(hiddenElement);
    }
    else if (category === "company type list" || category === "office type list"
            || category === "company status list" || category === "additional relationship list"
            || category === "functional departments list" || category === "salutation list"
            || category === "college list" || category === "stream list" || category === "travel destination list") {
        var activeSection = _("typeStatusSection");
        if (_("statusPara")) {
            activeSection.innerHTML = "";
        }

        //creating a combobox containing the status of the setting
        var activePara = document.createElement("p");
        activePara.setAttribute("id", "statusPara");
        var activeLabel = document.createElement("label");
        activeLabel.innerHTML = "Status";
        activePara.appendChild(activeLabel);
        var activeSelect = document.createElement("select");
        activeSelect.setAttribute("name", "cmbTypeStatus");
        activeSelect.style.width = "65%";
        var activeOption = document.createElement("option");
        activeOption.setAttribute("value", "Active");
        activeOption.innerHTML = "Active";
        activeSelect.appendChild(activeOption);
        var inactiveOption = document.createElement("option");
        inactiveOption.setAttribute("value", "Inactive");
        inactiveOption.innerHTML = "Inactive";
        activeSelect.appendChild(inactiveOption);
        activePara.appendChild(activeSelect);
        activeSection.appendChild(activePara);

        _("txtAdminTypeTask").value = row['sett_value'];
        document.getElementsByName("cmbTypeStatus")[0].value = row['sett_status'];

        var addButton = document.getElementsByName("btnAdminSubmit")[0];
        addButton.value = "Update";
        addButton.removeAttribute("onclick");
        addButton.setAttribute("onclick", "updateAdminTypeTask('" + row['sett_value'] + "')");

        if (document.getElementsByName("btnAdminClear")[0]) {
            var clearButton = document.getElementsByName("btnAdminClear")[0];
            _("frmBtnFieldset").removeChild(clearButton);
        }

        if (document.getElementsByName("typeId")[0]) {
            _("hiddenSection").innerHTML = "";
        }
        var hiddenElement = document.createElement("input");
        hiddenElement.setAttribute("type", "hidden");
        hiddenElement.setAttribute("name", "typeId");
        hiddenElement.setAttribute("value", row['sett_id']);
        _("hiddenSection").appendChild(hiddenElement);

        var hiddenElement1 = document.createElement("input");
        hiddenElement1.setAttribute("type", "hidden");
        hiddenElement1.setAttribute("name", "settType");
        hiddenElement1.setAttribute("value", row['sett_type']);
        _("hiddenSection").appendChild(hiddenElement1);
    }

    else if (category === "district list") {
        document.frmDistrict.txtCity.value = row['addr_city'];
        _("otherState").innerHTML = "";

        //Remember Me - Need to change later when new state added to india
        if (row['addr_country'] !== "India") {
            document.frmDistrict.cmbState.value = "Other";
            var otherStatePara = document.createElement("p");
            var otherState = document.createElement("input");
            otherState.setAttribute("type", "input");
            otherState.setAttribute("name", "txtOtherState");
            otherState.setAttribute("placeholder", "Enter other State Name");
            otherState.setAttribute("value", row['addr_state']);
            otherState.style.width = "55%";
            otherStatePara.appendChild(otherState);
            _("otherState").appendChild(otherStatePara);
        }
        else {
            document.frmDistrict.cmbState.value = row['addr_state'];
        }
        document.frmDistrict.cmbCountry.value = row['addr_country'];
        document.frmDistrict.btnAddDistrict.value = "Update";
        document.frmDistrict.btnAddDistrict.removeAttribute("onclick");
        document.frmDistrict.btnAddDistrict.setAttribute("onclick", "updateDistrict()");
        if (document.getElementsByName("btnAdminClear")[0]) {
            var clearButton = document.getElementsByName("btnAdminClear")[0];
            _("frmBtnFieldset").removeChild(clearButton);
        }
        if (document.getElementsByName("districtId")[0]) {
            _("hiddenSection").innerHTML = "";
        }
        var hiddenElement = document.createElement("input");
        hiddenElement.setAttribute("type", "hidden");
        hiddenElement.setAttribute("name", "districtId");
        hiddenElement.setAttribute("value", row['addr_id']);
        _("hiddenSection").appendChild(hiddenElement);
    }
}

function rowDelete(id, category) {
    var ajaxLoader = _("ajaxLoaderProcessing");
    if (category === "company list") {
        //document.frmCompany.reset();
        document.getElementsByName('txtCmpName')[0].value = "";
        document.getElementsByName('txtWebSite')[0].value = "";
        document.getElementsByName('cmbCmpType')[0].selectedIndex = 0;
        document.getElementsByName('btnAdminCmpSubmit')[0].value = "Add";
        document.frmCompany.btnAdminCmpSubmit.removeAttribute("onclick");
        document.frmCompany.btnAdminCmpSubmit.setAttribute("onclick", "addCompany()");
        _("hiddenSection").innerHTML = "";
        if (document.frmCompany.btnAdminCompanyClear) {
        }else{
            var clearButton = document.createElement("input");
            clearButton.setAttribute("type", "reset");
            clearButton.setAttribute("name", "btnAdminCompanyClear");
            clearButton.setAttribute("value", "Clear");
            clearButton.setAttribute("class", "submit-gray");
            _("frmCompanFieldset").appendChild(clearButton);
        }
        document.getElementsByName('txtCmpName')[0].focus();
        var rspText = "";
        var xmlhttp;

        var url = 'includes/php/ajax/ajax_companyCount.php?id=' + id;

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
                ajaxLoader.innerHTML = "";
                ajaxLoader.style.top = "";
                ajaxLoader.style.left = "";
                ajaxLoader.style.width = "";
                ajaxLoader.style.height = "";
                ajaxLoader.style.position = "";
                ajaxLoader.style.display = "";
                ajaxLoader.style.backgroundColor = "";
                ajaxLoader.style.zIndex = "";
                rspText = xmlhttp.responseText;
                //alert(rspText);
                var xyz = rspText.split(' ');
                rspText = "There are " + rspText + " related to this company?\nAre you sure to delete the company and its related details?";
                if (xyz.length !== 1) {
                    if (window.confirm(rspText) === false) {
                        return;
                    }
                    else
                        deleteCompany(id);
                }
                else {
                    rspText = "Are you sure to delete the company details?";
                    if (window.confirm(rspText) === false) {
                        return;
                    }
                    else
                        deleteCompany(id);
                }
            }
            else {
                ajaxLoader.style.top = window.pageYOffset + "px";
                ajaxLoader.style.left = window.pageXOffset + "px";
                ajaxLoader.style.width = "100%";
                ajaxLoader.style.height = "100%";
                ajaxLoader.style.position = "absolute";
                ajaxLoader.style.display = "table";
                ajaxLoader.style.backgroundColor = "darkgray";
                ajaxLoader.style.zIndex = "1000";
                ajaxLoader.style.opacity = "0.5";
                ajaxLoader.style.filter = "alpha(opacity=50)";
                ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Deleting company details...</span></p>";
            }
        };
        xmlhttp.open('GET', url, true);
        xmlhttp.send();
    }
    else if (category === "company type list" || category === "office type list"
            || category === "company status list" || category === "additional relationship list"
            || category === "functional departments list" || category === "salutation list" || category === "college list" || category === "stream list" || category === "travel destination list") {

        //code to refresh the existing form before deleting the data-----------
        var settingType;
        var type;
        if (category === "company type list") {
            settingType = "Company Type";
            type = "comp_type";
        }
        else if (category === "office type list") {
            settingType = "Office Type";
            type = "offi_type";
        }
        else if (category === "company status list") {
            settingType = "Company Status";
            type = "acti_status";
        }
        else if (category === "additional relationship list") {
            settingType = "Contact Relation";
            type = "add_relation";
        }
        else if (category === "functional departments list") {
            settingType = "Functional Department";
            type = "func_dept";
        }
        else if (category === "salutation list") {
            settingType = "Salutation";
            type = "salute";
        }
        else if (category === "college list") {
            settingType = "College";
            type = "college";
        }
        else if (category === "stream list") {
            settingType = "Stream";
            type = "stream";
        }
        else if (category === "travel destination list"){
            settingType = "Travel";
            type = "travel";
        }
        _("txtAdminTypeTask").value = "";
        _("typeStatusSection").innerHTML = "";
        document.getElementsByName('btnAdminSubmit')[0].value = "Add";
        var addButton = document.getElementsByName("btnAdminSubmit")[0];
        addButton.removeAttribute("onclick");
        addButton.setAttribute("onclick", "saveAdminTaskForm('" + settingType + "')");
        _("hiddenSection").innerHTML = "";
        if (document.getElementsByName("btnAdminClear")[0]) {
        }else{
            var clearButton = document.createElement("input");
            clearButton.setAttribute("type", "reset");
            clearButton.setAttribute("name", "btnAdminClear");
            clearButton.setAttribute("value", "Clear");
            clearButton.setAttribute("class", "submit-gray");
            _("frmBtnFieldset").appendChild(clearButton);
        }
        _("txtAdminTypeTask").focus();
        //----------------------------------------------------------------------

        var cnfMsg = "Are you sure to delete the data?";
        if (window.confirm(cnfMsg) === false) {
            return;
        }
        var msgDiv = _("adminSuccessMsg");
        msgDiv.innerHTML = "";
        var msgSpan = document.createElement("span");
        var rspText = "";

        var xmlhttp;
        var url = 'includes/php/ajax/ajax_deleteAdminTask.php?id=' + id;

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
                ajaxLoader.innerHTML = "";
                ajaxLoader.style.top = "";
                ajaxLoader.style.left = "";
                ajaxLoader.style.width = "";
                ajaxLoader.style.height = "";
                ajaxLoader.style.position = "";
                ajaxLoader.style.display = "";
                ajaxLoader.style.backgroundColor = "";
                ajaxLoader.style.zIndex = "";
                rspText = xmlhttp.responseText;
                //alert(rspText);
                if (rspText === "Success") {
                    msgSpan.setAttribute("class", "notification n-success");
                    msgSpan.innerHTML = "Data deleted successfully.";
                    displayAdminTaskListTable(type);
                }
                else {
                    msgSpan.setAttribute("class", "notification n-error");
                    msgSpan.innerHTML = "Error while deleting the data.";
                    displayAdminTaskListTable(type);
                }
                msgDiv.appendChild(msgSpan);
            }
            else {
                ajaxLoader.style.top = window.pageYOffset + "px";
                ajaxLoader.style.left = window.pageXOffset + "px";
                ajaxLoader.style.width = "100%";
                ajaxLoader.style.height = "100%";
                ajaxLoader.style.position = "absolute";
                ajaxLoader.style.display = "table";
                ajaxLoader.style.backgroundColor = "darkgray";
                ajaxLoader.style.zIndex = "1000";
                ajaxLoader.style.opacity = "0.5";
                ajaxLoader.style.filter = "alpha(opacity=50)";
                ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Deleting " + settingType + " details...</span></p>";
            }
        };
        xmlhttp.open('GET', url, true);
        xmlhttp.send();
    }
    else if (category === "district list") {
        //following codes are to refresh the District Form to its original state-----
        document.getElementsByName('txtCity')[0].value = "";
        document.getElementsByName('cmbState')[0].selectedIndex = "NA";
        document.getElementsByName('cmbCountry')[0].selectedIndex = "NA";
        _('otherState').innerHTML = "";        
        document.frmDistrict.cmbState.disabled = false;
        document.frmDistrict.cmbCountry.disabled = false;
        document.getElementsByName('btnAddDistrict')[0].value = "Add";
        document.frmDistrict.btnAddDistrict.removeAttribute("onclick");
        document.frmDistrict.btnAddDistrict.setAttribute("onclick", "addDistrict()");
        _("hiddenSection").innerHTML = "";
        if (document.getElementsByName("btnAdminClear")[0]) {
        }else{
            var clearButton = document.createElement("input");
            clearButton.setAttribute("type", "reset");
            clearButton.setAttribute("name", "btnAdminClear");
            clearButton.setAttribute("value", "Clear");
            clearButton.setAttribute("class", "submit-gray");
            _("frmBtnFieldset").appendChild(clearButton);
        }
        document.getElementsByName('txtCity')[0].focus();
        //---------------------------------------------------------------------------

        var cnfMsg = "Are you sure to delete the district?";
        if (window.confirm(cnfMsg) === false) {
            return;
        }
        var xmlhttp;
        var rspText;
        var msgDiv = _("adminSuccessMsg");
        msgDiv.innerHTML = "";
        var msgSpan = document.createElement("span");

        var url = 'includes/php/ajax/ajax_deleteDistrict.php?id=' + id;

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
                ajaxLoader.innerHTML = "";
                ajaxLoader.style.top = "";
                ajaxLoader.style.left = "";
                ajaxLoader.style.width = "";
                ajaxLoader.style.height = "";
                ajaxLoader.style.position = "";
                ajaxLoader.style.display = "";
                ajaxLoader.style.backgroundColor = "";
                ajaxLoader.style.zIndex = "";
                rspText = xmlhttp.responseText;
                //alert(rspText);
                if (rspText === "Success") {
                    msgSpan.setAttribute("class", "notification n-success");
                    msgSpan.innerHTML = "District details deleted successfully.";
                    displayDistrictList();
                }
                else {
                    msgSpan.setAttribute("class", "notification n-error");
                    msgSpan.innerHTML = "Error while deleting District.";
                    displayDistrictList();
                }
                msgDiv.appendChild(msgSpan);
            }
            else {
                ajaxLoader.style.top = window.pageYOffset + "px";
                ajaxLoader.style.left = window.pageXOffset + "px";
                ajaxLoader.style.width = "100%";
                ajaxLoader.style.height = "100%";
                ajaxLoader.style.position = "absolute";
                ajaxLoader.style.display = "table";
                ajaxLoader.style.backgroundColor = "darkgray";
                ajaxLoader.style.zIndex = "1000";
                ajaxLoader.style.opacity = "0.5";
                ajaxLoader.style.filter = "alpha(opacity=50)";
                ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Deleting district details...</span></p>";
            }
        };
        xmlhttp.open('GET', url, true);
        xmlhttp.send();
    }
    else if (category === "user list") {
        document.getElementsByName("txtEmpName")[0].value = "";
        document.getElementsByName("txtUserId")[0].value = "";
        document.getElementsByName("txtPassword")[0].value = "";
        document.getElementsByName("cmbUserType")[0].selectedIndex = "NA";
        document.getElementsByName("txtDob")[0].value = "";
        document.getElementsByName("txtEmail")[0].value = "";
        document.getElementsByName("txtMobile")[0].value = "";
        document.getElementsByName("txtAddress")[0].value = "";
        _("hiddenSection").innerHTML = "";
        document.getElementsByName('btnFrmUserAdd')[0].value = "Add";
        document.frmManageUser.btnFrmUserAdd.removeAttribute("onclick");
        document.frmManageUser.btnFrmUserAdd.setAttribute("onclick", "addUser()");
        _("hiddenSection").innerHTML = "";
        _("hidCmbStatus").innerHTML = "";
        if (document.frmManageUser.btnFrmUserClear) {
        }else{
            var clearButton = document.createElement("input");
            clearButton.setAttribute("type", "reset");
            clearButton.setAttribute("name", "btnFrmUserClear");
            clearButton.setAttribute("value", "Clear");
            clearButton.setAttribute("onclick", "javascript: document.frmManageUser.txtEmpName.focus()");
            clearButton.setAttribute("class", "submit-gray");
            _("fldUserBtnContainer").appendChild(clearButton);
        }
        document.getElementsByName("txtEmpName")[0].focus();

        if (confirm("Are you sure to delete the user?") === false) {
            return;
        }

        var msgDiv = _("adminSuccessMsg");
        msgDiv.innerHTML = "";
        var msgSpan = document.createElement("span");

        var rspText = "";
        var url = 'includes/php/ajax/ajax_adminDeleteUser.php?userId=' + id;
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
                ajaxLoader.innerHTML = "";
                ajaxLoader.style.top = "";
                ajaxLoader.style.left = "";
                ajaxLoader.style.width = "";
                ajaxLoader.style.height = "";
                ajaxLoader.style.position = "";
                ajaxLoader.style.display = "";
                ajaxLoader.style.backgroundColor = "";
                ajaxLoader.style.zIndex = "";
                rspText = xmlhttp.responseText;
                //console.log(rspText);
                if (rspText === "Success") {
                    msgSpan.setAttribute("class", "notification n-success");
                    msgSpan.innerHTML = "User's details was deleted successfully.";
                    displayUserList();
                }
                else {
                    msgSpan.setAttribute("class", "notification n-error");
                    msgSpan.innerHTML = "Error while deleting User's details.";
                    displayUserList();
                }
                msgDiv.appendChild(msgSpan);
            }
            else {
                ajaxLoader.style.top = window.pageYOffset + "px";
                ajaxLoader.style.left = window.pageXOffset + "px";
                ajaxLoader.style.width = "100%";
                ajaxLoader.style.height = "100%";
                ajaxLoader.style.position = "absolute";
                ajaxLoader.style.display = "table";
                ajaxLoader.style.backgroundColor = "darkgray";
                ajaxLoader.style.zIndex = "1000";
                ajaxLoader.style.opacity = "0.5";
                ajaxLoader.style.filter = "alpha(opacity=50)";
                ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Deleting User's details...</span></p>";
            }
        };
        xmlhttp.open('GET', url, true);
        xmlhttp.send();
    }
}

function adminTodoCancel(todoId) {
    var cnfMsg = "Are you sure to CANCEL this REMINDER?";
    if (window.confirm(cnfMsg) === false) {
        return;
    }
    var frmTodo = document.getElementsByName("frmUpdateTodo")[0];
    frmTodo.innerHTML = "";

    //code to create commment textarea
    var todoPara = document.createElement("p");
    var todoLabel = document.createElement("label");
    todoLabel.innerHTML = "Comment";
    var todoComment = document.createElement("textarea");
    todoComment.rows = "7";
    todoComment.cols = "90";
    todoComment.name = "txtAreaComment";
    todoComment.setAttribute("class", "input-short");
    todoComment.setAttribute("placeholder", "Write the reason for cancelling Reminder here.");
    todoComment.style.resize = "none";
    todoComment.style.width = "70%";
    todoPara.appendChild(todoLabel);
    todoPara.appendChild(todoComment);
    frmTodo.appendChild(todoPara);

    //code to create Cancel Button
    var todoFieldset = document.createElement("fieldset");
    var todoCancelBtn = document.createElement("input");
    todoCancelBtn.setAttribute("class", "submit-green");
    todoCancelBtn.setAttribute("type", "button");
    todoCancelBtn.setAttribute("onclick", "cancelAdminTodo(" + todoId + ")");
    todoCancelBtn.setAttribute("name", "btnCancelAdmin");
    todoCancelBtn.setAttribute("value", "Cancel Todo Item");
    todoFieldset.appendChild(todoCancelBtn);
    frmTodo.appendChild(todoFieldset);
}                        