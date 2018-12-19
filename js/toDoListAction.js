function _(e1) {
    return document.getElementById(e1);
}

function saveToDoForm() {
    var rspText;
    var xmlhttp;
    var msgDiv = _("todoSuccessMsg");
    var ajaxLoader = _("ajaxLoaderProcessing");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");
    var txtToDay = document.getElementsByName('txtToDay')[0].value;
    var txtCloseDay = document.getElementsByName('txtCloseDay')[0].value;
    var txtAreaDesc = document.getElementsByName('txtAreaDesc')[0].value;
    var cmbStatus = document.getElementsByName('cmbStatus')[0].value;
    var cmbTodoType = document.getElementsByName('cmbTodoType')[0].value;
    var cmpContactId = "";
    if (document.frmToDo.cmpContactId) {
        cmpContactId = document.getElementsByName('cmpContactId')[0].value;
        if (cmpContactId === "NA") {
            msgSpan.setAttribute("class", "notification n-error");
            msgSpan.innerHTML = "Please select a Contact Name.";
            msgDiv.appendChild(msgSpan);
            document.getElementsByName('cmpContactId')[0].focus();
            return;
        }
    }

    var btnTodoSubmit = document.getElementsByName('btnTodoSubmit')[0].value;
    if (txtCloseDay === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select a Closing Date.";
        msgDiv.appendChild(msgSpan);
        document.getElementsByName('txtCloseDay')[0].focus();
        return;
    }
    if (txtAreaDesc.indexOf('\'') >= 0) {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please remove the single quote(') character from the description.";
        msgDiv.appendChild(msgSpan);
        _('txtAreaDesc').focus();
        return;
    }
    else if (cmbTodoType === "NA") {
        cmbTodoType = "";
    }
    var url = "txtToDay=" + txtToDay + "&txtCloseDay=" + txtCloseDay + "&txtAreaDesc=" + txtAreaDesc + "&cmbStatus=" + cmbStatus + "&cmpContactId=" + cmpContactId + "&btnTodoSubmit=" + btnTodoSubmit + "&cmbTodoType=" + cmbTodoType;
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
                msgSpan.innerHTML = "Reminder added successfully.";
                document.getElementsByName('txtAreaDesc')[0].value = "";
                document.getElementsByName('txtCloseDay')[0].value = "";
                document.getElementsByName('cmbTodoType')[0].selectedIndex = "NA";
                document.getElementsByName('chkCorporate')[0].checked = false;
                _("corporateDeatils").innerHTML = "";
                todoRefreshClosingDateList();
                displayOnlyByType('all');
            }
            else {
                msgSpan.setAttribute("class", "notification n-error");
                msgSpan.innerHTML = "Error while adding Reminder.";
                document.getElementsByName('txtAreaDesc')[0].value = "";
                document.getElementsByName('txtCloseDay')[0].value = "";
                document.getElementsByName('cmbTodoType')[0].selectedIndex = "NA";
                document.getElementsByName('chkCorporate')[0].checked = false;
                _("corporateDeatils").innerHTML = "";
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
            ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Saving your reminder...</span></p>";
        }
    };
    xmlhttp.open('POST', 'includes/php/ajax/ajax_AddToDoList.php', true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(url);
}

