function _(e1) {
    return document.getElementById(e1);
}

function deleteContact(contId) {
    if (confirm('Do you want to Delete this Contact?') === false) {
        return false;
    }
    else {
        var url = 'includes/php/ajax/ajax_deleteContact.php?contId=' + contId + '&permission=yes';
        var msgDiv = _("contactMessageDisplay");
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
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
            {
                var rspText = xmlhttp.responseText;
                if (rspText === "success") {
                    msgDiv.innerHTML = "<span class='notification n-success'>Contact deleted successfully.</span>";
                }
                else {
                    msgDiv.innerHTML = "<span class='notification n-error'>Error while deleting contact. Please try again.</span>";
                }
                if (document.getElementsByName('txtCompSearchBox').length === 2)
                    fetchContactListByComp(document.getElementsByName('txtCompSearchBox')[1].value, 'checkbox', 'contact');
                else
                    fetchContactListByComp(document.getElementsByName('txtCompSearchBox')[0].value, 'checkbox', 'contact');
            }
            else {
                _("myTable").innerHTML("<tr><td><img src='ajax-loader.gif' height='15'><span style='font-size: 100%;color: #000000;'> Please wait while deleting the contact...</span></td></tr>");
            }
        };
        xmlhttp.open('GET', url, true);
        xmlhttp.send();
    }
}
function displayContactDetails(contId, companyName, compId) {
    contentSelector("contact", "off");
    displayContent("contact_view0", contId, companyName, compId);
}

function displayContactOnViewTab(contId, companyName, compId) {
    //alert(companyName);    
    document.getElementsByName("txtCompSearchBox")[0].value = companyName;
    fetchSingleContact(compId, contId);
}
function fetchSingleContact(compId, contId) {
    //alert(infoType);
    var contentArea = _("contactContainer");

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
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            contentArea.innerHTML = xmlhttp.responseText;
            _("contactDetailContainer").innerHTML = "";
            fetchContactDetail(document.getElementsByName('contactList'));
        }
    }
    //alert('includes/php/ajax/ajax_contactComboList.php?type='+tagType+'&companyId=' + compId);
    xmlhttp.open('GET', 'includes/php/ajax/ajax_contactComboList.php?infoType=contact&type=checkbox&companyId=' + compId + '&contId=' + contId, true);
    xmlhttp.send();
}
function displayMyCompanyList(checkStatus) {
    if (checkStatus.checked) {
        url = 'includes/php/ajax/ajax_companyListSelection.php?type=myCompany';
    }
    else {
        url = 'includes/php/ajax/ajax_companyListSelection.php?type=view';
    }
    var url;
    var content = _("divCompanyList");

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
            content.innerHTML = xmlhttp.responseText;
        } else {
            content.innerHTML = " <img src='ajax-loader.gif' height=15 width=15 /> Loading company list. Please wait...";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}
