function _(e1){
    return document.getElementById(e1);
}

var contentSelector = function(category, autoDisplay) {
    //var categoryName = category.name;    
    var categoryName = category;
    var actLst;

    _('contentHeading').innerHTML = "";
    _('mainContent').innerHTML = "";

    var menuLst = ["contact", "toDoList", "report", "calendar", "admin", "event"];

    if (categoryName === "toDoList")
        actLst = ["New", "View"];
    // else if (categoryName === "account")
    //   actLst = ["Details By Office", "Activity Details", "Scheduled Items"];
    //else if (categoryName === "contact")
    //    actLst = ["New", "View", "Functional Heads"];
    else if (categoryName === "contact")
        actLst = ["View", "New", "Contact Search","Contact Edit Status"];
    //else if (categoryName === "activity")
    //    actLst = ["Activities by Contact", "Activities by Member"];
    else if (categoryName === "admin")
        actLst = ["Company", "Company Type", "Office Type", "Contact Status", "Contact Relationship", "Department", "Salutation", "College List", "Stream", "District", "Manage Reminder", "Manage User", "Contact Visibility", "Swapping Contact", "Travel Destination","Edit Approval"];
    else if (categoryName === "event")
        actLst = ["View", "New", "Delete"];
    else if (categoryName === "report") {
        if (_("empType")) {
            actLst = ["Details By Office", "Activities By Company", "Activities by Contact", "Activities by Member", "Scheduled Items", "Probable Visits", "Employee Performance", "Travel Report","Contact Report"];
        }
        else {
            actLst = ["Details By Office", "Activities By Company", "Activities by Contact", "Activities by Member", "Scheduled Items", "Probable Visits","Contact Report"];
        }
    }
    else if (categoryName === "calendar") {
        actLst = ["Calendar", "Travel Plan"];
    }

    // code to remove the click event of the currently clicked content          
    var classList = document.getElementsByClassName('dashboard-module');
    for (var ii = 0, len = classList.length; ii < len; ii++) {
        if (classList[ii].name === categoryName) {
            classList[ii].removeAttribute('onclick');
            classList[ii].id = "currentTab";
            classList[ii].style.backgroundPosition = "top right";
        }
        else {
            classList[ii].setAttribute("onclick", "contentSelector('" + menuLst[ii] + "')");
            classList[ii].removeAttribute("id");
            classList[ii].style.backgroundPosition = "bottom left";
        }
    }
    if (typeof autoDisplay !== 'undefined') {
        displayToDoListSubMenu(actLst, categoryName.toLowerCase(), autoDisplay);
    } else {
        displayToDoListSubMenu(actLst, categoryName.toLowerCase());
    }
};

function displayToDoListSubMenu(actions, categoryName, autoDisplay) {
    var actionList = _("nav");
    actionList.innerHTML = "";
    //_("idToDo").removeAttribute("onclick");
    var actionLength = actions.length;      //number of actions a category contain
    var eleList = [];
    var eleAnchor = [];
    var actionName;
    for (var i = 0; i < actionLength; i++) {
        eleList[i] = document.createElement('li');
        eleList[i].setAttribute("class", "cntList");
        actionList.appendChild(eleList[i]);
        eleAnchor[i] = document.createElement('a');
        eleList[i].appendChild(eleAnchor[i]);
        eleAnchor[i].innerHTML = actions[i];
        actionName = actions[i].toLowerCase() + i;
        actionName = categoryName + "_" + actionName;
        if (categoryName === 'event' && i === 1) {
            eleAnchor[i].setAttribute("href", "includes/php/addEvent.php");
            eleAnchor[i].setAttribute("id", actionName);
            eleAnchor[i].setAttribute("onclick", "return displayModalBox(this.id)");
        }
        else {
            eleAnchor[i].setAttribute("href", "javascript:void(0)");
            eleAnchor[i].setAttribute("name", actionName);
            eleAnchor[i].setAttribute("onclick", "displayContent(this.name)");
        }
    }
    /**
     * below code is for displaying the first tab automatically
     */
    if (typeof autoDisplay === 'undefined') {
        if (actions.length > 0) {
            var firstTab = eleAnchor[0].innerHTML;
            firstTab = firstTab.toLowerCase() + "0";
            firstTab = categoryName + "_" + firstTab;
            displayContent(firstTab);
        }
    }
}

