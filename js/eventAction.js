function _(e1){
    return document.getElementById(e1);
}

var displayNoticeEvent = function(eventId, divId, body) {
    var xmlhttp;
    var url = 'includes/php/ajax/ajax_displayEvent.php?eventId=' + eventId + '&divId=' + divId + '&moduleBody=' + body;
    var type = "";
    if (divId === "normalNoticeBoard") {
        type = "Notice";
    }
    else {
        type = "Event";
    }
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
            _(divId).innerHTML = "";
            _(divId).innerHTML = xmlhttp.responseText;
        }
        else {
            _(body).innerHTML = "<div style='top: 0; left: 0;width: 100%; height:100%; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading " + type + ". Please Wait...</span></p></div>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
};

//this function is called when the user click on the Exit button of the Merquee Event window
var displayPrevScreen = function(divId) {
    var type = '';
    var body = '';
    if (divId === "normalNoticeBoard") {
        type = "Notice";
        body = "normalNoticeBoardBody";
    }
    else {
        type = "Event";
        body = "normalEventBoardBody";
    }
    if(divId === "merqueeNoticeBoard")
        var body = '';
        
    var xmlhttp;
    var url = 'includes/php/ajax/ajax_displayPrevScreen.php?divId=' + divId;
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
            _(divId).innerHTML = "";
            _(divId).innerHTML = xmlhttp.responseText;
        }
        else {
            if (body === "normalNoticeBoardBody" || body === "normalEventBoardBody") {                
                _(body).innerHTML = "<div style='top: 0; left: 0;width: 100%; height:100%; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading " + type + " List. Please Wait...</span></p></div>";
            }
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
};

var changeEventStatus = function(eventId, eventStatus, eventType, msgDivId, tblId) {
    var rspText;
    var msgDiv = _(msgDivId);
    var ajaxLoader = _("ajaxLoaderProcessing");
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");
    var xmlhttp;
    var url = 'includes/php/ajax/ajax_updateStatusEvent.php?eventId=' + eventId + "&eventStatus=" + eventStatus;
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
                msgSpan.innerHTML = "Status updated successfully.";
                refreshEventTable(eventType, msgDivId, tblId);
            }
            else {
                msgSpan.setAttribute("class", "notification n-error");
                msgSpan.innerHTML = "Error while updating status";
                refreshEventTable(eventType, msgDivId, tblId);
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
            ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Changing " + eventType + " status...</span></p>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
};

var removeEvent = function(eventId, eventType, msgDivId, tblId) {
    var ajaxLoader = _("ajaxLoaderProcessing");
    if (!confirm("Are you sure to delete the selected " + eventType + "?")) {
        return;
    }
    else {
        var rspText;
        var msgDiv = _(msgDivId);
        msgDiv.innerHTML = "";
        var msgSpan = document.createElement("span");
        var xmlhttp;
        var url = 'includes/php/ajax/ajax_deleteEvent.php?eventId=' + eventId;
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
                    msgSpan.innerHTML = eventType + " was deleted successfully.";
                    refreshEventTable(eventType, msgDivId, tblId);
                }
                else {
                    msgSpan.setAttribute("class", "notification n-error");
                    msgSpan.innerHTML = "Error while deleting" + eventType;
                    refreshEventTable(eventType, msgDivId, tblId);
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
                ajaxLoader.innerHTML = "<p style='text-align: center;display: table-cell;vertical-align: middle;z-index:1001'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;color: #000000;'> Deleting " + eventType + " status...</span></p>";
            }
        };
        xmlhttp.open('GET', url, true);
        xmlhttp.send();
    }
};

var refreshEventTable = function(eventType, msgDivId, tblId) {
    var xmlhttp;
    var url = 'includes/php/ajax/ajax_refreshEventTable.php?eventType=' + eventType + "&msgDivId=" + msgDivId + "&tblId=" + tblId;
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
            _(tblId).innerHTML = xmlhttp.responseText;
        }
        else {
            _(tblId).innerHTML = "<tr><td colspan='5'><div style='top: 0; left: 0;width: 100%; height:100%; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='15' width='15'><span style='font-size: 100%;'> Loading " + eventType + " List. Please Wait...</span></p></div></td></tr>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
};

var deleteSelectedEvent = function(eventType, msgDivId, tblId) {
    var rspText = "";
    var msgDiv = _(msgDivId);
    msgDiv.innerHTML = "";
    var msgSpan = document.createElement("span");
    var frmName = '';
    if (eventType === 'Notice') {
        frmName = 'frmNotice';
    }
    else if (eventType === 'Event') {
        frmName = 'frmEvent';
    }
    //var eleLength = document.getElementsByName(frmName)[0].elements.length;
    //alert(eleLength);
    var selectedValues = "";
    var coutnSelected = 0;
    for (var i = 0, eleLength = document.getElementsByName(frmName)[0].elements.length; i < eleLength; i++)
    {
        if (document.getElementsByName(frmName)[0].elements[i].type === "checkbox") {
            if (document.getElementsByName(frmName)[0].elements[i].checked === true) {
                selectedValues = selectedValues + document.getElementsByName(frmName)[0].elements[i].value + ",";
                coutnSelected = i + 1;
            }
        }
    }
    //alert(selectedValues.length);
    if (selectedValues.length < 1) {
        msgSpan.setAttribute("class", "notification n-error");
        msgSpan.innerHTML = "Please select the " + eventType + " you wish to delete.";
    }
    else {
        if (confirm("Are you sure you want to delete the selected " + coutnSelected + " " + eventType + "s?")) {
            selectedValues = selectedValues.substring(0, selectedValues.length - 1);
            //alert(selectedValues);
            var xmlhttp;
            var url = 'includes/php/ajax/ajax_deleteSelectedEvent.php?selectedEventId=' + selectedValues;
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
                    rspText = xmlhttp.responseText;
                    if (rspText === "Success") {
                        msgSpan.setAttribute("class", "notification n-success");
                        msgSpan.innerHTML = coutnSelected + " " + eventType + "s were deleted successfully.";
                        refreshEventTable(eventType, msgDivId, tblId);
                    }
                    else {
                        msgSpan.setAttribute("class", "notification n-error");
                        msgSpan.innerHTML = "Error while deleting selected " + coutnSelected + " " + eventType;
                        refreshEventTable(eventType, msgDivId, tblId);
                    }
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