function fetchContactListByComp(compId, tagType, infoType) {
    //alert(infoType);
    var contentArea = _("contactContainer");
    var url = "";
    var myContactLst = _("myContLst");
    if (myContactLst) {
        if (myContactLst.checked === true) {
            url = 'includes/php/ajax/ajax_contactComboList.php?infoType=' + infoType + '&type=' + tagType + '&companyId=' + compId + '&myContact=' + 'yes';
        }
        else {
            url = 'includes/php/ajax/ajax_contactComboList.php?infoType=' + infoType + '&type=' + tagType + '&companyId=' + compId;
        }
    }
    else {
        url = 'includes/php/ajax/ajax_contactComboList.php?infoType=' + infoType + '&type=' + tagType + '&companyId=' + compId;
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
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            if (tagType == 'checkbox' || tagType == 'radio')
                contentArea.innerHTML = xmlhttp.responseText;
            else
                contentArea.innerHTML = "<select class='input-long' name='contact_list' >" + xmlhttp.responseText + "</select>";
            if (infoType == "contact") {
                _("contactDetailContainer").innerHTML = "";
                fetchContactDetail(document.getElementsByName('contactList'));
            }
            else
                fetchActivityDetail(document.getElementsByName('contactList'), 'cont_id');
            //_("companyLst").value = "";
        }
    }
    //alert('includes/php/ajax/ajax_contactComboList.php?type='+tagType+'&companyId=' + compId);    
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}
function myContactDisplay() {
    if (document.getElementsByName('txtCompSearchBox').length === 2)
        fetchContactListByComp(document.getElementsByName('txtCompSearchBox')[1].value, 'checkbox', 'contact');
    else
        fetchContactListByComp(document.getElementsByName('txtCompSearchBox')[0].value, 'checkbox', 'contact');
}
function displayPackage(streamCount) {
    var streamId = "txtPackage" + streamCount;
    var streamChk = "stream" + streamCount;
    if (_(streamChk).checked === false) {
        _(streamId).value = "";
        _(streamId).style.display = "none";
    }
    else
        _(streamId).style.display = "block";
}
function fetchOfficeList(compId, tagType) {
    //alert(compId);
    var contentArea = _("officeListContainer");
    if (compId == "NA") {
        contentArea.innerHTML = "Please select a company first";
        //document.getElementsByName('offi_selector')[0].selectedIndex=0;
        //displayOfficeForm("New");
        return;
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
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var data = xmlhttp.responseText;
            contentArea.innerHTML = xmlhttp.responseText;
            if (tagType == "checkbox" || tagType == "radio") {
                _("officeDetailContainer").innerHTML = "";
                fetchOfficeDetail(document.getElementsByName('offi_list'));
            }
        }
    }
    xmlhttp.open('GET', 'includes/php/ajax/ajax_officeComboList.php?tagType=' + tagType + '&compId=' + compId, true);
    xmlhttp.send();
}

function displayOfficeForm(type) {
    //alert(type);
    if (type == "New") {
        _('newOffice').style.display = 'block';
        _('oldOffice').style.display = 'none';
        _('officeListContainer').innerHTML = "";
        _('officeDetailContainer').innerHTML = "";
    } else {
        if (document.getElementsByName('txtCompSearchBox').length == 2)
            fetchOfficeList(document.getElementsByName('txtCompSearchBox')[1].value, "select");
        else
            fetchOfficeList(document.getElementsByName('txtCompSearchBox')[0].value, "select");
        _('newOffice').style.display = 'none';
        _('oldOffice').style.display = 'block';
    }
}

function fetchCityList(country) {
    //alert(compId);
    //alert(country);
    var contentArea = _("cityListContainer");
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
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var data = xmlhttp.responseText;
            if (data == "No City Available")
                contentArea.innerHTML = "Please Contact Admin to add the City to the Database";
            else
                contentArea.innerHTML = "<select class='input-long' name='offi_city'>" + data + "</select>";
        }
    }
    xmlhttp.open('GET', 'includes/php/ajax/ajax_cityComboList.php?country=' + country, true);
    xmlhttp.send();
}

