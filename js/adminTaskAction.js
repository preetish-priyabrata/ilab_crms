function _(e1) {
    return document.getElementById(e1);
}

function adminChangeContactVisibility(contVisibility) {
    var msgDiv = _("adminSuccessMsg");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");
    var ajaxLoader = _("ajaxLoaderProcessing");
    var xmlhttp;
    var rspText;
    var url = 'includes/php/ajax/ajax_adminUpdateContactVisibility.php?value=' + contVisibility;
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

            if (rspText === 'Success') {
                msgSpan.setAttribute("class", "notification n-success");
                msgSpan.innerHTML = "Contact visibility updated successfully.";
            }
            else {
                msgSpan.setAttribute("class", "notification n-error");
                msgSpan.innerHTML = "Error while updating contact visibility.";
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
            ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Updating contact visibility status...</span></p>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}

var resetSwapContact = function() {
    _("paginationTable").innerHTML = "";
    _("cmbToEmplLst").innerHTML = "<option value='NA'>First select an employee</option>";
    _("cmbToEmplLst").disabled = true;
    _("adminSuccessMsg").innerHTML = "";
};

function deselectAllSwapCheck() {
    if (_("chkCheckSelector").checked === true) {
        _("chkCheckSelector").checked = false;
    }
}

var swapContact = function() {
    var rspText = "";
    var msgDiv = _("adminSuccessMsg");
    var ajaxLoader = _("ajaxLoaderProcessing");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");
    var toEmpId = _("cmbToEmplLst").value;
    var fromEmpId = _("cmbFromEmplLst").value;

    var selectedContact = "";
    var countSelected = 0;
    var frmContactLst = document.getElementsByName("frmContactLst")[0];
    if (fromEmpId === "NA") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select an Employee from the FROM Employee List.";
        msgDiv.appendChild(msgSpan);
        document.frmSwapContact.cmbFromEmplLst.focus();
        return;
    }
    if (toEmpId === "NA") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select an Employee from the TO Employee List.";
        msgDiv.appendChild(msgSpan);
        document.frmSwapContact.cmbFromEmplLst.focus();
        return;
    }
    for (var i = 0, eleLength = frmContactLst.elements.length; i < eleLength; i++)
    {
        if (frmContactLst.elements[i].type === "checkbox") {
            if (frmContactLst.elements[i].checked === true && i !== 0) {
                selectedContact = selectedContact + frmContactLst.elements[i].value + ",";
                countSelected++;
            }
        }
    }
    //alert(selectedContact.length);
    if (selectedContact.length < 1) {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "There is no selected contact to be swapped.";
    }
    else {
        if (confirm("Are you sure to swap the selected " + countSelected + " contacts?")) {
            selectedContact = selectedContact.substring(0, selectedContact.length - 1);
            //alert(selectedContact);
            var xmlhttp;
            var url = 'includes/php/ajax/ajax_adminSwapContactList.php?selectedContactId=' + selectedContact + "&empId=" + toEmpId;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            }
            else
            {
                xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
            }
            xmlhttp.onreadystatechange = function()
            {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
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
                        msgSpan.innerHTML = countSelected + " contacts swapped successfully.";
                        displayEmplContact(fromEmpId, "Yes");
                        _("cmbToEmplLst").selectedIndex = 0;
                    }
                    else {
                        msgSpan.setAttribute("class", "notification n-error");
                        msgSpan.innerHTML = "Error while swapping contacts.";
                        displayEmplContact(fromEmpId, "Yes");
                        _("cmbToEmplLst").selectedIndex = 0;
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
                    ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Swapping Contacts...</span></p>";
                }
            };
            xmlhttp.open('GET', url, true);
            xmlhttp.send();
        }
        else {
            return;
        }
    }
    msgDiv.appendChild(msgSpan);
};

function empSwapChkAll() {
    var checkToggle;
    if (_('chkCheckSelector').checked === true) {
        checkToggle = true;
    } else {
        checkToggle = false;
    }
    var checkboxes = new Array();
    checkboxes = document.forms["frmContactLst"].getElementsByTagName('input');
    //alert(checkboxes.length);
    for (var i = 0, c = checkboxes.length; i < c; i++) {
        if (checkboxes[i].type === 'checkbox') {
            checkboxes[i].checked = checkToggle;
        }
    }
}

