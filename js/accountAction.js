/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function _(e1) {
    return document.getElementById(e1);
}

function fetchOfficeDetail(officeList) {
    var contentArea = _("officeDetailContainer");
    var compElement = document.getElementsByName("txtCompSearchBox")[0];
    var companyName;
    if (compElement.hasOwnProperty("options"))
        companyName = compElement.options[compElement.selectedIndex].text;
    else
        companyName = compElement.value;
    var postData = "task=officeDetail&companyName=" + companyName;


    for (var i = 0; i < officeList.length; i++) {
        if (officeList[i].checked == true) {
            postData += "&officeList[]=" + officeList[i].value;
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
            if (contentArea)
                contentArea.innerHTML = xmlhttp.responseText;
        }
        else {
            contentArea.innerHTML = "<tr><td colspan='8'>Fetching office details... Please Wait...</td></tr>";
        }
    }
    xmlhttp.open('POST', 'includes/php/ajax/ajax_Account.php', true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(postData);
}

function fetchActivityDetailsByCompany(compId) {
    var contentArea = _("activityDetailContainer");
    var compElement = document.getElementsByName("txtCompSearchBox")[0];
    var companyName;
    if (compElement.hasOwnProperty("options"))
        companyName = compElement.options[compElement.selectedIndex].text;
    else
        companyName = compElement.value;
    //alert(companyName);
    var startDate = _("inputField").value;
    var endDate = _("inputField1").value;
    var postData = "endDate=" + endDate + "&startDate=" + startDate + "&companyName=" + companyName + "&task=activityDetail&compId=" + compId;
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
        } else {
            contentArea.innerHTML = "<tr><td colspan='10'>Loading Reports... Please Wait...</td></tr>";
        }
    }
    xmlhttp.open('POST', 'includes/php/ajax/ajax_Account.php', true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(postData);

}

function fetchToDoDetailsByCompany(compId) {
    var contentArea = _("toDoDetailContainer");
    var compElement = document.getElementsByName("txtCompSearchBox")[0];
    var companyName;
    if (compElement.hasOwnProperty("options"))
        var companyName = compElement.options[compElement.selectedIndex].text;
    else
        var companyName = compElement.value;
    var startDate = _("inputField").value;
    var endDate = _("inputField1").value;
    var postData = "endDate=" + endDate + "&startDate=" + startDate + "&companyName=" + companyName + "&task=toDoDetail&compId=" + compId;
    //alert(postData);
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
        } else {
            contentArea.innerHTML = "<tr><td colspan='10'>Loading Schedule Reports... Please Wait...</td></tr>";
        }
    }
    xmlhttp.open('POST', 'includes/php/ajax/ajax_Account.php', true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(postData);

}
