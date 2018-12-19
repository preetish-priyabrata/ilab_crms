function _(e1){
    return document.getElementById(e1);
}

function fetchActivityDetail(contactList, fetchType) {
    var contentArea = _("activityDetailContainer");
    var compElement = document.getElementsByName("txtCompSearchBox")[0];
    
    if (fetchType == 'cont_id') {
        var companyName;
        if (compElement.hasOwnProperty("options"))
            companyName = compElement.options[compElement.selectedIndex].text;
        else
            companyName = compElement.value;
        var postData = "companyName=" + companyName + "&task=activityDetail&by=" + fetchType;
    } else {
        var postData = "task=activityDetail&by=" + fetchType;
    }


    for (var i = 0; i < contactList.length; i++) {
        if (contactList[i].checked == true) {
            postData += "&contactId=" + contactList[i].value;
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
        }else{
            contentArea.innerHTML = "<tr><td colspan='7'>Fetching Activity Report... Please Wait...</td></tr>";
        }
    }
    //alert(postData);
    xmlhttp.open('POST', 'includes/php/ajax/ajax_Activity.php', true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(postData);
}