function searchSwapContact(empName) {
    var xmlhttp;
    var selectedEmpId = _('cmbFromEmplLst').value;
    var tblContactRow = _("swapEmpList");
    tblContactRow.innerHTML = "";
    var url = 'includes/php/ajax/ajax_adminSearchSwapContactList.php?empId=' + selectedEmpId + '&keyword=' + empName;
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
            tblContactRow.innerHTML = xmlhttp.responseText;
        }
        else {
            _("swapEmpList").innerHTML = "<tr><td colspan='3'><img src='ajax-loader.gif' height='15' width='15' style='padding-left: 150px;'><span style='font-size: 100%;'> Loading Contact List. Please Wait...</span></td></tr>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}

function displayEmplContact(empId, msgDisplay) {
    var xmlhttp;
    var succMsg = _("adminSuccessMsg");
    if (msgDisplay === "No")
        succMsg.innerHTML = "";
    var tblContactLst = _("paginationTable");
    tblContactLst.innerHTML = "";
    var url = 'includes/php/ajax/ajax_adminDisplaySwapContactList.php?empId=' + empId + '&task=' + "displayTable";
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
            tblContactLst.innerHTML = xmlhttp.responseText;
        }
        else {
            _("paginationTable").innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading Contact List. Please Wait...</span></p></div>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();

    var xmlhttp1;
    var cmbContact = _("cmbToEmplLst");
    var eleAjaxLoader = _("toEmpAjaxLoader");
    cmbContact.disabled = false;
    cmbContact.innerHTML = "";
    var url = 'includes/php/ajax/ajax_adminDisplaySwapContactList.php?empId=' + empId + '&task=' + "fillEmployeeList";
    if (window.XMLHttpRequest) {
        xmlhttp1 = new XMLHttpRequest();
    }
    else
    {
        xmlhttp1 = new ActiveXObject('Microsoft.XMLHTTP');
    }
    xmlhttp1.onreadystatechange = function()
    {
        if (xmlhttp1.readyState === 4 && xmlhttp1.status === 200)
        {
            eleAjaxLoader.innerHTML = "";
            cmbContact.innerHTML = xmlhttp1.responseText;
        }
        else {
            eleAjaxLoader.innerHTML = " <img src='ajax-loader.gif' height=15 width=15 /> Loading employee list...";
        }
    };
    xmlhttp1.open('GET', url, true);
    xmlhttp1.send();
}

function addCompany() {
    var rspText;
    var xmlhttp;
    var msgDiv = _("adminSuccessMsg");
    var ajaxLoader = _("ajaxLoaderProcessing");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");

    var cmpName = document.getElementsByName('txtCmpName')[0].value;
    var newString = cmpName.split("&");
    var txtCmpName = newString.join("zxtwuqmtz");
    var cmbCmpType = document.getElementsByName('cmbCmpType')[0].value;
    var txtWebSite = document.getElementsByName('txtWebSite')[0].value;
    var btnAdminCmpSubmit = document.getElementsByName('btnAdminCmpSubmit')[0].value;

    var url = 'includes/php/ajax/ajax_adminCompanyAdd.php?txtCmpName=' + txtCmpName + '&cmbCmpType=' + cmbCmpType + '&txtWebSite=' + txtWebSite + '&btnAdminCmpSubmit=' + btnAdminCmpSubmit;
    //alert(url);

    if (txtCmpName === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please enter a company name.";
        msgDiv.appendChild(msgSpan);
        document.frmCompany.txtCmpName.focus();
        return;
    } else if (cmbCmpType === "NA") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select a company type.";
        msgDiv.appendChild(msgSpan);
        document.frmCompany.cmbCmpType.focus();
        return;
    }
    else {
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
                //console.log(rspText);
                if (rspText === "Success") {
                    msgSpan.setAttribute("class", "notification n-success");
                    msgSpan.innerHTML = cmpName + " added to Company List successfully.";
                    document.getElementsByName('txtCmpName')[0].value = "";
                    document.getElementsByName('txtWebSite')[0].value = "";
                    document.getElementsByName('cmbCmpType')[0].selectedIndex = 0;
                    document.getElementsByName('txtCmpName')[0].focus();
                    displayCompanyList();
                }

                else {
                    msgSpan.setAttribute("class", "notification n-error");
                    msgSpan.innerHTML = "Please enter a unique company name.";
                    document.getElementsByName('txtCmpName')[0].value = "";
                    document.getElementsByName('txtWebSite')[0].value = "";
                    document.getElementsByName('cmbCmpType')[0].selectedIndex = 0;
                    document.getElementsByName('txtCmpName')[0].focus();
                    displayCompanyList();
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
                ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Saving company details...</span></p>";
            }
        };
        xmlhttp.open('GET', url, true);
        //xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send();
    }
}

function updateCompany() {
    var rspText;
    var xmlhttp;
    var msgDiv = _("adminSuccessMsg");
    var ajaxLoader = _("ajaxLoaderProcessing");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");

    var txtCmpName = document.getElementsByName('txtCmpName')[0].value;
    var newString = txtCmpName.split("&");
    txtCmpName = newString.join("zxtwuqmtz");
    var cmbCmpType = document.getElementsByName('cmbCmpType')[0].value;
    var txtWebSite = document.getElementsByName('txtWebSite')[0].value;
    var btnAdminCmpSubmit = document.getElementsByName('btnAdminCmpSubmit')[0].value;
    var hidCmpId = document.getElementsByName('companyId')[0].value;

    if (txtCmpName === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please enter a company name.";
        msgDiv.appendChild(msgSpan);
        document.frmCompany.txtCmpName.focus();
        return;
    } else if (cmbCmpType === "NA") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select a company type.";
        msgDiv.appendChild(msgSpan);
        document.frmCompany.cmbCmpType.focus();
        return;
    }
    else {
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
                    msgSpan.innerHTML = "Company details updated successfully.";
                    document.getElementsByName('txtCmpName')[0].value = "";
                    document.getElementsByName('txtWebSite')[0].value = "";
                    document.getElementsByName('cmbCmpType')[0].selectedIndex = 0;
                    document.getElementsByName('btnAdminCmpSubmit')[0].value = "Add";
                    document.frmCompany.btnAdminCmpSubmit.removeAttribute("onclick");
                    document.frmCompany.btnAdminCmpSubmit.setAttribute("onclick", "addCompany()");
                    _("hiddenSection").innerHTML = "";
                    var clearButton = document.createElement("input");
                    clearButton.setAttribute("type", "reset");
                    clearButton.setAttribute("name", "btnAdminCompanyClear");
                    clearButton.setAttribute("value", "Clear");
                    clearButton.setAttribute("class", "submit-gray");
                    _("frmCompanFieldset").appendChild(clearButton);
                    document.getElementsByName('txtCmpName')[0].focus();
                    displayCompanyList();
                }
                else {
                    msgSpan.setAttribute("class", "notification n-error");
                    msgSpan.innerHTML = "Company name already exist. Please enter a unique company name.";
                    document.frmCompany.txtCmpName.focus();
                    document.getElementsByName('txtCmpName')[0].value = "";
                    document.getElementsByName('txtWebSite')[0].value = "";
                    document.getElementsByName('cmbCmpType')[0].selectedIndex = 0;
                    document.getElementsByName('btnAdminCmpSubmit')[0].value = "Add";
                    document.frmCompany.btnAdminCmpSubmit.removeAttribute("onclick");
                    document.frmCompany.btnAdminCmpSubmit.setAttribute("onclick", "addCompany()");
                    _("hiddenSection").innerHTML = "";
                    var clearButton = document.createElement("input");
                    clearButton.setAttribute("type", "reset");
                    clearButton.setAttribute("name", "btnAdminCompanyClear");
                    clearButton.setAttribute("value", "Clear");
                    clearButton.setAttribute("class", "submit-gray");
                    _("frmCompanFieldset").appendChild(clearButton);
                    document.getElementsByName('txtCmpName')[0].focus();
                    displayCompanyList();
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
                ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Updating company details...</span></p>";
            }
        };
        xmlhttp.open('POST', 'includes/php/ajax/ajax_adminUpdateCompany.php', true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("txtCmpName=" + txtCmpName + "&cmbCmpType=" + cmbCmpType + "&txtWebSite=" + txtWebSite + "&btnAdminCmpSubmit=" + btnAdminCmpSubmit + "&hidCmpId=" + hidCmpId);
    }
}

