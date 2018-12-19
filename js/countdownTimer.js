function _(e1){
    return document.getElementById(e1);
}

// global variables
var secondsRemaining;

function startCountDown() {
    secondsRemaining = 5 * 60;
    setInterval(tick, 1000);
}

function tick() {
    // grab the h5
    var timeDisplay = _("time");

    // turn seconds into mm:ss
    var min = Math.floor(secondsRemaining / 60);
    var sec = secondsRemaining - (min * 60);

    // add a leading zero (as a string value( if secnds less than 10
    if (sec < 10) {
        sec = "0" + sec;
    }
    // concatenate with colon
    var message = min + ":" + sec;
    // now chang the dispaly
    timeDisplay.innerHTML = message;

    // refresh the event and notice merquee board if down to zero
    if (secondsRemaining === 0) {
        secondsRemaining = 5 * 60;
        displayPrevScreen("merqueeNoticeBoard");
    }
    // subtract from seconds remaining
    secondsRemaining--;
    
    saveEventTime();
    
    var today = new Date();
    var loginDate = _("currDate").value;    
    var loginDay = parseInt(loginDate);
    if (today.getDate() !== loginDay) {
        _("currDate").value = today.getDate();        
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
                xmlhttp.responseText;                
            }
        };
        //alert('includes/php/ajax/ajax_contactComboList.php?type='+tagType+'&companyId=' + compId);
        xmlhttp.open('GET', 'includes/php/ajax/ajax_logoutTimer.php?infoType=changeDate', true);
        xmlhttp.send();
    }
}


function saveEventTime()
{    
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
            xmlhttp.responseText;            
        }
    };
    //alert('includes/php/ajax/ajax_contactComboList.php?type='+tagType+'&companyId=' + compId);
    xmlhttp.open('GET', 'includes/php/ajax/ajax_logoutTimer.php', true);
    xmlhttp.send();
}

function confirmCloseCrm() {
    saveEventTime();
    return "You have attempted to Logout from this CRM Application.";
}
        