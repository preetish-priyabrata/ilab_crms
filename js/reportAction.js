function _(e1) {
    return document.getElementById(e1);
}

var displayContactReportCombo = function(fieldName) {
    _("emplCmbPara").style.display = "none";
    _("CompCmbPara").style.display = "none";
    _("streamCmbPara").style.display = "none";
    _("companyStreamCmbPara").style.display = "none";
    _("eventPara").style.display = "none";
    _("report-div").innerHTML = "";

    if (fieldName === "employee") {
        _("emplCmbPara").style.display = "block";
    } else if (fieldName === "company") {
        _("CompCmbPara").style.display = "block";
    } else if (fieldName === "stream") {
        _("streamCmbPara").style.display = "block";
    } else if (fieldName === "streamCompany") {
        _("companyStreamCmbPara").style.display = "block";
    }else {
        _("eventPara").style.display = "block";
    }
};

var displayContactDetailsByStream = function(stream) {
    if (stream == 'NA') {
        _('divContactDetails').innerHTML = '';
    }
    var companyId = _('cmbCompanyLst').value;
    var url = 'includes/php/ajax/ajax_getContactDetailsByStream.php?compId=' + companyId + '&streamName=' + stream;
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
            _("divContactDetails").innerHTML = xmlhttp.responseText;
        }
        else {
            _("divContactDetails").innerHTML = "<img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading Contact List. Please Wait...</span>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
};

var displayContactDetailsReport = function(categoryId, categoryName) {
    var reportId = _("report-div");
    if (categoryId === 'NA') {
        reportId.innerHTML = "";
    } else {
        reportId.innerHTML = "";
        var url = 'includes/php/ajax/ajax_getCompanyListByEmpl.php?categoryId=' + categoryId + '&categoryName=' + categoryName + '&companyId=' + categoryName;
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
                reportId.innerHTML = xmlhttp.responseText;
            }
            else {
                reportId.innerHTML = "<img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading Company list and corresponding Contact List. Please Wait...</span>";
            }
        };
        xmlhttp.open('GET', url, true);
        xmlhttp.send();
    }
};

