function _(e1) {
    return document.getElementById(e1);
}

function travelPlanPaginationMaker(page) {
    var url = 'includes/php/ajax/ajax_travelPlan_pagination.php?page_num=' + page;
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
            _("paginationTable").innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading Travel Plan List. Please Wait...</span></p></div>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
}

var saveTravelPlan = function() { // , 
    var msgDiv = _("todoSuccessMsg");
    var ajaxLoader = _("ajaxLoaderProcessing");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");
    var startDate = _('txtStartDate').value;
    var endDate = _('txtEndDate').value;
    var destination = _('cmbDestinationCity').value;
    var desc = _('txtAreaDesc').value;
    var startTime = _('cmbStartTime').value;
    var endTime = _('cmbEndTime').value;
    if (startDate === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select a From Date.";
        msgDiv.appendChild(msgSpan);
        _('txtStartDate').focus();
        return;
    }
    if (endDate === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select a To Date.";
        msgDiv.appendChild(msgSpan);
        _('txtEndDate').focus();
        return;
    }
    if (destination === "NA") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please choose a destination.";
        msgDiv.appendChild(msgSpan);
        _('cmbDestinationCity').focus();
        return;
    }
    if (desc.indexOf('\'') >= 0) {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please remove the single quote(') character from the description.";
        msgDiv.appendChild(msgSpan);
        _('txtAreaDesc').focus();
        return;
    }

//check whether the starting date is inside other travel plan date
    var rspText;
    var xmlhttp;
    var checkDateUrl = 'includes/php/ajax/ajax_checkTravelDate.php?startDate=' + startDate + '&endDate=' + endDate + '&destination=' + destination + '&startTime=' + startTime + '&endTime=' + endTime;
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
            console.log(rspText);            
            if (rspText === "Range Between") {
                alert("You have already a travel plan between " + startDate + " and " + endDate + ". Please choose another Date range.");
                _('txtStartDate').focus();
                return;
            }
            else if (rspText === "Start Date") {
                //alert("You have already a travel plan associated with this Start date " + startDate + ". Please choose another Start Date.");
                alert("A travel plan already existing during the selected period.\nPlease recheck start and end time.");
                _('txtStartDate').focus();
                return;
            }
            else if (rspText === "End Date") {
//                alert("You have already a travel plan associated with this End date " + endDate + ". Please choose another End Date.");
                alert("A travel plan already existing during the selected period.\nPlease recheck start and end time.");
                _('txtEndDate').focus();
                return;
            }
            else if (rspText.length > 20) {
                if (!confirm(rspText)) {
                    //_('txtStartDate').value = "";
                    //_('txtEndDate').value = "";                    
                    //_('cmbDestinationCity').selectedIndex = "NA";
                    //_('txtAreaDesc').value = "";
                    _('txtStartDate').focus();
                    return;
                }
            }
            //check whether the starting date is inside other travel plan date
            var xmlhttp1;
            var rspText1;

            //if doesn't found any date between the starting date and ending date then proceed to save data    
            var url = "startDate=" + startDate + "&endDate=" + endDate + "&destination=" + destination + "&description=" + desc + '&startTime=' + startTime + '&endTime=' + endTime;
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
                    ajaxLoader.innerHTML = "";
                    ajaxLoader.style.top = "";
                    ajaxLoader.style.left = "";
                    ajaxLoader.style.width = "";
                    ajaxLoader.style.height = "";
                    ajaxLoader.style.position = "";
                    ajaxLoader.style.display = "";
                    ajaxLoader.style.backgroundColor = "";
                    ajaxLoader.style.zIndex = "";
                    rspText1 = xmlhttp1.responseText;
                    //alert(rspText1);
                    //console.log(rspText1);
                    if (rspText1 === "Wrong Start Date") {
                        msgSpan.setAttribute("class", "notification n-error");
                        msgSpan.innerHTML = "Start Date cannot be an earlier date than today.";
                        msgDiv.appendChild(msgSpan);
                        _('txtStartDate').focus();
                        return;
                    }
                    else if (rspText1 === "Wrong Date") {
                        msgSpan.setAttribute("class", "notification n-error");
                        msgSpan.innerHTML = "The end time cannot be an earlier time than the start time";
                        msgDiv.appendChild(msgSpan);
                        _('txtEndDate').focus();
                        return;
                    }
                    else if (rspText1 === "Success") {
                        msgSpan.setAttribute("class", "notification n-success");
                        msgSpan.innerHTML = "Travel Plan added successfully.";
//                        _('txtStartDate').value = "";
//                        _('txtEndDate').value = "";
//                        _('cmbDestinationCity').selectedIndex = "NA";
//                        _('txtAreaDesc').value = "";
                        _("frmTravelPlan").reset();
                        refreshTravelPlan();
                    }
                    else {
                        msgSpan.setAttribute("class", "notification n-error");
                        msgSpan.innerHTML = "Error while adding Travel Plan.";
                        _('txtStartDate').value = "";
                        _('txtEndDate').value = "";
                        _('cmbDestinationCity').selectedIndex = "NA";
                        _('txtAreaDesc').value = "";
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
                    ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Saving your Travel Plan...</span></p>";
                }
            };
            xmlhttp1.open('POST', 'includes/php/ajax/ajax_AddTravelPlan.php', true);
            xmlhttp1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp1.send(url);
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
            ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Checking for Date Availability...</span></p>";
        }
    };
    xmlhttp.open('GET', checkDateUrl, true);
    xmlhttp.send();
};

