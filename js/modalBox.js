function _(e1){
    return document.getElementById(e1);
}

function closeModalBox() {
    var modalBoxDiv = _("modalBox-Div");
    modalBoxDiv.innerHTML = "";
    modalBoxDiv.style.position = "";
    modalBoxDiv.style.height = "";
    modalBoxDiv.style.width = "";
    modalBoxDiv.style.zIndex = "";
    modalBoxDiv.style.top = "";
    modalBoxDiv.style.left = "";
    modalBoxDiv.style.background = "";
    modalBoxDiv.style.marginLeft = "";
    modalBoxDiv.style.overflow = "";
    modalBoxDiv.style.border = "";
    modalBoxDiv.style.borderRadius = "";
    modalBoxDiv.style.boxShadow = "";
    var outerDiv = _("simplemodal-overlay");
    outerDiv.style.opacity = "";
    outerDiv.style.height = "";
    outerDiv.style.width = "";
    outerDiv.style.position = "";
    outerDiv.style.left = "";
    outerDiv.style.top = "";
    outerDiv.style.zIndex = "";
    outerDiv.style.backgroundColor = "";
}

function displayModalBox(id) {
    // Set the style of the outer <div>
    var outerDiv = _("simplemodal-overlay");
    outerDiv.style.opacity = "0.2";
    outerDiv.style.height = "1250px";
    outerDiv.style.width = "1349px";
    outerDiv.style.position = "fixed";
    outerDiv.style.left = "0px";
    outerDiv.style.top = "0px";
    outerDiv.style.zIndex = "1001";
    outerDiv.style.backgroundColor = "black";

    // Now create the modalbox div
    var modalBoxDiv = _("modalBox-Div");
    // Now set the style of the modalbox <div>
    modalBoxDiv.style.position = "absolute";
    modalBoxDiv.style.height = "525px";
    modalBoxDiv.style.width = "600px";
    modalBoxDiv.style.zIndex = "1011";
    var yCodinate = window.pageYOffset + 50;
    modalBoxDiv.style.top = yCodinate + "px";
    modalBoxDiv.style.left = "42%";
    modalBoxDiv.style.background = "white";
    modalBoxDiv.style.marginLeft = "-250px";
    modalBoxDiv.style.overflow = "auto";
    modalBoxDiv.style.border = "2px solid #a1a1a1";
    modalBoxDiv.style.borderRadius = "10px";
    modalBoxDiv.style.boxShadow = "10px 10px 5px #888888";

    // Create the title <div> and append it with the modalbox <div>
    var modalBoxTitleDiv = document.createElement("div");
    modalBoxTitleDiv.id = "modalBox-titleBar";

    // Create the title <span> and append it with the title <div>
    var modalBoxTitleSpan = document.createElement("span");
    // Set the innerHTML of the title <span>                
    var modalBoxTitle = _(id).getAttribute("title");
    if (modalBoxTitle === null)
        modalBoxTitle = "";
    modalBoxTitleSpan.innerHTML = modalBoxTitle + " <img id='modelBoxClose' src='modalBox-close.png' height='18' width='18' style='float: right; margin-right: 10px;margin-top: 3px;' title='Exit' onclick='closeModalBox();'>";
    modalBoxTitleDiv.appendChild(modalBoxTitleSpan);

    // Create the <iframe> and append it with the modalbox <div>
    var modalBoxIframe = document.createElement("iframe");
    // Set the ifrmae attributes                
    //modalBoxIframe.src = _(id).getAttribute("href");                
    modalBoxIframe.setAttribute("src", _(id).getAttribute("href"));

    modalBoxIframe.setAttribute("frameBorder", "0");
    //modalBoxIframe.seamless = true;
    modalBoxIframe.style.width = "100%";
    modalBoxIframe.style.height = "90%";

    modalBoxDiv.appendChild(modalBoxTitleDiv);
    modalBoxDiv.appendChild(modalBoxIframe);
    return false;
}