function deleteCompany(id) {
    var xmlhttp;
    var rspText;
    var msgDiv = _("adminSuccessMsg");
    msgDiv.innerHTML = "";
    var ajaxLoader = _("ajaxLoaderProcessing");

    var msgSpan = document.createElement("span");

    var url = 'includes/php/ajax/ajax_deleteCompany.php?id=' + id;

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
                msgSpan.innerHTML = "Company details deleted successfully.";
                displayCompanyList();
            }
            else {
                msgSpan.setAttribute("class", "notification n-error");
                msgSpan.innerHTML = "Error while deleting company";
                displayCompanyList();
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
            ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Deleting Company List...</span></p>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}

function saveAdminTaskForm(type) {
    var msgDiv = _("adminSuccessMsg");
    var ajaxLoader = _("ajaxLoaderProcessing");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");
    var txtAdminValue = _("txtAdminTypeTask").value;
    var btnAdminSubmit = document.getElementsByName('btnAdminSubmit')[0].value;
    // Here we are using .split() instead of the replace() because replace() only convert
    // the first char but the .split(), then after .join() encode all the encountered char.
    txtAdminValue = txtAdminValue.split("+");
    txtAdminValue = txtAdminValue.join("*()");

    if (txtAdminValue === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please ensure that the field is not empty before adding.";
        msgDiv.appendChild(msgSpan);
        _("txtAdminTypeTask").focus();
        return;
    }

    var settingType;
    if (type === "Company Type") {
        settingType = "comp_type";
    }
    else if (type === "Office Type") {
        settingType = "offi_type";
    }
    else if (type === "Company Status") {
        settingType = "acti_status";
    }
    else if (type === "Contact Relation") {
        settingType = "add_relation";
    }
    else if (type === "Functional Department") {
        settingType = "func_dept";
    }
    else if (type === "Salutation") {
        settingType = "salute";
    }
    else if (type === "College") {
        settingType = "college";
    }
    else if (type === "Stream") {
        settingType = "stream";
    }
    else if (type === "Travel") {
        settingType = "travel";
    }

    var rspText;
    var xmlhttp;
    var url = 'includes/php/ajax/ajax_Admin.php';
    var postData = "txtInput=" + txtAdminValue + "&settingType=" + settingType + "&btnAdminSubmit=" + btnAdminSubmit;

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
            if (rspText === "Success") {
                msgSpan.setAttribute("class", "notification n-success");
                msgSpan.innerHTML = type + " added successfully.";
                _("txtAdminTypeTask").value = "";
                _("txtAdminTypeTask").focus();
                displayAdminTaskListTable(settingType);
            }
            else {
                msgSpan.setAttribute("class", "notification n-error");
                msgSpan.innerHTML = "Error while adding " + type;
                _("txtAdminTypeTask").value = "";
                _("txtAdminTypeTask").focus();
                displayAdminTaskListTable(settingType);
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
            ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Saving " + type + " details...</span></p>";
        }
    };
    xmlhttp.open('POST', url, true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(postData);
}

function updateAdminTypeTask(settValue) {
    var rspText;
    var xmlhttp;
    var ajaxLoader = _("ajaxLoaderProcessing");
    var msgDiv = _("adminSuccessMsg");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");

    var txtType = _("txtAdminTypeTask").value;
    txtType = txtType.split("+");
    txtType = txtType.join("*()");
    var cmbTypeStatus = document.getElementsByName('cmbTypeStatus')[0].value;
    var btnAdminSubmit = document.getElementsByName('btnAdminSubmit')[0].value;
    var hidTypeId = document.getElementsByName('typeId')[0].value;

    var url = 'includes/php/ajax/ajax_adminTypeUpdate.php';
    var type = document.getElementsByName('settType')[0].value;
    var settingType;
    if (type === "comp_type") {
        settingType = "Company Type";
    }
    else if (type === "offi_type") {
        settingType = "Office Type";
    }
    else if (type === "acti_status") {
        settingType = "Company Status";
    }
    else if (type === "add_relation") {
        settingType = "Contact Relation";
    }
    else if (type === "func_dept") {
        settingType = "Functional Department";
    }
    else if (type === "salute") {
        settingType = "Salutation";
    }
    else if (type === "college") {
        settingType = "College";
    }
    else if (type === "stream") {
        settingType = "Stream";
    }

    if (txtType === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please ensure that the field should be not empty.";
        msgDiv.appendChild(msgSpan);
        _("txtAdminTypeTask").focus();
        return;
    }
    else {
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
                    msgSpan.innerHTML = "Data successfully updated.";
                    _("txtAdminTypeTask").value = "";
                    _("typeStatusSection").innerHTML = "";
                    document.getElementsByName('btnAdminSubmit')[0].value = "Add";
                    var addButton = document.getElementsByName("btnAdminSubmit")[0];
                    addButton.removeAttribute("onclick");
                    addButton.setAttribute("onclick", "saveAdminTaskForm('" + settingType + "')");
                    _("hiddenSection").innerHTML = "";
                    var clearButton = document.createElement("input");
                    clearButton.setAttribute("type", "reset");
                    clearButton.setAttribute("name", "btnAdminClear");
                    clearButton.setAttribute("value", "Clear");
                    clearButton.setAttribute("class", "submit-gray");
                    _("frmBtnFieldset").appendChild(clearButton);
                    _("txtAdminTypeTask").focus();
                    displayAdminTaskListTable(type);
                }

                else {
                    msgSpan.setAttribute("class", "notification n-error");
                    msgSpan.innerHTML = "Error while updating the data.";

                    _("txtAdminTypeTask").value = "";
                    _("typeStatusSection").innerHTML = "";
                    _("txtAdminTypeTask").value = "";
                    _("typeStatusSection").innerHTML = "";
                    document.getElementsByName('btnAdminSubmit')[0].value = "Add";
                    var addButton = document.getElementsByName("btnAdminSubmit")[0];
                    addButton.removeAttribute("onclick");
                    addButton.setAttribute("onclick", "saveAdminTaskForm('" + settingType + "')");
                    _("hiddenSection").innerHTML = "";
                    var clearButton = document.createElement("input");
                    clearButton.setAttribute("type", "reset");
                    clearButton.setAttribute("name", "btnAdminClear");
                    clearButton.setAttribute("value", "Clear");
                    clearButton.setAttribute("class", "submit-gray");
                    _("frmBtnFieldset").appendChild(clearButton);
                    _("txtAdminTypeTask").focus();
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
                ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Updating " + settingType + " details...</span></p>";
            }
        };
        xmlhttp.open('POST', url, true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("txtType=" + txtType + "&cmbTypeStatus=" + cmbTypeStatus + "&btnAdminSubmit=" + btnAdminSubmit + "&hidTypeId=" + hidTypeId + "&type=" + type + "&settingValue=" + settValue);
    }
}