var travelRowEdit = function(row) {
    _('txtStartDate').value = row[1];
    _('txtEndDate').value = row[2];
    _('cmbDestinationCity').value = row[3];
    _('txtAreaDesc').value = row[4];
    _('hidTravelId').value = row[0];
    _('cmbStartTime').value = row[5];    
    _('cmbEndTime').value = row[6];    
    var fldBtn = _("fldButton");
    fldBtn.innerHTML = "";
    fldBtn.innerHTML = "<input class='submit-green' type='button' onclick='updateTravelPlan();' name='btnTodoUpdate' id='btnTodoUpdate' value='Update' />";
};

var travelRowDelete = function(travelId) {
    _('txtStartDate').value = "";
    _('txtEndDate').value = "";
    _('cmbDestinationCity').selectedIndex = "NA";
    _('txtAreaDesc').value = "";
    if (_('btnTodoUpdate')) {
        var fldBtn = _("fldButton");
        fldBtn.innerHTML = "";
        fldBtn.innerHTML = "<input class='submit-green' type='button' onclick='saveTravelPlan();' name='btnTodoSubmit' value='Add' /> <input class='submit-gray' type='reset' name='btnTodoClear' value='Clear' />";
    }
    if (!confirm("Are you sure to Delete the travel Plan?"))
        return;
    else {
        var rspText;
        var xmlhttp;
        var msgDiv = _("todoSuccessMsg");
        var ajaxLoader = _("ajaxLoaderProcessing");
        msgDiv.innerHTML = "";
        var msgSpan = document.createElement("span");
        var xmlhttp;
        var url = 'includes/php/ajax/ajax_deleteTravelPlan.php?travelId=' + travelId;
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
                    msgSpan.innerHTML = "Travel Plan deleted successfully.";
                    refreshTravelPlan();
                }
                else {
                    msgSpan.setAttribute("class", "notification n-error");
                    msgSpan.innerHTML = "Error while deleting travel plan.";
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
                ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Deleting Travel Plan...</span></p>";
            }
        };
        xmlhttp.open('GET', url, true);
        xmlhttp.send();
    }
};