function saveContactDetails() {
    var msgDiv = _("contactMessageDisplay");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");

    var form = _("addNewContactForm");
    /*
     var contactType = form.contact_type.value;
     if (contactType == "Additional Contact")
     var postData = "type=" + form.add_relation.value;
     else
     var postData = "type=" + contactType;
     */
    var postData = "type=Regular";
    if (form.cont_name.value == "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Name of the Contact cannot be left empty";
        msgDiv.appendChild(msgSpan);
        return;
    }
    if (form.cont_dept.value == "NA") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Department of the Contact cannot be left empty";
        msgDiv.appendChild(msgSpan);
        return;
    }
    var contactName = form.salute.value + " " + form.cont_name.value;
    postData += "&contactName=" + contactName;
    var email = form.cont_email.value;
    postData += "&email=" + email;
    var mobile = form.cont_mobile.value;
    postData += "&mobile=" + mobile;
    var direct = form.cont_direct.value;
    postData += "&direct=" + direct;
    var ext = form.cont_ext.value;
    postData += "&ext=" + ext;
    var desg = form.cont_desg.value;
    postData += "&desg=" + desg;
    var dept = form.cont_dept.value;
    postData += "&dept=" + dept;
    var status = form.cont_active.value;
    //alert(status);
    postData += "&status=" + status;

    var companyId;
    if (document.getElementsByName('txtCompSearchBox').length == 2)
        companyId = document.getElementsByName('txtCompSearchBox')[1].value;
    else
        companyId = document.getElementsByName('txtCompSearchBox')[0].value;
    //alert(companyId);
    postData += "&companyId=" + companyId;

    if (companyId == "NA" || companyId == "" || companyId == "no") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select a Company";
        msgDiv.appendChild(msgSpan);
        return;
    }
    var officeSelector = form.offi_selector.value;
    postData += "&officeSelector=" + officeSelector;
    if (officeSelector == "Existing") {
        var existingOffice = form.offi_list.value;
        postData += "&existingOffice=" + existingOffice;
        if (existingOffice == "NA") {
            msgSpan.setAttribute("class", "notification n-error");
            msgSpan.innerHTML = "Please enter a Valid Office Address";
            msgDiv.appendChild(msgSpan);
            return;
        }
    }
    else {
        var officeType = form.offi_type.value;
        postData += "&officeType=" + officeType;
        var officeBoard = form.offi_board.value;
        postData += "&officeBoard=" + officeBoard;
        if (officeBoard == "") {
            msgSpan.setAttribute("class", "notification n-error");
            msgSpan.innerHTML = "Please enter a Board Line Number of the Office";
            msgDiv.appendChild(msgSpan);
            return;
        }
        var officeAddress = form.offi_add1.value + " " + form.offi_add2.value;
        postData += "&officeAddress=" + officeAddress;
        if (officeAddress == " ") {
            msgSpan.setAttribute("class", "notification n-error");
            msgSpan.innerHTML = "Please enter the Address of the Office";
            msgDiv.appendChild(msgSpan);
            return;
        }
        var officeCountry = form.offi_country.value;
        postData += "&officeCountry=" + officeCountry;
        if (officeCountry == "Select") {
            msgSpan.setAttribute("class", "notification n-error");
            msgSpan.innerHTML = "Please select the country of the location of the Office";
            msgDiv.appendChild(msgSpan);
            return;
        }
        var city = form.offi_city.value;
        postData += "&city=" + city;
        var pin = form.offi_pin.value;
        postData += "&pin=" + pin;
        var recStatus = form.rec_status.checked;
        postData += "&recStatus=" + recStatus;
    }
    //Conclave of the contact 
    var conclave = _("cont_conclave").value;
    postData += "&conclave=" + conclave;
    //var stream = form.stream.value;
    var stream = document.getElementsByName("stream");
    var packageAmt = document.getElementsByName("txtPackage");
    var packageAmount = 0;
    //alert(stream);
    var errFlag = 0;
    for (var i = 0; i < stream.length; i++) {
        if (stream[i].checked == true) {
            postData += "&stream[]=" + stream[i].value;
            if (packageAmt[i].value == "")
                packageAmount = 0;
            else
                packageAmount = packageAmt[i].value;
            postData += "&package[]=" + packageAmount;
            errFlag = 1;
        }
    }
    if (errFlag == 0) {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select atleast one Stream, regarding which the discussions are being carried out";
        msgDiv.appendChild(msgSpan);
        return;
    }
    var contactStatus = form.cont_status.value;
    if (contactStatus == "NA") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select the status of the contact.";
        msgDiv.appendChild(msgSpan);
        return;
    } else {
        var statusCommentSave = "NA";
        if (contactStatus.indexOf("Positive") >= 0 || contactStatus.indexOf("Negetive") >= 0) {
            var statusCommentNegative = form.statusComment.value;
            var statusCommentPositive = form.visitingMonth.value + "," + form.visitingYear.value;
            //alert(statusCommentNegative);
            //alert(statusCommentPositive);
            if (statusCommentNegative == "" && statusCommentPositive.indexOf("NA , NA") >= 0) {
                msgSpan.setAttribute("class", "notification n-error");
                msgSpan.innerHTML = "Please enter the proper comments in the field provided.<br /> For positive status, select the expected time of visit.<br /> For Negative status, provide a summarized reason";
                msgDiv.appendChild(msgSpan);
                return;
            } else {
                statusCommentSave = (statusCommentPositive == "NA,NA") ? statusCommentNegative : statusCommentPositive;
            }
        }
        contactStatus = contactStatus.replace('+', '%2B');
        postData += "&contactStatus=" + contactStatus;
        postData += "&statusComment=" + statusCommentSave;
        //alert(postData);
    }
    var rspText;
    var xmlhttp;
    var msgDiv = _("contactMessageDisplay");

    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");

    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    }
    else
    {
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            rspText = xmlhttp.responseText;
            //console.log(rspText);
            rspText = rspText.split(",");

            if (rspText[0] == "Success") {
                var noPackageTxt = document.getElementsByName("txtPackage").length;
                for (var i = 0; i < noPackageTxt; i++) {
                    document.getElementsByName("txtPackage")[i].style.display = "none";
                }
                _("statusComment").style.display = "none";
                _("positiveLabel").style.display = "none";
                _("visitingMonth").style.display = "none";
                _("visitingYear").style.display = "none";
                form.reset();
                _("oldOffice").style.display = "none";
                _("newOffice").style.display = "block";
                _("divCompanyList").innerHTML = "";
                _("divCompanyList").innerHTML = "<p id='txtCmpPara'><label id='comanyLabel'>Company</label><input type='text' name='txtCompSearchBox' class='input-short' placeholder='Search company here' onkeyup=" + "\"autopopulate(this,\'new\')\"" + "style='width: 70%;' autofocus><span id='companySearchAjax'></span><br /><span id='companySuggested' style='z-index: 1; position: absolute; width: 26%;'></span></p>";
                //document.getElementsByName("txtCompSearchBox")[0].focus();     

                //success message
                msgSpan.setAttribute("class", "notification n-success");
                msgSpan.innerHTML = contactName + " has been sucessfully added to the Database.";
                var msgSpanInfo = document.createElement("span");
                msgSpanInfo.setAttribute("class", "notification n-information");
                msgSpanInfo.innerHTML = '<a id="contactToDo" href="includes/php/ajax/ajax_AddToDoList.php?include=style&contId=' + rspText[1] + '" onclick="return displayModalBox(this.id);">Add a Reminder</a> for ' + contactName;
                msgSpanInfo.innerHTML += '<br /><a  id="contactActivity" href="includes/php/ajax/ajax_addActivity.php?contId=' + rspText[1] + '" onclick="return displayModalBox(this.id);"> Add an Activity wrt </a> ' + contactName;
            }
            else {
                msgSpan.setAttribute("class", "notification n-error");
                msgSpan.innerHTML = "Problem saving information. Please check if compulsory fields have been missed out";
                //document.forms[0].reset();
            }
            msgDiv.appendChild(msgSpan);
            msgDiv.appendChild(msgSpanInfo);
        }
    }
    xmlhttp.open('POST', 'includes/php/ajax/ajax_Contact.php', true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var url = "task=Save&" + postData;
    xmlhttp.send(url);
}