function clearSaveToDoForm() {
    document.getElementsByName('txtAreaDesc')[0].value = "";
    document.getElementsByName('txtCloseDay')[0].value = "";
    document.getElementsByName('cmbTodoType')[0].selectedIndex = "NA";
    document.getElementsByName('chkCorporate')[0].checked = false;
    if (document.frmToDo.cmpContactId) {
        _("corporateDeatils").innerHTML = "";
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

        var url = 'includes/php/ajax/ajax_companyComboList.php?compLst=' + 'yes';
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
                eleAjaxLoader.innerHTML = " <img src='ajax-loader.gif' height=15 width=15 /> Loading company list. Please wait...";
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
        spanSuggestion.style.width = "26%";
        eleCompPara.appendChild(spanSuggestion);

        cmbContLst.innerHTML = "<option value='NA'>Enter a Company First</option>";
        cmbContLst.disabled = true;
    }
    companyListDiv.appendChild(eleCompPara);
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
        spanSuggestion.style.width = "26%";
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
var fetchContact = function(contactValue) {
    _("divContactDetails").style.display = "block";
    var contactContainer = _("contactDetailContainer");
    contactContainer.innerHTML = "";
    _("topstuff").style.display = "none";

    var newString = contactValue.split("&");
    contactValue = newString.join("zxtwuqmtz");
    var url = 'includes/php/ajax/ajax_contactDetails.php?keyword=' + contactValue;
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
            contactContainer.innerHTML = xmlhttp.responseText;
            _("companySearchAjax").innerHTML = "";
        }
        else {
            _("companySearchAjax").innerHTML = "<img src='ajax-search-loader.gif' width='16' height='16' />";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
};
function setText(searchValue, searchId, onChangeFunction) {
    if (searchValue === "no") {
        _('companySuggested').innerHTML = "";
        document.getElementsByName('txtCompSearchBox')[0].value = "";
    }
    else {
        document.getElementsByName('txtCompSearchBox')[0].value = searchValue;
        _('companySuggested').innerHTML = "";
    }
    if (onChangeFunction === 'search') {
        fetchContact(searchValue);
        _('companySuggested').style.height = "";
        _('companySuggested').style.overflowY = "";
        _('companySuggested').style.border = "";
    }
    else {
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

        if (onChangeFunction === "Reminders") {
            selectContact(searchId);
        }
        else if (onChangeFunction === "view") {
            fetchContactListByComp(searchId, 'checkbox', 'contact');
        }
        else if (onChangeFunction === "new") {
            //alert(document.getElementsByName("txtCompSearchBox")[1].value);
        }
        else if (onChangeFunction === "details by office") {
            fetchOfficeList(searchId, 'checkbox');
            //fetchContactListByComp(document.getElementsByName('txtCompSearchBox')[0].value, 'radio', 'activity');
        } else if (onChangeFunction === "activities by contact") {
            fetchContactListByComp(searchId, 'radio', 'activity');
        }
    }
}

function autopopulate(word, onChangeFunction) {
    var suggstDiv = _('companySuggested');
    if (word.value) {
        var keyword = word.value;
        var newString = keyword.split("&");
        keyword = newString.join("zxtwuqmtz");
        var url = 'includes/php/ajax/ajax_retriveCompanySuggest.php?callFunc=' + onChangeFunction + '&keyword=' + keyword;
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
                if (onChangeFunction === 'search') {
                    _('companySuggested').style.height = "300px";
                    _('companySuggested').style.overflowY = "auto";
                    _('companySuggested').style.border = "1px solid black";
                }
            }
            else {
                _("companySearchAjax").innerHTML = "<img src='ajax-search-loader.gif' width='16' height='16' />";
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
        if (_("divContactSearch")) {
            _("divContactDetails").style.display = "none";
            _("topstuff").style.display = "block";
        }
        if (onChangeFunction === 'search') {
            _('companySuggested').style.height = "";
            _('companySuggested').style.overflowY = "";
            _('companySuggested').style.border = "";
        }
    }
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
        xmlhttp.open('GET', 'includes/php/ajax/ajax_contactComboList.php?companyId=' + cid, true);
        xmlhttp.send();
    }
}