/* var updateTravelPlan = function() {
 var rspText;
 var xmlhttp;
 var msgDiv = _("todoSuccessMsg");
 var ajaxLoader = _("ajaxLoaderProcessing");
 msgDiv.innerHTML = "";
 var msgSpan = document.createElement("span");
 var startDate = _('txtStartDate').value;
 var endDate = _('txtEndDate').value;
 var destination = _('cmbDestinationCity').value;
 var desc = _('txtAreaDesc').value;
 var travelId = _('hidTravelId').value;
 if (startDate === "") {
 msgSpan.setAttribute("class", "notification n-error");
 msgSpan.innerHTML = "Please select a From Date.";
 msgDiv.appendChild(msgSpan);
 _('txtStartDate').focus();
 return;
 }
 if (endDate === "") {
 msgSpan.setAttribute("class", "notification n-error");
 msgSpan.innerHTML = "Please select a To Date.";
 msgDiv.appendChild(msgSpan);
 _('txtEndDate').focus();
 return;
 }
 if (destination === "NA") {
 msgSpan.setAttribute("class", "notification n-error");
 msgSpan.innerHTML = "Please choose a destination.";
 msgDiv.appendChild(msgSpan);
 _('cmbDestinationCity').focus();
 return;
 }
 if (desc.indexOf('\'') >= 0) {
 msgSpan.setAttribute("class", "notification n-error");
 msgSpan.innerHTML = "Please remove the single quote(') character from the description.";
 msgDiv.appendChild(msgSpan);
 _('txtAreaDesc').focus();
 return;
 }
 var url = "startDate=" + startDate + "&endDate=" + endDate + "&destination=" + destination + "&description=" + desc + '&travelId=' + travelId;    
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
 if (rspText === "Wrong Start Date") {
 msgSpan.setAttribute("class", "notification n-error");
 msgSpan.innerHTML = "You cannot update the travel plan to an earlier Starting Date.";
 msgDiv.appendChild(msgSpan);
 _('txtStartDate').focus();
 return;
 } else if (rspText === "Wrong Date") {
 msgSpan.setAttribute("class", "notification n-error");
 msgSpan.innerHTML = "You cannot update the travel plan to an earlier Ending Date";
 msgDiv.appendChild(msgSpan);
 _('txtEndDate').focus();
 return;
 }
 else if (rspText === "Success") {
 msgSpan.setAttribute("class", "notification n-success");
 msgSpan.innerHTML = "Travel Plan updated successfully.";
 _('txtStartDate').value = "";
 _('txtEndDate').value = "";
 _('cmbDestinationCity').selectedIndex = "NA";
 _('txtAreaDesc').value = "";
 var fldBtn = _("fldButton");
 fldBtn.innerHTML = "";
 fldBtn.innerHTML = "<input class='submit-green' type='button' onclick='saveTravelPlan();' name='btnTodoSubmit' value='Add' /> <input class='submit-gray' type='reset' name='btnTodoClear' value='Clear' />";
 refreshTravelPlan();
 }
 else {
 msgSpan.setAttribute("class", "notification n-error");
 msgSpan.innerHTML = "No updates done.";
 _('txtStartDate').value = "";
 _('txtEndDate').value = "";
 _('cmbDestinationCity').selectedIndex = "NA";
 _('txtAreaDesc').value = "";
 var fldBtn = _("fldButton");
 fldBtn.innerHTML = "";
 fldBtn.innerHTML = "<input class='submit-green' type='button' onclick='saveTravelPlan();' name='btnTodoSubmit' value='Add' /> <input class='submit-gray' type='reset' name='btnTodoClear' value='Clear' />";
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
 ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Updating your Travel Plan...</span></p>";
 }
 };
 xmlhttp.open('POST', 'includes/php/ajax/ajax_UpdateTravelPlan.php', true);
 xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
 xmlhttp.send(url);
 }; */