var addUser = function() {
    var msgDiv = _("adminSuccessMsg");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");
    var ajaxLoader = _("ajaxLoaderProcessing");

    var txtEmpName = document.getElementsByName("txtEmpName")[0].value;
    var txtUserId = document.getElementsByName("txtUserId")[0].value;
    var txtPassword = document.getElementsByName("txtPassword")[0].value;
    var cmbUserType = document.getElementsByName("cmbUserType")[0].value;
    var txtDob = document.getElementsByName("txtDob")[0].value;
    var txtEmail = document.getElementsByName("txtEmail")[0].value;
    var txtMobile = document.getElementsByName("txtMobile")[0].value;
    var txtAddress = document.getElementsByName("txtAddress")[0].value;
    var btnFrmUserAdd = document.getElementsByName("btnFrmUserAdd")[0].value;
    var userIdRegex = /^[a-z0-9]+$/i;

    if (txtEmpName === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please enter the Employee name.";
        msgDiv.appendChild(msgSpan);
        document.getElementsByName("txtEmpName")[0].focus();
        return;
    }
    else if (txtUserId === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please enter the user ID.";
        msgDiv.appendChild(msgSpan);
        document.getElementsByName("txtUserId")[0].focus();
        return;
    }
    else if (!userIdRegex.test(txtUserId)) {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please enter an alphanumeric user ID.";
        msgDiv.appendChild(msgSpan);
        document.getElementsByName("txtUserId")[0].focus();
        return;
    }
    else if (txtPassword === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please enter the password.";
        msgDiv.appendChild(msgSpan);
        document.getElementsByName("txtPassword")[0].focus();
        return;
    }
    else if (cmbUserType === "NA") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select the type of the user.";
        msgDiv.appendChild(msgSpan);
        document.getElementsByName("cmbUserType")[0].focus();
        return;
    }
    var rspText;
    var xmlhttp;
    var url = 'includes/php/ajax/ajax_adminAddUser.php';
    var sendPostItem = "txtEmpName=" + txtEmpName + "&txtUserId=" + txtUserId + "&txtPassword=" + txtPassword + "&cmbUserType=" + cmbUserType + "&txtDob=" + txtDob + "&txtEmail=" + txtEmail + "&txtMobile=" + txtMobile + "&txtAddress=" + txtAddress + "&btnFrmUserAdd=" + btnFrmUserAdd;

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
                msgSpan.innerHTML = txtEmpName + " was added to the company list successfully.";
                document.getElementsByName("txtEmpName")[0].value = "";
                document.getElementsByName("txtUserId")[0].value = "";
                document.getElementsByName("txtPassword")[0].value = "";
                document.getElementsByName("txtUserId")[0].value = "";
                document.getElementsByName("txtPassword")[0].value = "";
                document.getElementsByName("cmbUserType")[0].selectedIndex = "NA";
                document.getElementsByName("txtDob")[0].value = "";
                document.getElementsByName("txtEmail")[0].value = "";
                document.getElementsByName("txtMobile")[0].value = "";
                document.getElementsByName("txtAddress")[0].value = "";
                document.getElementsByName("txtEmpName")[0].focus();
                displayUserList();
            }
            else {
                msgSpan.setAttribute("class", "notification n-error");
                msgSpan.innerHTML = "Error while adding " + cmbUserType;
                document.getElementsByName("txtEmpName")[0].value = "";
                document.getElementsByName("txtUserId")[0].value = "";
                document.getElementsByName("txtPassword")[0].value = "";
                document.getElementsByName("txtUserId")[0].value = "";
                document.getElementsByName("txtPassword")[0].value = "";
                document.getElementsByName("cmbUserType")[0].selectedIndex = "NA";
                document.getElementsByName("txtDob")[0].value = "";
                document.getElementsByName("txtEmail")[0].value = "";
                document.getElementsByName("txtMobile")[0].value = "";
                document.getElementsByName("txtAddress")[0].value = "";
                document.getElementsByName("txtEmpName")[0].focus();
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
            ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Saving user's details...</span></p>";
        }
    };
    xmlhttp.open('POST', url, true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(sendPostItem);
};