function displayUpdateContactModalBox(id) {
    // Set the style of the outer <div>
    var outerDiv = _("simplemodal-overlay");
    outerDiv.style.opacity = "0.2";
    outerDiv.style.height = "1250px";
    outerDiv.style.width = "1349px";
    outerDiv.style.position = "fixed";
    outerDiv.style.left = "0px";
    outerDiv.style.top = "0px";
    outerDiv.style.zIndex = "1001";
    outerDiv.style.backgroundColor = "black";

    // Now create the modalbox div
    var modalBoxDiv = _("modalBox-Div");
    // Now set the style of the modalbox <div>
    modalBoxDiv.style.position = "absolute";
    modalBoxDiv.style.height = "525px";
    modalBoxDiv.style.width = "600px";
    modalBoxDiv.style.zIndex = "1011";
    var yCodinate = window.pageYOffset + 50;
    modalBoxDiv.style.top = yCodinate + "px";
    modalBoxDiv.style.left = "42%";
    modalBoxDiv.style.background = "white";
    modalBoxDiv.style.marginLeft = "-250px";
    modalBoxDiv.style.overflow = "auto";
    modalBoxDiv.style.border = "2px solid #a1a1a1";
    modalBoxDiv.style.borderRadius = "10px";
    modalBoxDiv.style.boxShadow = "10px 10px 5px #888888";

    // Create the title <div> and append it with the modalbox <div>
    var modalBoxTitleDiv = document.createElement("div");
    modalBoxTitleDiv.id = "modalBox-titleBar";

    // Create the title <span> and append it with the title <div>
    var modalBoxTitleSpan = document.createElement("span");
    // Set the innerHTML of the title <span>                
    var modalBoxTitle = _(id).getAttribute("title");
    if (modalBoxTitle === null)
        modalBoxTitle = "";
    modalBoxTitleSpan.innerHTML = modalBoxTitle + " <img id='modelBoxClose' src='modalBox-close.png' height='18' width='18' style='float: right; margin-right: 10px;margin-top: 3px;' title='Exit' onclick='fetchOfficeDetail(document.getElementsByName(\"offi_list\"));closeModalBox();'>";
    modalBoxTitleDiv.appendChild(modalBoxTitleSpan);

    // Create the <iframe> and append it with the modalbox <div>
    var modalBoxIframe = document.createElement("iframe");
    // Set the ifrmae attributes                
    //modalBoxIframe.src = _(id).getAttribute("href");                
    modalBoxIframe.setAttribute("src", _(id).getAttribute("href"));

    modalBoxIframe.setAttribute("frameBorder", "0");
    //modalBoxIframe.seamless = true;
    modalBoxIframe.style.width = "100%";
    modalBoxIframe.style.height = "90%";

    modalBoxDiv.appendChild(modalBoxTitleDiv);
    modalBoxDiv.appendChild(modalBoxIframe);
    return false;
}

function displayTodoModalBox(id) {
    //alert("debesh");
    // Set the style of the outer <div>
    var outerDiv = _("simplemodal-overlay");
    outerDiv.style.opacity = "0.2";
    outerDiv.style.height = "1250px";
    outerDiv.style.width = "1349px";
    outerDiv.style.position = "fixed";
    outerDiv.style.left = "0px";
    outerDiv.style.top = "0px";
    outerDiv.style.zIndex = "1001";
    outerDiv.style.backgroundColor = "black";

    // Now create the modalbox div
    var modalBoxDiv = _("modalBox-Div");
    // Now set the style of the modalbox <div>
    modalBoxDiv.style.position = "absolute";
    modalBoxDiv.style.height = "525px";
    modalBoxDiv.style.width = "600px";
    modalBoxDiv.style.zIndex = "1011";
    var yCodinate = window.pageYOffset + 50;
    modalBoxDiv.style.top = yCodinate + "px";
    modalBoxDiv.style.left = "42%";
    modalBoxDiv.style.background = "white";
    modalBoxDiv.style.marginLeft = "-250px";
    modalBoxDiv.style.overflow = "auto";
    modalBoxDiv.style.border = "2px solid #a1a1a1";
    modalBoxDiv.style.borderRadius = "10px";
    modalBoxDiv.style.boxShadow = "10px 10px 5px #888888";

    // Create the title <div> and append it with the modalbox <div>
    var modalBoxTitleDiv = document.createElement("div");
    modalBoxTitleDiv.id = "modalBox-titleBar";

    // Create the title <span> and append it with the title <div>
    var modalBoxTitleSpan = document.createElement("span");
    // Set the innerHTML of the title <span>                
    var modalBoxTitle = _(id).getAttribute("title");
    if (modalBoxTitle === null)
        modalBoxTitle = "";
    modalBoxTitleSpan.innerHTML = modalBoxTitle + " <img id='modelBoxClose' src='modalBox-close.png' height='18' width='18' style='float: right; margin-right: 10px;margin-top: 3px;' title='Exit' onclick='closeModalBox();'>";
    modalBoxTitleDiv.appendChild(modalBoxTitleSpan);

    // Create the <iframe> and append it with the modalbox <div>
    var modalBoxIframe = document.createElement("iframe");
    // Set the ifrmae attributes                
    //modalBoxIframe.src = _(id).getAttribute("href");                
    modalBoxIframe.setAttribute("src", _(id).getAttribute("href"));
    modalBoxIframe.setAttribute("frameBorder", "0");
    //modalBoxIframe.seamless = true;
    modalBoxIframe.style.width = "100%";
    modalBoxIframe.style.height = "90%";

    modalBoxDiv.appendChild(modalBoxTitleDiv);
    modalBoxDiv.appendChild(modalBoxIframe);

    var sortBy = _("rightPanelTodoSort").value;
    //alert("Right Panel: " + sortBy);
    modalBoxIframe.onload = function() {
        if (_("todoTabulationSortingType")) {
            var todoTableSortType = _("todoTabulationSortingType").value;
            //alert("Pagination Table Type: " + todoTableSortType);
            todoRefreshList('ViewList', todoTableSortType);
        }
        displayOnlyByType(sortBy);
    };
    return false;
}