function fetchContactDetail(contactList) {
    var contentArea = _("contactDetailContainer");
    var compElement = document.getElementsByName("txtCompSearchBox")[0];
    if (compElement.hasOwnProperty("options"))
        var companyName = compElement.options[compElement.selectedIndex].text;
    else
        var companyName = compElement.value;
    var postData = "companyName=" + companyName + "&task=contactDetail";

    for (var i = 0; i < contactList.length; i++) {
        if (contactList[i].checked == true) {
            postData += "&contactList[]=" + contactList[i].value;
        }
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
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            contentArea.innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open('POST', 'includes/php/ajax/ajax_Contact.php', true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    //alert(postData);
    xmlhttp.send(postData);
}

/*
 function saveFunctionalHeadDetails() {
 var msgDiv = _("contactMessageDisplay");
 msgDiv.innerHTML = "";
 var msgSpan = document.createElement("span");
 
 var form = _("addNewContactForm");
 var postData = "type=Functional Head";
 //alert("Mayank");
 //personal details
 if (form.cont_name.value == "") {
 msgSpan.setAttribute("class", "notification n-error");
 msgSpan.innerHTML = "Name of the Contact cannot be left empty";
 msgDiv.appendChild(msgSpan);
 return;
 }
 if (form.cont_dept.value == "NA") {
 msgSpan.setAttribute("class", "notification n-error");
 msgSpan.innerHTML = "Department of the Contact cannot be left empty";
 msgDiv.appendChild(msgSpan);
 return;
 }
 var contactName = form.salute.value + " " + form.cont_name.value;
 postData += "&contactName=" + contactName;
 var email = form.cont_email.value;
 postData += "&email=" + email;
 var mobile = form.cont_mobile.value;
 postData += "&mobile=" + mobile;
 var direct = form.cont_direct.value;
 postData += "&direct=" + direct;
 var ext = form.cont_ext.value;
 postData += "&ext=" + ext;
 var desg = form.cont_desg.value;
 postData += "&desg=" + desg;
 var dept = form.cont_dept.value;
 postData += "&dept=" + dept;
 var status = form.cont_active.value;
 postData += "&status=" + status;
 
 
 var companyId = document.getElementsByName('txtCompSearchBox')[0].value;
 postData += "&companyId=" + companyId;
 //alert(companyId);
 if (companyId == "NA") {
 msgSpan.setAttribute("class", "notification n-error");
 msgSpan.innerHTML = "Please select a Company";
 msgDiv.appendChild(msgSpan);
 return;
 }
 var officeSelector = form.offi_selector.value;
 postData += "&officeSelector=" + officeSelector;
 if (officeSelector == "Existing") {
 var existingOffice = form.offi_list.value;
 postData += "&existingOffice=" + existingOffice;
 if (existingOffice == "NA") {
 msgSpan.setAttribute("class", "notification n-error");
 msgSpan.innerHTML = "Please enter a Valid Office Address";
 msgDiv.appendChild(msgSpan);
 return;
 }
 }
 else {
 var officeType = form.offi_type.value;
 postData += "&officeType=" + officeType;
 var officeBoard = form.offi_board.value;
 postData += "&officeBoard=" + officeBoard;
 if (officeBoard == "") {
 msgSpan.setAttribute("class", "notification n-error");
 msgSpan.innerHTML = "Please enter a Board Line Number of the Office";
 msgDiv.appendChild(msgSpan);
 return;
 }
 var officeAddress = form.offi_add1.value + " " + form.offi_add2.value;
 postData += "&officeAddress=" + officeAddress;
 if (officeAddress == " ") {
 msgSpan.setAttribute("class", "notification n-error");
 msgSpan.innerHTML = "Please enter the Address of the Office";
 msgDiv.appendChild(msgSpan);
 return;
 }
 var officeCountry = form.offi_country.value;
 postData += "&officeCountry=" + officeCountry;
 if (officeCountry == "Select") {
 msgSpan.setAttribute("class", "notification n-error");
 msgSpan.innerHTML = "Please select the country of the location of the Office";
 msgDiv.appendChild(msgSpan);
 return;
 }
 var city = form.offi_city.value;
 postData += "&city=" + city;
 var recStatus = form.rec_status.checked;
 postData += "&recStatus=" + recStatus;
 }
 //var stream = form.stream.value;
 var stream = document.getElementsByName("stream");
 //alert(stream);
 var errFlag = 0;
 for (var i = 0; i < stream.length; i++) {
 if (stream[i].checked == true) {
 postData += "&stream[]=" + stream[i].value;
 errFlag = 1;
 }
 }
 
 var contactStatus = form.cont_status.value;
 postData += "&contactStatus=" + contactStatus;
 
 var rspText;
 var xmlhttp;
 var msgDiv = _("contactMessageDisplay");
 
 msgDiv.innerHTML = "";
 var msgSpan = document.createElement("span");
 
 if (window.XMLHttpRequest) {
 xmlhttp = new XMLHttpRequest();
 }
 else
 {
 xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
 }
 xmlhttp.onreadystatechange = function()
 {
 if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
 {
 rspText = xmlhttp.responseText;
 rspText = rspText.split(",");
 //document.write(rspText);
 
 if (rspText[0] == "Success") {
 msgSpan.setAttribute("class", "notification n-success");
 msgSpan.innerHTML = contactName + " has been sucessfully added to the Database.";
 document.forms[0].reset();
 var msgSpanInfo = document.createElement("span");
 msgSpanInfo.setAttribute("class", "notification n-information");
 msgSpanInfo.innerHTML = '<a id="contactToDo" href="includes/php/ajax/ajax_AddToDoList.php?include=style&contId=' + rspText[1] + '" onclick="return displayModalBox(this.id);">Add a Reminder</a> for ' + contactName;
 msgSpanInfo.innerHTML += '<br /><a  id="contactActivity" href="includes/php/ajax/ajax_addActivity.php?contId=' + rspText[1] + '" onclick="return displayModalBox(this.id);"> Add an Activity wrt </a> ' + contactName;
 }
 else {
 msgSpan.setAttribute("class", "notification n-error");
 msgSpan.innerHTML = "Problem saving information. Please check if compulsory fields have been missed out";
 document.forms[0].reset();
 }
 
 msgDiv.appendChild(msgSpan);
 msgDiv.appendChild(msgSpanInfo);
 }
 }
 xmlhttp.open('POST', 'includes/php/ajax/ajax_Contact.php', true);
 xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
 var url = "task=Save&" + postData;
 //alert(url);
 xmlhttp.send(url);
 
 
 }
 */

function displayAdditionalContact(type) {
    if (type == "Additional Contact") {
        _("additionalContactRelation").style.display = "block";
        contentArea = document.getElementsByName("add_relation")[0];
        //alert(contentArea.innerHTML);
        if (contentArea.innerHTML == "") {
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
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
                {
                    contentArea.innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open('GET', 'includes/php/ajax/ajax_relationComboList.php', true);
            xmlhttp.send();
        }
    }
    else {
        _("additionalContactRelation").style.display = "none";
    }
}

function displaySelOfficeDetail(officeId) {
    var contentArea = _("officeDetailContainer");
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
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            contentArea.innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open('GET', 'includes/php/ajax/ajax_officeComboList.php?task=officeDetail&officeId=' + officeId, true);
    xmlhttp.send();
}

function selectAllItems(selectAllId, eleToBeSelected) {
    selectAllEle = _(selectAllId);
    if (selectAllEle.checked == true) {
        for (var i = 0; i < eleToBeSelected.length; i++) {
            eleToBeSelected[i].checked = true;
        }
    } else if (selectAllEle.checked == false) {
        for (var i = 0; i < eleToBeSelected.length; i++) {
            eleToBeSelected[i].checked = false;
        }
    }

}

function deSelectAllEle(selectAllId, checked) {
    if (checked == false)
        _(selectAllId).checked = false;
}

function displayCommentFormByStatus(statusType) {
    var form = (_("addNewContactForm") == null) ? _("editContactForm") : _("addNewContactForm");
    if (statusType.indexOf("Positive") >= 0) {
        form.statusComment.style.display = "none";
        _("positiveLabel").style.display = "inline";
        form.visitingMonth.style.display = "inline";
        form.visitingYear.style.display = "inline";
    } else if (statusType.indexOf("Negetive") >= 0) {
        form.statusComment.style.display = "inline";
        form.visitingMonth.style.display = "none";
        form.visitingYear.style.display = "none";
        _("positiveLabel").style.display = "none";
    } else {
        form.statusComment.style.display = "none";
        form.visitingMonth.style.display = "none";
        form.visitingYear.style.display = "none";
        _("positiveLabel").style.display = "none";
    }
}

function displayAddToDoForm(task, contactId) {
    //alert(task);
    //alert(contactId);
    var formContainer = _("addToDoContainer")
    if (task == 'Yes') {
        var xmlhttp;
        formContainer.style.display = "block";
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        }
        else
        {
            xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
        }
        xmlhttp.onreadystatechange = function()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                formContainer.innerHTML = xmlhttp.responseText;
                displayDatePicker();
            }
        }
        xmlhttp.open('GET', 'ajax_AddToDoList.php?contId=' + contactId, true);
        xmlhttp.send();
    } else {
        formContainer.style.display = "none";
    }

}

function displayChangeStatusForm(value) {
    if (value == "Yes")
        _("changeStatusForm").style.display = "inline";
    else
        _("changeStatusForm").style.display = "none";
}

var displayDeleteDisapproveReason = function(editId) {
    var reasonSectionId = "comment-span-" + editId;
    if (_(reasonSectionId).style.display === "none") {
        _(reasonSectionId).style.display = "block";
    } else {
        _(reasonSectionId).style.display = "none";
    }
    if (_(reasonSectionId).className === 'delete-status') {
        var url = 'includes/php/ajax/ajax_delete_disapproved_edit.php?editId=' + editId;
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
                var rspText = xmlhttp.responseText;
                if (rspText === "Success") {
                    _(reasonSectionId).className = '';
                }
            }
        };
        xmlhttp.open('GET', url, true);
        xmlhttp.send();
    }
};