var updateUser = function() {
    var msgDiv = _("adminSuccessMsg");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");
    var ajaxLoader = _("ajaxLoaderProcessing");

    var txtEmpName = document.getElementsByName("txtEmpName")[0].value;
    var txtUserId = document.getElementsByName("txtUserId")[0].value;
    var txtPassword = document.getElementsByName("txtPassword")[0].value;
    var cmbUserType = document.getElementsByName("cmbUserType")[0].value;
    var txtDob = document.getElementsByName("txtDob")[0].value;
    var txtEmail = document.getElementsByName("txtEmail")[0].value;
    var txtMobile = document.getElementsByName("txtMobile")[0].value;
    var txtAddress = document.getElementsByName("txtAddress")[0].value;
    var cmbUserStatus = document.getElementsByName("cmbUserStatus")[0].value;
    var userId = document.getElementsByName("userId")[0].value;
    var btnFrmUserAdd = document.getElementsByName("btnFrmUserAdd")[0].value;
    var userIdRegex = /^[a-z0-9]+$/i;

    if (txtEmpName === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please enter the Employee name.";
        msgDiv.appendChild(msgSpan);
        document.getElementsByName("txtEmpName")[0].focus();
        return;
    }
    else if (txtUserId === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please enter the user ID.";
        msgDiv.appendChild(msgSpan);
        document.getElementsByName("txtUserId")[0].focus();
        return;
    }
    else if (!userIdRegex.test(txtUserId)) {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please enter an alphanumeric user ID.";
        msgDiv.appendChild(msgSpan);
        document.getElementsByName("txtUserId")[0].focus();
        return;
    }
    else if (txtPassword === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please enter the password.";
        msgDiv.appendChild(msgSpan);
        document.getElementsByName("txtPassword")[0].focus();
        return;
    }
    else if (cmbUserType === "NA") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select the type of the user.";
        msgDiv.appendChild(msgSpan);
        document.getElementsByName("cmbUserType")[0].focus();
        return;
    }
    var rspText;
    var xmlhttp;
    var url = 'includes/php/ajax/ajax_adminUpdateUser.php';
    var sendPostItem = "txtEmpName=" + txtEmpName + "&txtUserId=" + txtUserId + "&txtPassword=" + txtPassword + "&cmbUserType=" + cmbUserType + "&txtDob=" + txtDob + "&txtEmail=" + txtEmail + "&txtMobile=" + txtMobile + "&txtAddress=" + txtAddress + "&cmbUserStatus=" + cmbUserStatus + "&btnFrmUserAdd=" + btnFrmUserAdd + "&userId=" + userId + "&txtPassword=" + txtPassword;

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
                msgSpan.innerHTML = txtEmpName + "'s details updated successfully.";
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
                var clearButton = document.createElement("input");
                clearButton.setAttribute("type", "reset");
                clearButton.setAttribute("name", "btnFrmUserClear");
                clearButton.setAttribute("value", "Clear");
                clearButton.setAttribute("onclick", "javascript: document.frmManageUser.txtEmpName.focus()");
                clearButton.setAttribute("class", "submit-gray");
                _("fldUserBtnContainer").appendChild(clearButton);
                document.getElementsByName("txtEmpName")[0].focus();
                displayUserList();
            }
            else {
                msgSpan.setAttribute("class", "notification n-error");
                msgSpan.innerHTML = "Error while updating " + txtEmpName + "'s details";
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
                var clearButton = document.createElement("input");
                clearButton.setAttribute("type", "reset");
                clearButton.setAttribute("name", "btnFrmUserClear");
                clearButton.setAttribute("value", "Clear");
                clearButton.setAttribute("onclick", "javascript: document.frmManageUser.txtEmpName.focus()");
                clearButton.setAttribute("class", "submit-gray");
                _("fldUserBtnContainer").appendChild(clearButton);
                document.getElementsByName("txtEmpName")[0].focus();
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
            ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Updating user's details...</span></p>";
        }
    };
    xmlhttp.open('POST', url, true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(sendPostItem);
};

var displayUserList = function() {
    var xmlhttp;
    var url = 'includes/php/ajax/ajax_admin_CheckTaskPagination.php?userList=' + "Yes";
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
            _("paginationTable").innerHTML = xmlhttp.responseText;
            _("refreshButton").innerHTML = "Refresh User List <img src='bin.gif' width='16' height='12' alt='Display User List' title='Dispaly User List'/>";
        }
        else {
            _("paginationTable").innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading Users List. Please Wait...</span></p></div>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
};

function displayCompanyList() {
    var xmlhttp;
    var url = 'includes/php/ajax/ajax_admin_CheckTaskPagination.php?companyList=' + "Yes";
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
            _("paginationTable").innerHTML = xmlhttp.responseText;
            _("refreshButton").innerHTML = "Refresh Company List <img src='bin.gif' width='16' height='12' alt='Display Company List' title='Dispaly Company List'/>";
        }
        else {
            _("paginationTable").innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading Company List. Please Wait...</span></p></div>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}

/**
 * 
 * @param {type} type defines which admin work the user selected
 * @returns it display the corresponding List of data in Pagination Table format
 */
function displayAdminTaskListTable(type) {
    var settingType;
    if (type === "comp_type") {
        settingType = "Company Type";
    }
    else if (type === "offi_type") {
        settingType = "Office Type";
    }
    else if (type === "acti_status") {
        settingType = "Company Status";
    }
    else if (type === "add_relation") {
        settingType = "Contact Relation";
    }
    else if (type === "func_dept") {
        settingType = "Functional Department";
    }
    else if (type === "salute") {
        settingType = "Salutation";
    }
    else if (type === "college") {
        settingType = "College";
    }
    else if (type === "stream") {
        settingType = "Stream";
    }
    else if (type === "travel")
        settingType = "Travel";

    var xmlhttp;
    var url = 'includes/php/ajax/ajax_admin_CheckTaskPagination.php?sett_type=' + type;

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
            _("paginationTable").innerHTML = xmlhttp.responseText;
            _("refreshButton").innerHTML = "Refresh <img src='bin.gif' width='16' height='12' alt='Refresh List' title='Dispaly Company Type List'/>";
        }
        else {
            _("paginationTable").innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading " + settingType + " List. Please Wait...</span></p></div>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}

function displayTodoListTable() {
    var empId = _("cmbEmplLst").value;
//    inputField,inputField1
    var startDate = _("inputField").value;
    var endDate = _("inputField1").value;

    var msgDiv = _("adminSuccessMsg");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");

    if (_("hiddenEmpId").hasAttribute("value")) {
        _("hiddenEmpId").removeAttribute("value");
    }

    if (empId === 'NA') {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select an employee.";
        msgDiv.appendChild(msgSpan);
        _("cmbEmplLst").focus();
        return;
    }

    var xmlhttp;
    var url = 'includes/php/ajax/ajax_admin_CheckTaskPagination.php?empId=' + empId + '&startDate=' + startDate + "&endDate="
    endDate;
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
            _("paginationTable").innerHTML = xmlhttp.responseText;
            _("hiddenEmpId").value = empId;
        }
        else {
            _("paginationTable").innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading Reminder List. Please Wait...</span></p></div>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}