var displayCalendarModalBox = function(href,title){
    // Set the style of the outer <div>
    var outerDiv = _("simplemodal-overlay");
    outerDiv.style.opacity = "0.2";
    outerDiv.style.height = "1250px";
    outerDiv.style.width = "1349px";
    outerDiv.style.position = "fixed";
    outerDiv.style.left = "0px";
    outerDiv.style.top = "0px";
    outerDiv.style.zIndex = "1001";
    outerDiv.style.backgroundColor = "black";

    // Now create the modalbox div
    var modalBoxDiv = _("modalBox-Div");
    // Now set the style of the modalbox <div>
    modalBoxDiv.style.position = "absolute";
    modalBoxDiv.style.height = "525px";
    modalBoxDiv.style.width = "600px";
    modalBoxDiv.style.zIndex = "1011";
    var yCodinate = window.pageYOffset + 50;
    modalBoxDiv.style.top = yCodinate + "px";
    modalBoxDiv.style.left = "42%";
    modalBoxDiv.style.background = "white";
    modalBoxDiv.style.marginLeft = "-250px";
    modalBoxDiv.style.overflow = "auto";
    modalBoxDiv.style.border = "2px solid #a1a1a1";
    modalBoxDiv.style.borderRadius = "10px";
    modalBoxDiv.style.boxShadow = "10px 10px 5px #888888";

    // Create the title <div> and append it with the modalbox <div>
    var modalBoxTitleDiv = document.createElement("div");
    modalBoxTitleDiv.id = "modalBox-titleBar";

    // Create the title <span> and append it with the title <div>
    var modalBoxTitleSpan = document.createElement("span");
    // Set the innerHTML of the title <span>                
    var modalBoxTitle = title;
    if (modalBoxTitle === null)
        modalBoxTitle = "";
    modalBoxTitleSpan.innerHTML = modalBoxTitle + " <img id='modelBoxClose' src='modalBox-close.png' height='18' width='18' style='float: right; margin-right: 10px;margin-top: 3px;' title='Exit' onclick='closeModalBox();'>";
    modalBoxTitleDiv.appendChild(modalBoxTitleSpan);

    // Create the <iframe> and append it with the modalbox <div>
    var modalBoxIframe = document.createElement("iframe");
    // Set the ifrmae attributes                    
    modalBoxIframe.setAttribute("src", href);

    modalBoxIframe.setAttribute("frameBorder", "0");
    //modalBoxIframe.seamless = true;
    modalBoxIframe.style.width = "100%";
    modalBoxIframe.style.height = "90%";

    modalBoxDiv.appendChild(modalBoxTitleDiv);
    modalBoxDiv.appendChild(modalBoxIframe);    
};