function displayContent(name, contId, companyName, compId) {
    saveEventTime();
    var ajaxLoader = _("ajaxLoaderProcessing");
    var contentHeading = _('contentHeading');
    var contentArea = _('mainContent');

    contentHeading.innerHTML = "";
    contentArea.innerHTML = "";

    var contentList = document.getElementsByClassName('cntList');

    var nameString = name.replace(/[0-9]/g, '');    //remove all numeric character from the string
    var contentNo = parseInt(name.replace(/^\D+/g, ''));   //remove all except numberic character

    var contentLength = nameString.length;
    var charUnderScore = nameString.indexOf("_");
    var categoryName = nameString.substr(0, charUnderScore);
    var contentSting = nameString.substring(charUnderScore + 1, contentLength);     //

    for (var i = 0, len = contentList.length; i < len; i++) {
        if (i === contentNo) {
            contentList[i].id = 'current';
        }
        else
            contentList[i].removeAttribute("id");
    }
    document.getElementsByClassName("module-body")[0].style.paddingTop = "";
    if (categoryName === "todolist") {
        if (contentSting === "new") {
            contentHeading.innerHTML = 'Add New Reminder List Items';
            _("titleChanger").innerHTML = "CRM :: Add New Reminder List";
            document.getElementsByClassName("module-body")[0].style.paddingTop = "";
        }
        else if (contentSting === "view") {
            contentHeading.innerHTML = 'View Reminder List Item';
            _("titleChanger").innerHTML = "CRM :: View Reminder List";
            document.getElementsByClassName("module-body")[0].style.paddingTop = "0cm";
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
                ajaxLoader.innerHTML = "";
                ajaxLoader.style.top = "";
                ajaxLoader.style.left = "";
                ajaxLoader.style.width = "";
                ajaxLoader.style.height = "";
                ajaxLoader.style.position = "";
                ajaxLoader.style.display = "";
                ajaxLoader.style.backgroundColor = "";
                ajaxLoader.style.zIndex = "";
                contentArea.innerHTML = xmlhttp.responseText;
                displayDatePicker(); //call the jquery date picker
            }
            else {
                contentArea.innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;'> Loading. Please Wait...</span></p></div>";
                ajaxLoader.style.top = window.pageYOffset + "px";
                ajaxLoader.style.left = window.pageXOffset + "px";
                ajaxLoader.style.width = "100%";
                ajaxLoader.style.height = "100%";
                ajaxLoader.style.position = "absolute";
                ajaxLoader.style.display = "table";
                ajaxLoader.style.backgroundColor = "darkgray";
                ajaxLoader.style.zIndex = "1000";
                ajaxLoader.style.opacity = "0";
                ajaxLoader.style.filter = "alpha(opacity=0)";
            }
        };
        xmlhttp.open('GET', 'includes/php/ajax/ajax_ToDoList.php?cntAction=' + contentSting, true);
        xmlhttp.send();
    }
    else if (categoryName === "admin") {
        //check the admin task and change the header accordingly 
        if (contentSting === "edit approval") {
            contentHeading.innerHTML = 'Manage Edited Contact';
            _("titleChanger").innerHTML = "CRM :: Manage Edited Contact";
        }
        else if (contentSting === "company") {
            contentHeading.innerHTML = 'Manage Company';
            _("titleChanger").innerHTML = "CRM :: Manage Company";
        }
        else if (contentSting === "company type") {
            contentHeading.innerHTML = 'Manage Company Type';
            _("titleChanger").innerHTML = "CRM :: Manage Company Type";
        }
        else if (contentSting === "office type") {
            contentHeading.innerHTML = 'Manage Office Type';
            _("titleChanger").innerHTML = "CRM :: Manage Office Type";
        }
        else if (contentSting === "district") {
            contentHeading.innerHTML = 'Manage District';
            _("titleChanger").innerHTML = "CRM :: Manage District";
        }
        else if (contentSting === "contact status") {
            contentHeading.innerHTML = 'Manage Contact Status';
            _("titleChanger").innerHTML = "CRM :: Manage Contact Status";
        }
        else if (contentSting === "contact relationship") {
            contentHeading.innerHTML = 'Manage Contact Relationship';
            _("titleChanger").innerHTML = "CRM :: Manage Contact Relationship";
        }
        else if (contentSting === "department") {
            contentHeading.innerHTML = 'Manage Department';
            _("titleChanger").innerHTML = "CRM :: Manage Department";
        }
        else if (contentSting === "salutation") {
            contentHeading.innerHTML = 'Manage Salutation';
            _("titleChanger").innerHTML = "CRM :: Manage Salutation";
        }
        else if (contentSting === "college list") {
            contentHeading.innerHTML = 'Manage College List';
            _("titleChanger").innerHTML = "CRM :: Manage College List";
        }
        else if (contentSting === "stream") {
            contentHeading.innerHTML = 'Manage Stream';
            _("titleChanger").innerHTML = "CRM :: Manage Stream";
        }
        else if (contentSting === "manage reminder") {
            contentHeading.innerHTML = 'Cancel/Update Reminder List Item';
            _("titleChanger").innerHTML = "CRM :: Manage Reminder List";
        }
        else if (contentSting === "manage user") {
            contentHeading.innerHTML = 'Manage User';
            _("titleChanger").innerHTML = "CRM :: Manage User";
        }
        else if (contentSting === "contact visibility") {
            contentHeading.innerHTML = 'Manage Contact Visibility';
            _("titleChanger").innerHTML = "CRM :: Manage Contact Visibility";
        }
        else if (contentSting === "swapping contact") {
            contentHeading.innerHTML = 'Swapping of Contact';
            _("titleChanger").innerHTML = "CRM :: Swapping of Contact";
        }
        else if (contentSting === "travel destination") {
            contentHeading.innerHTML = 'Manage Travel Destination';
            _("titleChanger").innerHTML = "CRM :: Manage Travel Destination";
        }
        //change the content section of the specific admin task using ajax section
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
                ajaxLoader.style.top = "";
                ajaxLoader.style.left = "";
                ajaxLoader.style.width = "";
                ajaxLoader.style.height = "";
                ajaxLoader.style.position = "";
                ajaxLoader.style.display = "";
                ajaxLoader.style.backgroundColor = "";
                ajaxLoader.style.zIndex = "";
                contentArea.innerHTML = xmlhttp.responseText;
                displayDatePicker(); //call the jquery date picker
            }
            else {
                contentArea.innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;'> Loading. Please Wait...</span></p></div>";
                ajaxLoader.style.top = window.pageYOffset + "px";
                ajaxLoader.style.left = window.pageXOffset + "px";
                ajaxLoader.style.width = "100%";
                ajaxLoader.style.height = "100%";
                ajaxLoader.style.position = "absolute";
                ajaxLoader.style.display = "table";
                ajaxLoader.style.backgroundColor = "darkgray";
                ajaxLoader.style.zIndex = "1000";
                ajaxLoader.style.opacity = "0";
                ajaxLoader.style.filter = "alpha(opacity=0)";
            }
        };
        xmlhttp.open('GET', 'includes/php/ajax/ajax_Admin.php?cntAction=' + contentSting, true);
        xmlhttp.send();
    }
    else if (categoryName === "contact") {
        if (contentSting === "new") {
            contentHeading.innerHTML = 'Add a new Contact';
            _("titleChanger").innerHTML = "CRM :: Add a new Contact";
        }
        else if (contentSting === "view") {
            contentHeading.innerHTML = 'View a Contact';
            _("titleChanger").innerHTML = "CRM :: View Existing Contacts";
        }
        else if (contentSting === "contact search") {
            //_("mainContent").style.height = "370px";
            contentHeading.innerHTML = 'Contact Search';
            _("titleChanger").innerHTML = "CRM :: Contact Search";
        }else if (contentSting === "contact edit status") {
            //_("mainContent").style.height = "370px";
            contentHeading.innerHTML = 'Contact Edit Status';
            _("titleChanger").innerHTML = "CRM :: Contact Edit Status";
        }
        else if (contentSting === "functional heads") {
            //alert("Mayank");
            contentHeading.innerHTML = 'Add a Functional Head';
            _("titleChanger").innerHTML = "CRM :: Add a Functional Head";
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
                contentArea.innerHTML = xmlhttp.responseText;
                ajaxLoader.innerHTML = "";
                ajaxLoader.style.top = "";
                ajaxLoader.style.left = "";
                ajaxLoader.style.width = "";
                ajaxLoader.style.height = "";
                ajaxLoader.style.position = "";
                ajaxLoader.style.display = "";
                ajaxLoader.style.backgroundColor = "";
                ajaxLoader.style.zIndex = "";
                if (typeof contId !== 'undefined') {
                    displayContactOnViewTab(contId, companyName, compId);
                }
                displayDatePicker();//call the jquery date picker
            }
            else {
                contentArea.innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;'> Loading. Please Wait...</span></p></div>";
                ajaxLoader.style.top = window.pageYOffset + "px";
                ajaxLoader.style.left = window.pageXOffset + "px";
                ajaxLoader.style.width = "100%";
                ajaxLoader.style.height = "100%";
                ajaxLoader.style.position = "absolute";
                ajaxLoader.style.display = "table";
                ajaxLoader.style.backgroundColor = "darkgray";
                ajaxLoader.style.zIndex = "1000";
                ajaxLoader.style.opacity = "0";
                ajaxLoader.style.filter = "alpha(opacity=0)";
            }
        };
        xmlhttp.open('GET', 'includes/php/ajax/ajax_Contact.php?cntAction=' + contentSting, true);
        xmlhttp.send();
    }
    else if (categoryName === "event") {
        if (contentSting === "view") {
            contentHeading.innerHTML = 'View Event/Notice';
            _("titleChanger").innerHTML = "CRM :: View Event/Notice";
        }
        else if (contentSting === "delete") {
            contentHeading.innerHTML = 'Delete Event/Notice';
            _("titleChanger").innerHTML = "CRM :: Delete Event/Notice";
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
                contentArea.innerHTML = xmlhttp.responseText;
                ajaxLoader.innerHTML = "";
                ajaxLoader.style.top = "";
                ajaxLoader.style.left = "";
                ajaxLoader.style.width = "";
                ajaxLoader.style.height = "";
                ajaxLoader.style.position = "";
                ajaxLoader.style.display = "";
                ajaxLoader.style.backgroundColor = "";
                ajaxLoader.style.zIndex = "";
                displayDatePicker(); //call the jquery date picker
            }
            else {
                contentArea.innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;'> Loading. Please Wait...</span></p></div>";
                ajaxLoader.style.top = window.pageYOffset + "px";
                ajaxLoader.style.left = window.pageXOffset + "px";
                ajaxLoader.style.width = "100%";
                ajaxLoader.style.height = "100%";
                ajaxLoader.style.position = "absolute";
                ajaxLoader.style.display = "table";
                ajaxLoader.style.backgroundColor = "darkgray";
                ajaxLoader.style.zIndex = "1000";
                ajaxLoader.style.opacity = "0";
                ajaxLoader.style.filter = "alpha(opacity=0)";
            }
        };
        xmlhttp.open('GET', 'includes/php/ajax/ajax_EventNotice.php?cntAction=' + contentSting, true);
        xmlhttp.send();
    }
    else if (categoryName === "calendar") {
        if (contentSting === "calendar") {
            contentHeading.innerHTML = 'View Calendar';
            _("titleChanger").innerHTML = "CRM :: View Calendar";
        }
        else if (contentSting === "travel plan") {
            contentHeading.innerHTML = 'Add New Travel Plan';
            _("titleChanger").innerHTML = "CRM :: New Travel Plan";
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
//                console.log(rspText);
                contentArea.innerHTML = rspText;
                ajaxLoader.innerHTML = "";
                ajaxLoader.style.top = "";
                ajaxLoader.style.left = "";
                ajaxLoader.style.width = "";
                ajaxLoader.style.height = "";
                ajaxLoader.style.position = "";
                ajaxLoader.style.display = "";
                ajaxLoader.style.backgroundColor = "";
                ajaxLoader.style.zIndex = "";
                displayDatePicker(); //call the jquery date picker
            }
            else {
                contentArea.innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;'> Loading. Please Wait...</span></p></div>";
                ajaxLoader.style.top = window.pageYOffset + "px";
                ajaxLoader.style.left = window.pageXOffset + "px";
                ajaxLoader.style.width = "100%";
                ajaxLoader.style.height = "100%";
                ajaxLoader.style.position = "absolute";
                ajaxLoader.style.display = "table";
                ajaxLoader.style.backgroundColor = "darkgray";
                ajaxLoader.style.zIndex = "1000";
                ajaxLoader.style.opacity = "0";
                ajaxLoader.style.filter = "alpha(opacity=0)";
            }
        };
        xmlhttp.open('GET', 'includes/php/ajax/ajax_Calendar.php?cntAction=' + contentSting, true);
        xmlhttp.send();
    }
    else if (categoryName === "report") {
        var ajaxCallPage = "";
        if (contentSting === "graphical view") {
            contentHeading.innerHTML = 'Graphical Reports';
            _("titleChanger").innerHTML = "CRM :: View Graphical Reports";
            ajaxCallPage = "ajax_Report.php";
        }
        if(contentSting === "contact report"){
            contentHeading.innerHTML = 'Contact Reports';
            _("titleChanger").innerHTML = "CRM :: Only My Contact Reports";
            ajaxCallPage = "ajax_Report.php";
        }
        if (contentSting === "probable visits") {
            contentHeading.innerHTML = 'Graphical Reports';
            _("titleChanger").innerHTML = "CRM :: View Graphical Reports";
            ajaxCallPage = "ajax_Report.php";
        }
        else if (contentSting === "download reports") {
            contentHeading.innerHTML = 'Download Excel Reports';
            _("titleChanger").innerHTML = "CRM :: Excel Reports";
            ajaxCallPage = "ajax_Report.php";
        }
        else if (contentSting === "details by office") {
            contentHeading.innerHTML = 'View Office Details';
            _("titleChanger").innerHTML = "CRM :: Account - Office Details ";
            ajaxCallPage = "ajax_Account.php";
        }
        else if (contentSting === "activities by company") {
            contentHeading.innerHTML = 'View Activities wrt an Account';
            _("titleChanger").innerHTML = "CRM :: View Activities wrt an Account";
            ajaxCallPage = "ajax_Account.php";
        }
        else if (contentSting === "scheduled items") {
            contentHeading.innerHTML = 'View upcoming schedules wrt an Account';
            _("titleChanger").innerHTML = "CRM :: View upcoming schedules wrt an Account";
            ajaxCallPage = "ajax_Account.php";
        }
        else if (contentSting === "activities by contact") {
            contentHeading.innerHTML = 'View Activities by Contacts';
            _("titleChanger").innerHTML = "CRM :: View Activities by Contacts";
            ajaxCallPage = "ajax_Activity.php";
        }
        else if (contentSting === "activities by member") {
            contentHeading.innerHTML = 'View Activities by Members';
            _("titleChanger").innerHTML = "CRM :: View Activities by Members";
            ajaxCallPage = "ajax_Activity.php";
        }
        else if (contentSting === "employee performance") {
            contentHeading.innerHTML = 'View Employee Report';
            _("titleChanger").innerHTML = "CRM :: Employee Activity Report";
            ajaxCallPage = "ajax_Report.php";
        }
        else if (contentSting === "travel report") {
            contentHeading.innerHTML = 'View Travel Report';
            _("titleChanger").innerHTML = "CRM :: View Travel Report";
            ajaxCallPage = "ajax_Report.php";
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
                contentArea.innerHTML = xmlhttp.responseText;
                ajaxLoader.innerHTML = "";
                ajaxLoader.style.top = "";
                ajaxLoader.style.left = "";
                ajaxLoader.style.width = "";
                ajaxLoader.style.height = "";
                ajaxLoader.style.position = "";
                ajaxLoader.style.display = "";
                ajaxLoader.style.backgroundColor = "";
                ajaxLoader.style.zIndex = "";
                displayDatePicker(); //call the jquery date picker
            }
            else {
                contentArea.innerHTML = "<div style='top: 0; left: 0;width: 100%; height:380px; display: table;'><p style='text-align: center;display: table-cell;vertical-align: middle;'><img src='ajax-loader.gif' height='25' width='25'><span style='font-size: 200%;'> Loading. Please Wait...</span></p></div>";
                ajaxLoader.style.top = window.pageYOffset + "px";
                ajaxLoader.style.left = window.pageXOffset + "px";
                ajaxLoader.style.width = "100%";
                ajaxLoader.style.height = "100%";
                ajaxLoader.style.position = "absolute";
                ajaxLoader.style.display = "table";
                ajaxLoader.style.backgroundColor = "darkgray";
                ajaxLoader.style.zIndex = "1000";
                ajaxLoader.style.opacity = "0";
                ajaxLoader.style.filter = "alpha(opacity=0)";
            }
        };
        xmlhttp.open('GET', 'includes/php/ajax/' + ajaxCallPage + '?cntAction=' + contentSting, true);
        xmlhttp.send();
    }
}

function displayCompanyComboListByType(type, state) {
    if (state.checked) {
        if (type === 'view') {
            _("divMyCompanyChkBox").style.display = "block";
        }
        var url;
        var content = _("divCompanyList");
        url = 'includes/php/ajax/ajax_companyListSelection.php?type=' + type;
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
    } else {
        if (type === 'view') {
            _("divMyCompanyChkBox").style.display = "none";
        }
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
        txtComp.style.width = "100%";
        txtComp.setAttribute("placeholder", "Search company here");
        txtComp.setAttribute("onkeyup", "autopopulate(this,'" + type + "')");
        txtCompPara.appendChild(txtComp);

        //company search suggestion table
        var spanSuggestion = document.createElement("span");
        spanSuggestion.id = "companySuggested";
        spanSuggestion.style.zIndex = "1";
        spanSuggestion.style.position = "absolute";
        spanSuggestion.style.width = "26%";
        var eleBr = document.createElement("br");
        txtCompPara.appendChild(eleBr);
        txtCompPara.appendChild(spanSuggestion);
        _("divCompanyList").innerHTML = "";
        _("divCompanyList").appendChild(txtCompPara);
    }
}