function addDistrict() {
    var rspText;
    var xmlhttp;
    var ajaxLoader = _("ajaxLoaderProcessing");
    var msgDiv = _("adminSuccessMsg");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");

    var city = document.getElementsByName('txtCity')[0].value;
    var state = document.getElementsByName('cmbState')[0].value;
    var country = document.getElementsByName('cmbCountry')[0].value;
    var btnAddDistrict = document.getElementsByName('btnAddDistrict')[0].value;

    if (document.getElementsByName("txtOtherState")[0]) {
        state = document.getElementsByName('txtOtherState')[0].value;
        if (state === "") {
            msgSpan.setAttribute("class", "notification n-error");
            msgSpan.innerHTML = "Please enter the other state name.";
            msgDiv.appendChild(msgSpan);
            document.frmDistrict.txtOtherState.focus();
            return;
        }
    }
    if (city === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please enter a city name.";
        msgDiv.appendChild(msgSpan);
        document.frmDistrict.txtCity.focus();
        return;
    }
    else if (state === "NA" || state === "Please select a State") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select a State.";
        msgDiv.appendChild(msgSpan);
        document.frmDistrict.cmbState.focus();
        return;
    }
    else if (country === "NA") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select a Country.";
        msgDiv.appendChild(msgSpan);
        document.frmDistrict.cmbCountry.focus();
        return;
    }
    else {
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
                    msgSpan.innerHTML = city + " added to District List successfully.";
                    document.getElementsByName('txtCity')[0].value = "";
                    document.getElementsByName('cmbState')[0].selectedIndex = "NA";
                    document.getElementsByName('cmbCountry')[0].selectedIndex = "NA";
                    _('otherState').innerHTML = "";
                    document.frmDistrict.cmbCountry.disabled = false;
                    document.frmDistrict.cmbState.disabled = false;
                    document.getElementsByName('txtCity')[0].focus();
                    displayDistrictList();
                }
                else {
                    msgSpan.setAttribute("class", "notification n-error");
                    msgSpan.innerHTML = "Error while adding District.";
                    document.getElementsByName('txtCity')[0].value = "";
                    document.getElementsByName('cmbState')[0].selectedIndex = "NA";
                    document.getElementsByName('cmbCountry')[0].selectedIndex = "NA";
                    _('otherState').innerHTML = "";

                    document.frmDistrict.cmbCountry.disabled = false;
                    document.frmDistrict.cmbState.disabled = false;
                    document.getElementsByName('txtCity')[0].focus();
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
                ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Saving district details...</span></p>";
            }
        };
        xmlhttp.open('POST', 'includes/php/ajax/ajax_adminAddDistrict.php', true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("city=" + city + "&state=" + state + "&country=" + country + "&btnAddDistrict=" + btnAddDistrict);
    }
}

function updateDistrict() {
    var rspText;
    var xmlhttp;
    var msgDiv = _("adminSuccessMsg");
    var ajaxLoader = _("ajaxLoaderProcessing");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");

    var hidAddrId = document.getElementsByName('districtId')[0].value;
    var city = document.getElementsByName('txtCity')[0].value;
    var state = document.getElementsByName('cmbState')[0].value;
    var country = document.getElementsByName('cmbCountry')[0].value;
    var btnAddDistrict = document.getElementsByName('btnAddDistrict')[0].value;

    if (document.getElementsByName("txtOtherState")[0]) {
        state = document.getElementsByName('txtOtherState')[0].value;
        if (state === "") {
            msgSpan.setAttribute("class", "notification n-error");
            msgSpan.innerHTML = "Please enter the other state name.";
            msgDiv.appendChild(msgSpan);
            document.frmDistrict.txtOtherState.focus();
            return;
        }
    }
    if (city === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please enter a city name.";
        msgDiv.appendChild(msgSpan);
        document.frmDistrict.txtCity.focus();
        return;
    }
    else if (state === "NA" || state === "Please select a State") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select a State.";
        msgDiv.appendChild(msgSpan);
        document.frmDistrict.cmbState.focus();
        return;
    }

    else if (country === "NA") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select a Country.";
        msgDiv.appendChild(msgSpan);
        document.frmDistrict.cmbCountry.focus();
        return;
    }

    else {

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
                    msgSpan.innerHTML = city + "'s District details updated successfully.";
                    document.getElementsByName('txtCity')[0].value = "";
                    document.getElementsByName('cmbState')[0].selectedIndex = "NA";
                    document.getElementsByName('cmbCountry')[0].selectedIndex = "NA";
                    _('otherState').innerHTML = "";
                    document.getElementsByName('btnAddDistrict')[0].value = "Add";
                    document.frmDistrict.btnAddDistrict.removeAttribute("onclick");
                    document.frmDistrict.btnAddDistrict.setAttribute("onclick", "addDistrict()");
                    _("hiddenSection").innerHTML = "";
                    var clearButton = document.createElement("input");
                    clearButton.setAttribute("type", "reset");
                    clearButton.setAttribute("name", "btnAdminClear");
                    clearButton.setAttribute("value", "Clear");
                    clearButton.setAttribute("class", "submit-gray");
                    _("frmBtnFieldset").appendChild(clearButton);
                    document.frmDistrict.cmbCountry.disabled = false;
                    document.frmDistrict.cmbState.disabled = false;
                    document.getElementsByName('txtCity')[0].focus();
                    displayDistrictList();
                }
                else {
                    msgSpan.setAttribute("class", "notification n-error");
                    msgSpan.innerHTML = "Error while updating District.";
                    document.getElementsByName('txtCity')[0].value = "";
                    document.getElementsByName('cmbState')[0].selectedIndex = "NA";
                    document.getElementsByName('cmbCountry')[0].selectedIndex = "NA";
                    _('otherState').innerHTML = "";
                    document.getElementsByName('btnAddDistrict')[0].value = "Add";
                    document.frmDistrict.btnAddDistrict.removeAttribute("onclick");
                    document.frmDistrict.btnAddDistrict.setAttribute("onclick", "addDistrict()");
                    _("hiddenSection").innerHTML = "";
                    var clearButton = document.createElement("input");
                    clearButton.setAttribute("type", "reset");
                    clearButton.setAttribute("name", "btnAdminClear");
                    clearButton.setAttribute("value", "Clear");
                    clearButton.setAttribute("class", "submit-gray");
                    _("frmBtnFieldset").appendChild(clearButton);
                    document.frmDistrict.cmbCountry.disabled = false;
                    document.frmDistrict.cmbState.disabled = false;
                    document.getElementsByName('txtCity')[0].focus();
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
                ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Updating district details...</span></p>";
            }
        };
        xmlhttp.open('POST', 'includes/php/ajax/ajax_adminUpdateDistrict.php', true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("city=" + city + "&state=" + state + "&country=" + country + "&btnAddDistrict=" + btnAddDistrict + "&hidAddrId=" + hidAddrId);
    }
}