function todoRefreshList(frmName, sortBy) {
    if (frmName === 'ViewList') {
        var condition = "";
        var uid = _("hiddenEmplId").value;
        var member;

        if (sortBy !== 'no') {
            if (sortBy === 'call') {
                condition += " AND todo.todo_type='Telephone / Conversation' ";
            } else if (sortBy === 'meeting') {
                condition += " AND todo.todo_type='Meeting' ";
            } else if (sortBy === 'mail') {
                condition += " AND todo.todo_type='Email' ";
            }
        }
        if (_("rdoSelf").checked === true) {
            member = "Self";
            condition += " AND todo.empl_id = " + uid + " ";
        }
        if (_("rdoOther").checked === true) {
            member = "Other";
            condition += " AND todo.empl_id != " + uid + " ";
        }
        if (_("chkOpen").checked === true) {
            condition += " AND (todo.todo_status = 'open' ";
        }
        if (_("chkClose").checked === true) {
            if (_("chkOpen").checked === true)
                condition += " OR ";
            else
                condition += " AND (";
            condition += " todo.todo_status = 'closed' ";
        }
        if (_("chkCancel").checked === true) {
            if (_("chkOpen").checked === true || _("chkClose").checked === true)
                condition += " OR ";
            else
                condition += " AND ";
            condition += " todo.todo_status = 'cancelled' ";
        }
        if (_("chkCancel").checked !== true && (_("chkClose").checked === true || _("chkOpen").checked === true)) {
            condition += ")";
        }

        if (_("chkCancel").checked === true && (_("chkClose").checked === true && _("chkOpen").checked === true)) {
            condition += ")";
        }
        else if (_("chkCancel").checked === true && (_("chkClose").checked === true || _("chkOpen").checked === true)) {
            condition += ")";
        }

        if (_("companyLst")) {
            //txtCompSearchBox          
            if (document.getElementsByName("txtCompSearchBox")[0]) {
                //alert("debesh");
                if (document.getElementsByName("txtCompSearchBox")[0].value === "") {
                    _("companyLst").value = "";
                }
            }
            if (_("companyLst").value !== "NA" && _("companyLst").value !== "") {
                var companyId = _("companyLst").value;
                //alert(companyId);
                condition += " AND cmp.comp_id = " + companyId + " ";
            }
        }
        if (_("contactLst").value !== "NA") {
            var contactId = _("contactLst").value;
            condition += " AND cnt.cont_id = " + contactId + " ";
        }
        var url = 'includes/php/ajax/ajax_todo_CheckViewPagination.php?dynamic=' + condition + "&member=" + member + '&frmName=' + frmName + '&sortBy=' + sortBy;
    }
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
            var rspText = xmlhttp.responseText;
            _("paginationTable").innerHTML = rspText;
        }
        else {
            _("paginationTable").innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading Reminder List. Please Wait...</span></p></div>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}

function todoPaginationMaker(page, frmName) {
    var url = 'includes/php/ajax/ajax_todo_pagination.php?page_num=' + page + '&frmName=' + frmName;
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

function displayFullString(todoDetail, idHeader) {
    var todoDisplayId = idHeader + todoDetail;
    var todoDetailEle = _(todoDisplayId);
    var styleValue = todoDetailEle.getAttribute("style");
    if (styleValue === "display: none;")
        todoDetailEle.style.display = "inline";
    else
        todoDetailEle.style.display = "none";
}

function displayOnlyByType(type) {
    var url = 'includes/php/ajax/ajax_todoView.php?type=' + type;
    var reminderTable = _("reminderTable");
    reminderTable.innerHTML = "";
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
            reminderTable.innerHTML = xmlhttp.responseText;
            todoRefreshClosingDateList();
        }
        else {
            reminderTable.innerHTML = "<div style='top: 0; left: 0;width: 100%; height:100%; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='50' width='50'></p></div>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}

function displayReminderByDate(ele) {
    var date = ele.value;
    if (date === 'NA')
        return;
    var url = 'includes/php/ajax/ajax_todoView.php?date=' + date;
    var reminderTable = _("reminderTable");
    reminderTable.innerHTML = "";
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
            reminderTable.innerHTML = xmlhttp.responseText;
        }
        else {
            reminderTable.innerHTML = "<div style='top: 0; left: 0;width: 100%; height:100%; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='50' width='50'></p></div>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}

function todoRefreshClosingDateList() {
    var cmbDate = document.getElementsByName("cmbRemindClosingDate")[0];
    cmbDate.innerHTML = "";
    var xmlhttp;
    var url = 'includes/php/ajax/ajax_todoRefreshClosingDateList.php';
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
            cmbDate.innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}