var displayContactDetailsByCompany = function(companyId, emplId) {
    _("contactDetailContainer").innerHTML = "";
    var url;
    if (typeof emplId === "undefined") {
        url = 'includes/php/ajax/ajax_getContactDetailsByCompany.php?compId=' + companyId;
    }
    else {
        url = 'includes/php/ajax/ajax_getContactDetailsByCompany.php?compId=' + companyId + '&emplId=' + emplId;
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
            _("contactDetailContainer").innerHTML = xmlhttp.responseText;
        }
        else {
            _("contactDetailContainer").innerHTML = "<img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading Contact List. Please Wait...</span>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
};
function clearCompanyStatusForm() {
    if (_("statusYear")) {
        _("datePicker").innerHTML = "";
    }
    if (document.getElementsByName("chkStream[]")[0]) {
        _("streamLst").innerHTML = "";
    }
    _("companyStatusTable").innerHTML = "";
    _("notificationMsg").innerHTML = "";
    _("btnReportSubmit").style.display = "none";
}
function checkVisitingStatus(status) {
    _("companyStatusTable").innerHTML = "";
    _("btnReportSubmit").style.display = "none";
    if (status.indexOf("Positive") >= 0) {
        var yearList = "<option>Select a Year</option>";
        for (var i = 2013; i < 2020; i++) {
            yearList += "<option>" + i + "</option>";
        }
        var monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"];
        var monthList = "<option>Select a Month</option>";
        for (var i = 0; i < 12; i++) {
            monthList += "<option>" + monthNames[i] + "</option>";
        }
        _("datePicker").innerHTML = "<label>Select the probable Month of visit</label><select id='statusMonth' name='month'>" + monthList + "</select>&nbsp;&nbsp;&nbsp;<select id='statusYear' name='year'>" + yearList + "</select>";
        var url = 'includes/php/ajax/ajax_getStreamList.php';
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
                _("streamLst").innerHTML = xmlhttp.responseText;
            }
            else {
                _("streamLst").innerHTML = "<img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading Stream List. Please Wait...</span>";
            }
        };
        xmlhttp.open('GET', url, true);
        xmlhttp.send();
    }
    else {
        _("datePicker").innerHTML = "";
        _("streamLst").innerHTML = "";
    }
}
function generateCompanyStatusReport() {
    var msgDiv = _("notificationMsg");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");
    var status = _("status_list").value;
    if (status === "NA") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select a status.";
        msgDiv.appendChild(msgSpan);
        return false;
    }
    status = status.replace('+', '%2B');
    var url = "";
    if (_("statusYear")) {
        var year = _("statusYear").value;
        var month = _("statusMonth").value;
        if (year === "Select a Year" && month === "Select a Month") {
            msgSpan.setAttribute("class", "notification n-error");
            msgSpan.innerHTML = "Please select a correct month and year.";
            msgDiv.appendChild(msgSpan);
        }
        url = 'status=' + status + '&year=' + year + '&month=' + month;
    }
    else {
        url = 'status=' + status;
    }
    if (document.getElementsByName("chkStream[]")[0]) {
        var streamCount = document.getElementsByName("chkStream[]").length;
        var streamFlag = 0;
        var streamValue = '';
        var eleStream = '';
        for (var i = 0; i < streamCount; i++) {
            eleStream = document.getElementsByName("chkStream[]")[i];
            if (eleStream.checked) {
                streamFlag++;
                streamValue += "&stream[]=" + eleStream.value;
            }
        }
        if (streamFlag === 0) {
            msgSpan.setAttribute("class", "notification n-error");
            msgSpan.innerHTML = "Please select at least one stream.";
            msgDiv.appendChild(msgSpan);
            return false;
        }
        else {
            url += streamValue;
        }
    }
    //alert(url);
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
            _("companyStatusTable").style.height = "250px";
            _("companyStatusTable").innerHTML = xmlhttp.responseText;
            _("btnReportSubmit").style.display = "block";
        }
        else {
            _("companyStatusTable").innerHTML = "<img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading Company List. Please Wait...</span>";
        }
    };
    xmlhttp.open('GET', "includes/php/ajax/ajax_companyStatus.php?" + url, true);
    xmlhttp.send();
}
var generateTravelReport = function() {
    var msgDiv = _("adminSuccessMsg");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");
    var empCombo = document.getElementsByName("cmbEmpLst")[0];
    var empId = empCombo.value;
    if (empId === "NA") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select an Employee.";
        msgDiv.appendChild(msgSpan);
        document.getElementsByName("cmbEmpLst")[0].focus();
        return false;
    }
    var empName = empCombo.options[empCombo.selectedIndex].text;
    var startDate = document.getElementsByName("startDate")[0].value;
    if (startDate === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select a Start Date.";
        msgDiv.appendChild(msgSpan);
        document.getElementsByName("startDate")[0].focus();
        return false;
    }
    var endDate = document.getElementsByName("endDate")[0].value;
    if (endDate === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select an End Date.";
        msgDiv.appendChild(msgSpan);
        document.getElementsByName("endDate")[0].focus();
        return false;
    }
    var startTime = _('cmbStartTime').value;
    var endTime = _('cmbEndTime').value;
    var url = 'includes/php/ajax/ajax_empTravelReport.php?empId=' + empId + '&startDate=' + startDate + '&endDate=' + endDate + '&name=' + empName + '&startTime=' + startTime + '&endTime=' + endTime;

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
            //alert(rspText);
            if (rspText === "wrong date") {
                msgSpan.setAttribute("class", "notification n-error");
                msgSpan.innerHTML = "End date should be greater than Start Date .";
                msgDiv.appendChild(msgSpan);
                _("paginationTable").innerHTML = "";
            }
            else {
                _("paginationTable").innerHTML = rspText;
            }
        }
        else {
            _("paginationTable").innerHTML = "<img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading Travel Report. Please Wait...</span>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}
var generateEmplReport = function() {
    var msgDiv = _("adminSuccessMsg");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");
    var empCombo = document.getElementsByName("cmbEmpLst")[0];
    var empId = empCombo.value;
    if (empId === "NA") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select an Employee.";
        msgDiv.appendChild(msgSpan);
        document.getElementsByName("cmbEmpLst")[0].focus();
        return false;
    }
    var empName = empCombo.options[empCombo.selectedIndex].text;
    var startDate = document.getElementsByName("startDate")[0].value;
    if (startDate === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select a Start Date.";
        msgDiv.appendChild(msgSpan);
        document.getElementsByName("startDate")[0].focus();
        return false;
    }
    var endDate = document.getElementsByName("endDate")[0].value;
    if (endDate === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select an End Date.";
        msgDiv.appendChild(msgSpan);
        document.getElementsByName("endDate")[0].focus();
        return false;
    }
    var url = 'includes/php/ajax/ajax_empPerfReport.php?empId=' + empId + '&startDate=' + startDate + '&endDate=' + endDate + '&name=' + empName;
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
            if (rspText === "wrong date") {
                msgSpan.setAttribute("class", "notification n-error");
                msgSpan.innerHTML = "End date should be greater than Start Date .";
                msgDiv.appendChild(msgSpan);
                _("paginationTable").innerHTML = "";
            }
            else {
                _("paginationTable").innerHTML = rspText;
            }
        }
        else {
            _("paginationTable").innerHTML = "<img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading Employee Performance Report. Please Wait...</span>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}
function clearEmplReportForm() {
    document.getElementsByName("cmbEmpLst")[0].selectedIndex = 0;
    _("inputField").value = "";
    _("inputField1").value = "";
}