var updateTravelPlan = function() {
    var msgDiv = _("todoSuccessMsg");
    var ajaxLoader = _("ajaxLoaderProcessing");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");
    var startDate = _('txtStartDate').value;
    var endDate = _('txtEndDate').value;
    var destination = _('cmbDestinationCity').value;
    var desc = _('txtAreaDesc').value;
    var travelId = _('hidTravelId').value;
    var startTime = _('cmbStartTime').value;
    var endTime = _('cmbEndTime').value;

    if (startDate === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select a From Date.";
        msgDiv.appendChild(msgSpan);
        _('txtStartDate').focus();
        return;
    }
    if (endDate === "") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select a To Date.";
        msgDiv.appendChild(msgSpan);
        _('txtEndDate').focus();
        return;
    }
    if (destination === "NA") {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please choose a destination.";
        msgDiv.appendChild(msgSpan);
        _('cmbDestinationCity').focus();
        return;
    }
    if (desc.indexOf('\'') >= 0) {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please remove the single quote(') character from the description.";
        msgDiv.appendChild(msgSpan);
        _('txtAreaDesc').focus();
        return;
    }

//check whether the starting date is inside other travel plan date
    var rspText;
    var xmlhttp;
    var checkDateUrl = 'includes/php/ajax/ajax_checkTravelDate.php?startDate=' + startDate + '&endDate=' + endDate + '&destination=' + destination + '&travelId=' + travelId + '&startTime=' + startTime + '&endTime=' + endTime;
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
            if (rspText === "Range Between") {
                alert("You have already a travel plan between " + startDate + " and " + endDate + ". Please choose another Date range.");
                _('txtStartDate').focus();
                return;
            }
            else if (rspText === "Start Date") {
                alert("You have already a travel plan associated with this Start date " + startDate + ". Please choose another Start Date.");
                _('txtStartDate').focus();
                return;
            }
            else if (rspText === "End Date") {
                alert("You have already a travel plan associated with this End date " + endDate + ". Please choose another End Date.");
                _('txtEndDate').focus();
                return;
            }
            else if (rspText.length > 20) {
                if (!confirm(rspText)) {
                    //_('txtStartDate').value = "";
                    //_('txtEndDate').value = "";                    
                    //_('cmbDestinationCity').selectedIndex = "NA";
                    //_('txtAreaDesc').value = "";
                    _('txtStartDate').focus();
                    return;
                }
            }
            //check whether the starting date is inside other travel plan date
            var xmlhttp1;
            var rspText1;

            //if doesn't found any date between the starting date and ending date then proceed to save data    
            var url = "startDate=" + startDate + "&endDate=" + endDate + "&destination=" + destination + "&description=" + desc + '&travelId=' + travelId + '&startTime=' + startTime + '&endTime=' + endTime;

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
                    ajaxLoader.innerHTML = "";
                    ajaxLoader.style.top = "";
                    ajaxLoader.style.left = "";
                    ajaxLoader.style.width = "";
                    ajaxLoader.style.height = "";
                    ajaxLoader.style.position = "";
                    ajaxLoader.style.display = "";
                    ajaxLoader.style.backgroundColor = "";
                    ajaxLoader.style.zIndex = "";
                    rspText1 = xmlhttp1.responseText;
                    //alert(rspText1);
                    //console.log(rspText1);
                    if (rspText1 === "Wrong Start Date") {
                        msgSpan.setAttribute("class", "notification n-error");
                        msgSpan.innerHTML = "You cannot update the travel plan to an earlier Starting Date.";
                        msgDiv.appendChild(msgSpan);
                        _('txtStartDate').focus();
                        return;
                    }
                    else if (rspText1 === "Wrong Date") {
                        msgSpan.setAttribute("class", "notification n-error");
                        msgSpan.innerHTML = "The end time cannot be an earlier time than the start time";
                        msgDiv.appendChild(msgSpan);
                        _('txtEndDate').focus();
                        return;
                    }
                    else if (rspText1 === "Success") {
                        msgSpan.setAttribute("class", "notification n-success");
                        msgSpan.innerHTML = "Travel Plan updated successfully.";
                        _('txtStartDate').value = "";
                        _('txtEndDate').value = "";
                        _('cmbDestinationCity').selectedIndex = "NA";
                        _('txtAreaDesc').value = "";
                        var fldBtn = _("fldButton");
                        fldBtn.innerHTML = "";
                        fldBtn.innerHTML = "<input class='submit-green' type='button' onclick='saveTravelPlan();' name='btnTodoSubmit' value='Add' /> <input class='submit-gray' type='reset' name='btnTodoClear' value='Clear' />";
                        refreshTravelPlan();
                    }
                    else {
                        msgSpan.setAttribute("class", "notification n-error");
                        msgSpan.innerHTML = "No updates done.";
                        _('txtStartDate').value = "";
                        _('txtEndDate').value = "";
                        _('cmbDestinationCity').selectedIndex = "NA";
                        _('txtAreaDesc').value = "";
                        var fldBtn = _("fldButton");
                        fldBtn.innerHTML = "";
                        fldBtn.innerHTML = "<input class='submit-green' type='button' onclick='saveTravelPlan();' name='btnTodoSubmit' value='Add' /> <input class='submit-gray' type='reset' name='btnTodoClear' value='Clear' />";
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
                    ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Saving your Travel Plan...</span></p>";
                }
            };
            xmlhttp1.open('POST', 'includes/php/ajax/ajax_UpdateTravelPlan.php', true);
            xmlhttp1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp1.send(url);
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
            ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Checking for Date Availability...</span></p>";
        }
    };
    xmlhttp.open('GET', checkDateUrl, true);
    xmlhttp.send();
};

var refreshTravelPlan = function() {
    var xmlhttp;
    var url = 'includes/php/ajax/ajax_refreshTravelPlanLst.php?permission=' + 'yes';
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
        }
        else {
            _("paginationTable").innerHTML = "<div style='top: 0; left: 0;width: 100%; height:300px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading Travel Plan List. Please Wait...</span></p></div>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
};