function otherState(value) {
    if (value === "Other") {
        var otherStatePara = document.createElement("p");
        var otherState = document.createElement("input");
        otherState.setAttribute("type", "input");
        otherState.setAttribute("name", "txtOtherState");
        otherState.setAttribute("placeholder", "Enter other State Name");
        otherState.style.width = "55%";
        otherStatePara.appendChild(otherState);
        _("otherState").appendChild(otherStatePara);
        document.frmDistrict.txtOtherState.focus();
        document.frmDistrict.cmbCountry.disabled = false;
        document.frmDistrict.cmbCountry.value = "NA";
    }
    else {
        document.frmDistrict.cmbCountry.value = "India";
        document.frmDistrict.cmbCountry.disabled = true;
        _('otherState').innerHTML = "";
    }
}

function otherCountry(value) {
    if (value !== 'India') {
        if (document.getElementsByName("txtOtherState")[0]) {
        } else {
            var otherStatePara = document.createElement("p");
            var otherState = document.createElement("input");
            otherState.setAttribute("type", "input");
            otherState.setAttribute("name", "txtOtherState");
            otherState.setAttribute("placeholder", "Enter other State Name");
            otherState.style.width = "55%";
            otherStatePara.appendChild(otherState);
            _("otherState").appendChild(otherStatePara);
            document.frmDistrict.txtOtherState.focus();
            document.frmDistrict.cmbState.value = "Other";
            document.frmDistrict.cmbState.disabled = true;
        }
    }
    else {
        document.frmDistrict.cmbState.disabled = false;
        _('otherState').innerHTML = "";
        document.frmDistrict.cmbState.selectedIndex = "NA";
    }
}

function clearDistrictFrm() {
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
    document.getElementsByName('txtCity')[0].focus();
}

function displayDistrictList() {
    var xmlhttp;
    var url = 'includes/php/ajax/ajax_admin_CheckTaskPagination.php?districtList=' + "Yes";
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
            _("paginationTable").innerHTML = xmlhttp.responseText;
            _("refreshButton").innerHTML = "Refresh District List <img src='bin.gif' width='16' height='12' alt='Display Company List' title='Dispaly Company List'/>";
        }
        else {
            _("paginationTable").innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading District List. Please Wait...</span></p></div>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}

