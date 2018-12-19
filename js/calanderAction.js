function _(e1){
    return document.getElementById(e1);
}

var changeCalendar = function(buttonName, month, year) {
    var contentArea = _('mainContent');
    var empId = _("cmbEmpLst").value;
    //alert(empId);
    var xmlhttp;
    var url = '';
    if (buttonName === 'thisButton') {
        url = 'includes/php/ajax/ajax_Calendar.php?cntAction=' + 'calendar' + "&empId=" + empId;
    }
    else if (buttonName === 'goButton') {
        var month = _("cmbMonth").value;
        var year = _("cmbYear").value;
        url = 'includes/php/ajax/ajax_Calendar.php?cntAction=' + 'calendar' + '&month=' + month + '&year=' + year + "&empId=" + empId;
    }
    else if (buttonName === 'prevButton') {
        var firstYear = _("hidFirstYear").value;
        if (month === 1) {
            if (year == firstYear) { //double equal to operator is required here
                alert("No Calendar available.");
                return;
            }
            else {
                month = 12;
                year--;
            }
        }
        else {
            month--;
        }
        url = 'includes/php/ajax/ajax_Calendar.php?cntAction=' + 'calendar' + '&month=' + month + '&year=' + year + "&empId=" + empId;
    }
    else if (buttonName === 'nextButton') {
        var lastYear = _("hidLastYear").value;
        if (month === 12) {
            if (year == lastYear) { //double equal to operator is required here
                alert("No Calander available.");
                return;
            }
            else {
                month = 1;
                year++;
            }
        }
        else {
            month++;
        }
        url = 'includes/php/ajax/ajax_Calendar.php?cntAction=' + 'calendar' + '&month=' + month + '&year=' + year + "&empId=" + empId;
    }
    contentArea.innerHTML = "";
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
            contentArea.innerHTML = xmlhttp.responseText;
        }
        else {
            contentArea.innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;'> Loading Calendar. Please Wait...</span></p></div>";
        }
    };
    xmlhttp.open('GET', url, true);
    xmlhttp.send();
};