function cancelAdminTodo(todoId) {
    var comment = document.getElementsByName("txtAreaComment")[0].value;
    var ajaxLoader = _("ajaxLoaderProcessing");
    var rspText;
    var xmlhttp;
    var msgDiv = _("adminSuccessMsg");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");
    var url = 'includes/php/ajax/ajax_Cancel_Todo.php?todoId=' + todoId + '&comment=' + comment;
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
            if (rspText === "Success") {
                msgSpan.setAttribute("class", "notification n-success");
                msgSpan.innerHTML = "To-Do List cancelled successfully.";
                refreshAdminTodoList();
            }
            else {
                msgSpan.setAttribute("class", "notification n-error");
                msgSpan.innerHTML = "Error while cancelling To-Do Item.";
                refreshAdminTodoList();
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
            ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Cancelling Reminder details...</span></p>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}

function refreshAdminTodoList() {
    var frmManageTodo = document.getElementsByName("frmUpdateTodo")[0];
    frmManageTodo.innerHTML = "";
    frmManageTodo.innerHTML = "<p><label>Select an Employee</label><select class='input-short' style='width: 65%' name='cmbEmplLst' id ='cmbEmplLst' ></select></p><fieldset id='frmBtnFieldset'><input class='submit-green' type='button' onclick='displayTodoListTable()' name='btnTodoEmplSubmit' value='Submit' /><input class='submit-gray' type='reset' name='btnTodoEmplSubmitReset' value='Reset' />";
    var empList = _("cmbEmplLst");
    var xmlhttp;
    var url = 'includes/php/ajax/ajax_employee_list.php?request=' + 'yes';
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
            empList.innerHTML = xmlhttp.responseText;
            refreshAdminTodoPagination();
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}

function refreshAdminTodoPagination() {
    var paginationTable = _("paginationTable");
    paginationTable.innerHTML = "";

    var xmlhttp;
    var empId = _("hiddenEmpId").value;
    var url = 'includes/php/ajax/ajax_admin_CheckTaskPagination.php?empId=' + empId;
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
            paginationTable.innerHTML = xmlhttp.responseText;
        }
        else {
            _("paginationTable").innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading Reminder List. Please Wait...</span></p></div>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}

function updateToDoForm() {
    var rspText;
    var xmlhttp;
    var msgDiv = _("adminSuccessMsg");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");
    var ajaxLoader = _("ajaxLoaderProcessing");

    if (closingDate === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select a Closing Date.";
        msgDiv.appendChild(msgSpan);
        document.getElementsByName('txtCloseDay')[0].focus();
        return;
    }
    var closingDate = document.getElementsByName("txtCloseDay")[0].value;
    var description = document.getElementsByName("txtAreaDesc")[0].value;
    var status = document.getElementsByName("cmbStatus")[0].value;
    var contact;
    if (_('chkCorporate').checked) {
        if (_("hidContId").value === 0) {
            if (_("contactLst").value === "NA") {
                msgSpan.setAttribute("class", "notification n-error");
                msgSpan.innerHTML = "Please select a Contact.";
                msgDiv.appendChild(msgSpan);
                document.getElementsByName('cmpContactId')[0].focus();
                return;
            }
            else {
                contact = _("contactLst").value;
            }
        }
        else {
            if (_("contactLst").value === "NA") {
                contact = _("hidContId").value;
            }
            else {
                contact = _("contactLst").value;
            }
        }
    }
    else {
        contact = 0;
    }
    if (description.indexOf('\'') >= 0) {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please remove the single quote(') character from the description.";
        msgDiv.appendChild(msgSpan);
        _('txtAreaDesc').focus();
        return;
    }
    var todoId = _("todoId").value;
    var updateBtn = _("btnTodoUpdate").value;

    var url = 'includes/php/ajax/ajax_update_todo.php';
    var param = "todoId=" + todoId + "&contact=" + contact + "&status=" + status + "&description=" + description + "&closingDate=" + closingDate + "&updateBtn=" + updateBtn;

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
            if (rspText === "Wrong Date") {
                msgSpan.setAttribute("class", "notification n-error");
                msgSpan.innerHTML = "Please select a correct closing date.";
                msgDiv.appendChild(msgSpan);
                document.getElementsByName('txtCloseDay')[0].focus();
                return;
            }
            else if (rspText === "Success") {
                msgSpan.setAttribute("class", "notification n-success");
                msgSpan.innerHTML = "Reminder updated successfully.";
                refreshAdminTodoList();
            }
            else {
                msgSpan.setAttribute("class", "notification n-error");
                msgSpan.innerHTML = "Error while updating Reminder.";
                refreshAdminTodoList();
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
            ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Updating Reminder details...</span></p>";
        }
    };
    xmlhttp.open('POST', url, true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(param);
}

function searchTableData(keyValue, valueType) {
    var type = valueType.toLowerCase();
    var newString = keyValue.split("&");
    keyValue = newString.join("zxtwuqmtz");
    keyValue = keyValue.split("+");
    keyValue = keyValue.join("*()");
    var url = 'includes/php/ajax/ajax_admin_search_table.php?value=' + keyValue + '&type=' + type;
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
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            viewList.innerHTML = xmlhttp.responseText;
        }
        else {
            _("paginationTable").innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading " + valueType + " List. Please Wait...</span></p></div>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}

var displayPendingApprovalList = function(contactId, category, officeId) {
    var url = 'includes/php/ajax/ajax_pending_contact_list.php?contactId=' + contactId + '&category=' + category + '&officeId=' + officeId;
    var viewContactDetails = _("approve-section-div");
    viewContactDetails.innerHTML = "";
    var msgDiv = _("adminSuccessMsg");
    msgDiv.innerHTML = "";
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
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            viewContactDetails.innerHTML = xmlhttp.responseText;
        }
        else {
            _("approve-section-div").innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading Contact Details. Please Wait...</span></p></div>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
};

var approveUpdatedContact = function(contId) {
    var url = 'includes/php/ajax/ajax_approve_contact.php?contactId=' + contId;
    var viewContactDetails = _("approve-section-div");
    viewContactDetails.innerHTML = "";
    var msgDiv = _("adminSuccessMsg");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");
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
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            viewContactDetails.innerHTML = "";
            var rspText = xmlhttp.responseText;
            if (rspText === "Success") {
                msgSpan.setAttribute("class", "notification n-success");
                msgSpan.innerHTML = "Contact Edit request has been approved successfully.";
                msgDiv.appendChild(msgSpan);
                refreshEditRequestList();
            }
            else {
                msgSpan.setAttribute("class", "notification n-error");
                msgSpan.innerHTML = "Error while approving Contact Edit request. Please try again.";
                msgDiv.appendChild(msgSpan);
            }
        }
        else {
            viewContactDetails.innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Approving request for contact edit. Please Wait...</span></p></div>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
};

function refreshEditRequestList() {
    var url = 'includes/php/ajax/ajax_refresh_edit_request.php';
    var rquestList = _("pending-edit-list");
    rquestList.innerHTML = "";
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
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            rquestList.innerHTML = xmlhttp.responseText;
        }
        else {
            rquestList.innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loding request for approval list. Please Wait...</span></p></div>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}

var disapproveUpdatedContact = function(contId, category) {
    var comment = _("txtDisapproveReason").value;
    var msgDiv = _("adminSuccessMsg");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");
    if (comment === "") {
        if (_("comment-div").style.display === "none") {
            _("comment-div").style.display = "block";
        } else {
            msgSpan.setAttribute("class", "notification n-error");
            msgSpan.innerHTML = "Please enter the reason for disapproval here.";
            msgDiv.appendChild(msgSpan);
        }
    } else {
        var url = 'includes/php/ajax/ajax_disapprove_contact.php?contactId=' + contId + '&category=' + category + '&comment=' + comment;
        var viewContactDetails = _("approve-section-div");
        viewContactDetails.innerHTML = "";

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
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                viewContactDetails.innerHTML = "";
                var rspText = xmlhttp.responseText;
                if (rspText === "Success") {
                    msgSpan.setAttribute("class", "notification n-success");
                    msgSpan.innerHTML = "Contact Edit request has been rejected successfully.";
                    msgDiv.appendChild(msgSpan);
                    refreshEditRequestList();
                }
                else {
                    msgSpan.setAttribute("class", "notification n-error");
                    msgSpan.innerHTML = "Error while rejecting Contact Edit request. Please try again.";
                    msgDiv.appendChild(msgSpan);
                }
            }
            else {
                viewContactDetails.innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Rejecting request for contact edit. Please Wait...</span></p></div>";
            }
        };
        xmlhttp.open('GET', url, true);
        xmlhttp.send();
    }
};