<?php
include_once '../db.php';
session_start();
$empl_id = $_SESSION['empId'];
if (isset($_GET['cntAction']) && !empty($_GET['cntAction'])) {
    $contactContent = $_GET['cntAction'];
    if ($contactContent == "new") {
        //include_once './ajax_companyComboList.php';
        $sqlSalute = "select sett_value from tbl_setting where sett_type='salute' and sett_status='Active'";
        $res = mysqli_query($con, $sqlSalute);
        $salutation = "<select name='salute'>";
        while ($row = mysqli_fetch_array($res)) {
            $salutation.="<option>" . $row[0] . "</option>";
        }
        $salutation.="</select>";
        $sqlAddress = "select addr_city from tbl_address where addr_country='India' order by addr_city asc";
        $resAddress = mysqli_query($con, $sqlAddress);
        if (mysqli_num_rows($resAddress) > 0) {
            $city = "<select class='input-long' name='offi_city'>";
            while ($row = mysqli_fetch_array($resAddress)) {
                $city.="<option>" . $row[0] . "</option>";
            }
            $city.="</select>";
        } else {
            $city = "No City Available";
        }
        ?>
        <div id="contactMessageDisplay"></div>
        <form method="post" action="" id="addNewContactForm">
            <div id="divCompanyList">
                <p id="txtCmpPara">
                    <label id="comanyLabel">Company(*)</label>
                    <input type="text" name="txtCompSearchBox" class="input-short" placeholder="Search company here" onkeyup="autopopulate(this, 'new')" style="width: 70%;" autofocus autocomplete="off"><span id='companySearchAjax'></span>
                    <br />
                    <span id="companySuggested" style="z-index: 1; position: absolute; width: 26%;"></span>
                </p>
            </div>
            <p id="chekCompLstPara">
                <input type="checkbox" name="chkCmbCompanyLst" id="chkCmbCompanyLst" onchange="displayCompanyComboListByType('new', this)">
                <span> Display Company List</span>
            </p>        
            <div class="grid_6" style="margin-top: 10px;width: 35%" >
                <div class="module">
                    <h2><span>Personal Details</span></h2>
                    <div class="module-body">
                        <p>
                            <label>Name(*)</label><?php echo $salutation; ?> <input type="text" class="input-long" placeholder="Enter the Name of the Contact" name="cont_name"/>
                        </p>
                        <p>
                            <label>Email</label><input type="text" class="input-long" placeholder="Enter the E-Mail if the Contact" name="cont_email"/>
                        </p>
                        <p>
                            <label>Mobile</label><input type="text" class="input-long" placeholder="Enter the Mobile Number of the Contact" name="cont_mobile"/>
                        </p>
                        <p>
                            <label>Direct Number</label><input type="text" class="input-long" placeholder="Enter the Direct Number of the Contact"  name="cont_direct"/>
                        </p>
                        <p>
                            <label>Extension Number</label><input type="text" class="input-long" placeholder="Enter the Extension Number of the Contact"  name="cont_ext"/>
                        </p>
                        <p>
                            <label>Designation</label><input type="text" class="input-long" placeholder="Enter the Designation of the Contact"  name="cont_desg"/>
                        </p>
                        <p>
                            <label>Department(*)</label><select class="input-long"  placeholder="Department to which Contact belongs" name="cont_dept">
                                <?php include_once 'ajax_departmentComboList.php'; ?>
                            </select>
                        </p>
                        <p>
                            <label>Current Status</label> <select class="input-long" name="cont_active">
                                <option>Active</option>
                            </select>
                        </p>
                    </div>
                </div> <!-- module -->
                <div style="clear:both;"></div>

            </div> <!-- End .grid_6 -->

            <div class="grid_6" style="margin-top: 10px;width: 30%" >

                <div class="module">
                    <h2><span>Office Details</span></h2>
                    <div class="module-body" style="display:block; padding-bottom: 0px;">
                        <p>
                            <label>Select the Way to enter the office details</label> 
                            <select name="offi_selector" onchange="displayOfficeForm(this.value)">
                                <option>New</option>
                                <option>Existing</option>
                            </select>
                        </p>
                    </div>
                    <div class="module-body" id="newOffice" style="display:block; padding-top: 0px;" >
                        <p>
                            <label>Office Type</label> <select class="input-long" name="offi_type"><?php include_once './ajax_officeTypeComboList.php'; ?></select>
                        </p>
                        <p>
                            <label>Board Line Number(*)</label><input type="text" class="input-long" placeholder="Enter the Board Line Number" name="offi_board"/>
                        </p>
                        <p>
                            <label>Address Line 1(*)</label> <input type="text" class="input-long" placeholder="First Line of Address" name="offi_add1"/>
                        </p>
                        <p>
                            <label>Address Line 2</label> <input type="text" class="input-long" placeholder="Second Line of Address" name="offi_add2"/>
                        </p>
                        <p>
                            <label>Country</label> 
                            <select class="input-long" name="offi_country" onchange="fetchCityList(this.value)">
                                <option>India</option>
                                <option>Other</option>
                            </select>
                        </p>
                        <p>
                            <label>City</label> <span id="cityListContainer">
                                <?php echo $city; ?>
                            </span>
                        </p>
                        <p>
                            <label>PIN</label> <input type="text" class="input-long" placeholder="Enter PIN Code" name="offi_pin">
                        </p>
                        <p>
                            <label><input type="checkbox" style="margin-top:10px;" name="rec_status" checked="checked"/>Recruitment Calls are taken from this office</label>
                        </p>
                    </div>
                    <div class="module-body" id="oldOffice" style="display:none;padding-top: 0px;">
                        <!--a href="javascript:void(0)" class="button" onclick="fetchOfficeList(document.getElementsByName('txtCompSearchBox')[0].value)">
                            <span>Fetch Office List<img src="plus-small.gif" width="12" height="9" alt="Fetch Office List" /></span>
                        </a-->
                        <div id='officeListContainer'>

                        </div>
                        <div id='officeDetailContainer'>

                        </div>
                    </div>
                </div> <!-- module -->

                <div style="clear:both;"></div>

            </div> <!-- End .grid_6 -->
            <div class="grid_6" style="margin-top: 10px;width: 27%" >

                <div class="module">
                    <h2><span>Other Details</span></h2>

                    <div class="module-body">
                        <p>
                            <label>Select the appropriate Stream(*)</label> 
                            <?php include_once './ajax_streamCheckboxList.php'; ?>
                        </p>
                        <!--p>
                            <label>Select the type of Contact</label>
                            <select class="input-medium" name="contact_type" onchange="displayAdditionalContact(this.value)">
                                <option>Regular</option>
                                <option>Functional Head</option>
                                <option>Additional Contact</option>
                            </select>
                        </p>
                        <p id="additionalContactRelation" style="display: none;">
                            <label>Select the Relationship for Additional Contact</label>
                            <select name="add_relation"></select>
                        </p-->
                        <!-- Select Event Combobox -->
                        <p>
                            <label>Select the appropriate Event</label>
                            <select class="input-long" name="cont_conclave" id="cont_conclave">
                                <option value="">Select an Event</option>                                
                                <option value="Finance Conclave">Finance Conclave</option>                                
                                <option value="HR Conclave">HR Conclave</option>
                                <option value="Marketing Conclve">Marketing Conclave</option>
                                <option value="NMC">NMC</option>
                                <option value="Seminar">Seminar</option>
                            </select>
                        </p>
                        <p>
                            <label>Status of the Contact(*)</label>
                            <select class="input-long" name="cont_status" onchange="displayCommentFormByStatus(this.value)">
                                <?php include_once './ajax_contactStatusComboList.php'; ?>
                            </select>
                        </p>
                        <div>
                            <textarea class="input-long" rows="10" id="statusComment" style="display:none;" name="statusComment" placeholder="Enter the summarized comments for the selected status"></textarea>
                            <p  style="display:none;" id="positiveLabel">Enter the probable Month of visit</p>
                            <select style="display:none;" name="visitingMonth" id="visitingMonth">
                                <option value="NA">Select</option>
                                <option>January</option>
                                <option>February</option>
                                <option>March</option>
                                <option>April</option>
                                <option>May</option>
                                <option>June</option>
                                <option>July</option>
                                <option>August</option>
                                <option>September</option>
                                <option>October</option>
                                <option>November</option>
                                <option>December</option>
                            </select>
                            <select style="display:none;" name="visitingYear" id="visitingYear" >
                                <option value="NA">Select</option>
                                <?php
                                $year_limit = (int) (date("Y") + 5);
//                                    $year = $year_limit+1;
                                for ($year = 2013; $year < $year_limit; $year++) {
                                    echo "<option>$year</option>";
                                }
                                ?> 
                            </select>
                        </div>
                    </div>
                </div> <!-- module -->
                <div style="clear:both;"></div>
            </div> <!-- End .grid_6 -->            
        </form>                        
        <input class="submit-green" type="submit" onclick="saveContactDetails()" value="Save Details" style="margin-left:30px;" />
        <?php
    }
    if ($contactContent == "view") {
        ?>
        <div id="contactMessageDisplay"></div>
        <table style="border: 0px; padding:0px;">
            <tr>
                <td style="width: 50%;border: 0px;">
                    <div id="divCompanyList">
                        <p id="txtCmpPara" style="margin: 0px;">
                            <label id="comanyLabel">Company</label>
                            <input type="text" name="txtCompSearchBox" class="input-short" placeholder="Search company here" onkeyup="autopopulate(this, 'view')" style="width: 100%;" autofocus autocomplete="off"><span id='companySearchAjax'></span>                
                            <br />
                            <span id="companySuggested" style="z-index: 1; position: absolute; width: 26%;"></span>
                        </p>            
                    </div>
                </td>
                <td style="width: 50%;border: 0px;">                    
                    <div id="divMyCompanyChkBox" style="display: none;">
                        <br /><label style="display:inline;padding-left: 10px;padding-top: 20px;"><input name="myContLst" id="myContLst" type="checkbox" onclick="displayMyCompanyList(this);"> Display only my Company List</label>

                                                                                                                                                                        <!--<label style="display:inline;padding-left: 10px;padding-top: 20px;"><input name="myContLst" id="myContLst" type="checkbox" onclick="myContactDisplay();"> Display only my Contact List</label>            -->
                    </div>
                </td>
            </tr>
        </table>        
        <p id="chekCompLstPara">
            <input type="checkbox" name="chkCmbCompanyLst" id="chkCmbCompanyLst" onchange="displayCompanyComboListByType('view', this)">
            <span> Display Company List</span>
        </p>
        <p>
            <label>
                <a href="javascript:void(0)" class="button" onclick="if (document.getElementsByName('txtCompSearchBox').length == 2)
                                    fetchContactListByComp(document.getElementsByName('txtCompSearchBox')[1].value, 'checkbox', 'contact');
                                else
                                    fetchContactListByComp(document.getElementsByName('txtCompSearchBox')[0].value, 'checkbox', 'contact');">
                    <span>Refresh Contact List<img src="bin.gif" width="12" height="9" alt="Fetch Office List" /></span>
                </a>
            </label><br /><br />
        </p>
        <div  class="grid_6" style="width: 20%">
            <div class="module">
                <h2><span>Contact List</span></h2>

                <div class="module-body" id='contactContainer'>

                </div>

            </div>
        </div>
        <div class="grid_6" style="width: 73%;">
            <div class="module">
                <h2><span>Contact Details</span></h2>

                <div class="module-table-body">
                    <table id="myTable" class="tablesorter">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Primary Contacts</th>
                                <th>Other Contacts</th>
                                <th>Job Position</th>
                                <th>Contact(s) in Touch</th>
                                <th>Office Details</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id='contactDetailContainer'>
                            <tr>
                                <td colspan="8">Please select respective Company and Contacts to view details</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php
    }
    if ($contactContent == "contact search") {
        ?>
        <form action="includes/php/downloadContactSearchExcelReport.php" method="post">
            <div id="divContactSearch">
                <p id="paraContactSearch">                    
                    <input type="text" name="txtCompSearchBox" class="input-short" placeholder="Enter anything related to the Contact" onkeyup="autopopulate(this, 'search')" style="width: 70%;" autofocus autocomplete="off"><span id='companySearchAjax'></span>
                    <br />
                    <span id="companySuggested" style="z-index: 1; position: absolute; width: 50%; background-color: white;"></span>
                </p>
            </div>
            <div data-jibp="h" data-jiis="uc" id="topstuff">
                <div class="med">  
                    <span style="margin-top:1em;font-weight: bold;">Search keyword should be any one of the following:</span> 
                    <ul style="margin-left:5em;list-style: circle;">
                        <li>Contact Name.</li>
                        <li>Email Address.</li>
                        <li>Mobile Number.</li>
                        <li>Direct Number.</li>
                        <li>Extension Number.</li>
                        <li>Contact Designation.</li>
                        <li>Company Name.</li>
                        <li>Company Type.</li>
                        <li>Company Website.</li>
                        <li>Office Board Line Number.</li>
                        <li>Office Address.</li>
                        <li>Office City.</li>
                    </ul> 
                </div>
            </div>
            <div class="grid_6" style="width: 100%;display: none;height: 370px; margin-left: 0px;" id="divContactDetails">
                <div class="module">                
                    <h2><span>Contact Details</span></h2>
                    <div class="module-table-body">
                        <div style="height: 300px;overflow-y: auto;display: block;">
                            <table class="tablesorter" id="contactDetailContainer" style="width: 1026px;">                                                                                                          
                            </table>                        
                        </div>
                    </div>
                    <div style="padding-bottom: 10px;">
                        <center><input class="submit-green" type="submit" id="btnReportSubmit" name="btnContactDownload" value="Download"></center>
                    </div>                
                </div>
            </div>
        </form>
        <?php
    }
    if ($contactContent == "functional heads") {
        //include_once './ajax_companyComboList.php';
        $sqlSalute = "select sett_value from tbl_setting where sett_type='salute' and sett_status='Active'";
        $res = mysqli_query($con, $sqlSalute);
        $salutation = "<select name='salute'>";
        while ($row = mysqli_fetch_array($res)) {
            $salutation.="<option>" . $row[0] . "</option>";
        }
        $salutation.="</select>";
        ?>
        <div id="contactMessageDisplay"></div>
        <p>
            <label>Please select a Company</label>
            <select name="txtCompSearchBox" class="input-long">
                <?php include_once './ajax_companyComboList.php'; ?>
            </select>
        </p> 
        <form method="post" action="" id="addNewContactForm">
            <div class="grid_6" style="margin-top: 10px;width: 30%" >
                <div class="module">
                    <h2><span>Personal Details</span></h2>

                    <div class="module-body">
                        <p>
                            <label>Name(*)</label><?php echo $salutation; ?> <input type="text" class="input-long" placeholder="Enter the Name of the Contact" name="cont_name"/>
                        </p>
                        <p>
                            <label>Email</label><input type="text" class="input-long" placeholder="Enter the E-Mail if the Contact" name="cont_email"/>
                        </p>
                        <p>
                            <label>Mobile</label> <input type="text" class="input-long" placeholder="Enter the Mobile Number of the Contact" name="cont_mobile"/>
                        </p>
                        <p>
                            <label>Direct Number</label> <input type="text" class="input-long" placeholder="Enter the Direct Number of the Contact"  name="cont_direct"/>
                        </p>
                        <p>
                            <label>Extension Number</label> <input type="text" class="input-long" placeholder="Enter the Extension Number of the Contact"  name="cont_ext"/>
                        </p>
                        <p>
                            <label>Designation</label> <input type="text" class="input-long" placeholder="Enter the Designation of the Contact"  name="cont_desg"/>
                        </p>
                        <p>
                            <label>Department(*)</label> <select class="input-long"  placeholder="Department to which Contact belongs" name="cont_dept">
                                <?php include_once 'ajax_departmentComboList.php'; ?>
                            </select>
                        </p>
                        <p>
                            <label>Current Status</label> <select class="input-long" name="cont_active">
                                <option>Active</option>
                            </select>
                        </p>
                    </div>
                </div> <!-- module -->
                <div style="clear:both;"></div>

            </div> <!-- End .grid_6 -->

            <div class="grid_6" style="margin-top: 10px;width: 30%" >

                <div class="module">
                    <h2><span>Office Details</span></h2>
                    <div class="module-body" style="display:block; ">
                        <p>
                            <label>Select the Way to enter the office details</label> <select name="offi_selector" onchange="displayOfficeForm(this.value)">
                                <option>New</option>
                                <option>Existing</option>
                            </select>
                        </p>
                    </div>
                    <div class="module-body" id="newOffice" style="display:block; ">

                        <p>
                            <label>Office Type</label> <select class="input-long" name="offi_type"><?php include_once './ajax_officeTypeComboList.php'; ?></select>
                        </p>
                        <p>
                            <label>Board Line Number</label><input type="text" class="input-long" placeholder="Enter the Board Line Number" name="offi_board"/>
                        </p>
                        <p>
                            <label>Address Line 1</label> <input type="text" class="input-long" placeholder="First Line of Address" name="offi_add1"/>
                        </p>
                        <p>
                            <label>Address Line 2</label> <input type="text" class="input-long" placeholder="Second Line of Address" name="offi_add2"/>
                        </p>
                        <p>
                            <label>Country</label> 
                            <select class="input-long" name="offi_country" onchange="fetchCityList(this.value)">
                                <option>Select</option>
                                <option>India</option>
                                <option>Other</option>
                            </select>
                        </p>
                        <p>
                            <label>City</label> <span id="cityListContainer"></span>
                        </p>
                        <p>
                            <label><input type="checkbox" style="margin-top:10px;" name="rec_status"  checked="checked"/>Recruitment Calls are taken from this office</label>
                        </p>
                    </div>
                    <div class="module-body" id="oldOffice" style="display:none; ">
                        <a href="javascript:void(0)" class="button" onclick="fetchOfficeList(document.getElementById('companyLst').value)">
                            <span>Fetch Office List<img src="plus-small.gif" width="12" height="9" alt="Fetch Office List" /></span>
                        </a>
                        <div id='officeListContainer'>

                        </div>
                    </div>
                </div> <!-- module -->

                <div style="clear:both;"></div>

            </div> <!-- End .grid_6 -->
            <div class="grid_6" style="margin-top: 10px;width: 30%" >

                <div class="module">
                    <h2><span>Other Details</span></h2>

                    <div class="module-body">
                        <p>
                            <label>Select the appropriate Stream(Not Compulsory)</label> 
                            <?php include_once './ajax_streamCheckboxList.php'; ?>
                        </p>
                        <p>
                            <label>Status of the Contact</label>
                            <select class="input-long" name="cont_status"><?php include_once './ajax_contactStatusComboList.php'; ?></select>
                        </p>
                    </div>
                </div> <!-- module -->
                <div style="clear:both;"></div>
            </div> <!-- End .grid_6 -->
        </form>
        <fieldset>
            <input class="submit-green" type="submit" onclick="saveFunctionalHeadDetails()" value="Save Details" style="margin-left:30px;" />
        </fieldset>

        <?php
    }
    if ($contactContent == "contact edit status") {
        ?>
        <div class="module" id="approval-list-table">
            <h2><span>Edited Contact - Approval Status List</span></h2>

            <div class="module-table-body">                    	
                <table id="myTable" class="tablesorter">
                    <thead>
                        <tr>
                            <th style="width:3%">#</th>
                            <th style="width:25%">Company Name</th>
                            <th style="width:20%">Contact Name</th>
                            <th style="width:22%">Category</th>
                            <th style="width:10%">Time</th>
                            <th style="width:20%">Status</th>                            
                        </tr>
                    </thead>
                    <tbody id="pending-edit-list">
                        <?php
                        $pending_list_sql = "SELECT comp_name,cont_name,edit_category,edit_time,tedit.edit_status,tedit.edit_id,tedit.edit_comment FROM `tbl_contact` as tcon, `tbl_company` as tcom,`tbl_cont_edit_status` as tedit WHERE tedit.cont_id=tcon.cont_id and tedit.comp_id=tcom.comp_id and tedit.empl_id=$empl_id order by edit_time asc";
                        $pending_list_res = mysqli_query($con, $pending_list_sql);
                        if (mysqli_num_rows($pending_list_res) > 0) {
                            $i = 1;
                            while ($row = mysqli_fetch_array($pending_list_res)) {
                                $company_name = $row[0];
                                $contact_name = $row[1];
                                $category = $row[2];
                                $timestamp = $row[3];
                                $edit_status = $row[4];
                                $edit_id = $row[5];
                                $edit_comment = $row[6];
                                $color = '';
                                $formatted_time = date('d-m-Y (h:i A)', $timestamp);
                                if ($i % 2 == 0) {
                                    $color = "even";
                                } else {
                                    $color = "odd";
                                }
                                ?>
                                <tr class="<?php echo $color; ?>">
                                    <td class="align-center"><?php echo $i; ?></td>
                                    <td><?php echo $company_name; ?></td>
                                    <td><?php echo $contact_name; ?></td>
                                    <td><?php echo $category; ?></td>
                                    <td><?php echo $formatted_time; ?></td>
                                    <?php if ($edit_status == "Disapproved") { ?>
                                        <td title="Click here to view the reason for disapproval">
                                            <span class="disapprove-edit" onclick="displayDeleteDisapproveReason(<?php echo $edit_id; ?>);"><?php echo $edit_status; ?></span>               
                                            <span class="delete-status" id="comment-span-<?php echo $edit_id; ?>" style="display: none;">
                                                <hr style="margin-top:5px;margin-bottom: 5px;">
                                                <span>
                                                    <b><u>Reason:</u></b> <?php echo $edit_comment; ?>
                                                </span>
                                            </span>
                                        </td>                                    
                                    <?php } else { ?>
                                        <td><?php echo $edit_status; ?></td>                           
                                    <?php } ?>
                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="7">You have no edited contact.</td>
                            </tr>
                            <?php
                        }
                        ?>                        
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
}

if (isset($_POST['task']) && $_POST['task'] == "Save") {
    $contactName = mysqli_real_escape_string($con, $_POST['contactName']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
    $contactType = mysqli_real_escape_string($con, $_POST['type']);
    $direct = mysqli_real_escape_string($con, $_POST['direct']);
    $ext = mysqli_real_escape_string($con, $_POST['ext']);
    $desg = mysqli_real_escape_string($con, $_POST['desg']);
    $dept = mysqli_real_escape_string($con, $_POST['dept']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $conclave = mysqli_real_escape_string($con, $_POST['conclave']);
    $empId = $_SESSION['empId'];

    $companyId = $_POST['companyId'];

    $contactStatus = $_POST['contactStatus'];
    $statusComment = mysqli_real_escape_string($con, $_POST['statusComment']);
    $stream = array_values($_POST['stream']);
    $package = array_values($_POST['package']);
    /* $stream = "";
      foreach ($_POST['stream'] as $key => $value) {
      $stream.=$value . ",";
      }
      $queryCompany = "update tbl_company set stream='$stream' where comp_id=$companyId"; */
    // Get the stream and package associative array and convert it into normal array       
    //echo $queryCompany."<br />";
    $resCompany = mysqli_query($con, $queryCompany);
    $queryContact = "insert into tbl_contact values(NULL,'$contactName','$contactType','$dept','$email','$mobile','$direct','$ext','$desg','$conclave','$contactStatus','$status',$companyId,'',$empId)";
    //echo $queryContact."<br />";
    //echo $queryContact;
    $resContact = mysqli_query($con, $queryContact);
    //echo $queryContact;
    $contactId = mysqli_insert_id($con);

    if ($resContact) {
        //Now insert office data if it is selected "New"
        if ($_POST['officeSelector'] == "New") {
            $officeType = $_POST['officeType'];
            $officeBoard = $_POST['officeBoard'];
            $officeAddress = $_POST['officeAddress'];
            $officeCountry = $_POST['officeCountry'];
            $city = $_POST['city'];
            $pin = $_POST['pin'];
            $recStatus = $_POST['recStatus'] == 'true' ? "Yes" : "No";
            $sqlOffice = "insert into tbl_office values (NULL,'$officeType','$recStatus','None','$officeBoard','$officeAddress','$officeCountry','$city','$pin',$companyId)";
            //echo $sqlOffice."<br />";
            $resOffice = mysqli_query($con, $sqlOffice);
            $officeId = mysqli_insert_id($con);
        } else {
            $officeId = $_POST['existingOffice'];
        }
        $queryUpdateContact = "update tbl_contact set offi_id=$officeId where cont_id=$contactId";
        mysqli_query($con, $queryUpdateContact);

        $activityDate = date("Y-m-d");
        $activityDetail = "The contact was added to the CR Database";
        $queryActivity = "insert into tbl_emp_contact values(NULL, '$activityDate', '', '$activityDetail', '$contactId', '$empId', '$companyId', '$officeId', 'Added', '$contactStatus', '$statusComment')";
        mysqli_query($con, $queryActivity);

        //Now insert the stream values into the tbl_stream
        $arrStream = array_values($stream);
        $arrPackage = array_values($package);
        for ($i = 0, $c = count($arrStream); $i < $c; $i++) {
            $queryStream = "insert into tbl_stream values (NULL, '$arrStream[$i]', $contactId, $package[$i])";
            mysqli_query($con, $queryStream);
        }
        echo "Success," . $contactId;
        //echo "Success";
    } else
        echo "Failure";
}

if (isset($_POST['task']) && $_POST['task'] == "contactDetail") {
    $contactList = implode(",", $_POST['contactList']);
    $empId = $_SESSION['empId'];
    $companyName = $_POST['companyName'];
    $sqlContactDetail = "SELECT * , (
                            SELECT GROUP_CONCAT( DISTINCT (
                                SELECT te.empl_name
                                FROM tbl_employee te
                                WHERE te.empl_id = tec.empl_id
                                ) 
                            ) 
                            FROM tbl_emp_contact tec
                            WHERE tec.cont_id = tc.cont_id
                        ) AS touch
                        FROM tbl_contact tc, tbl_office tof
                        WHERE tc.offi_id = tof.offi_id
                        AND tc.cont_id
                        IN ($contactList)";

    /*
     * 
     * QUERY INCULDING THE COMPANY NAME AS WELL
      SELECT * , (
      SELECT GROUP_CONCAT( DISTINCT (

      SELECT te.empl_name
      FROM tbl_employee te
      WHERE te.empl_id = tec.empl_id
      ) )
      FROM tbl_emp_contact tec
      WHERE tec.cont_id = tc.cont_id
      ) AS touch
      FROM tbl_contact tc, tbl_company tco, tbl_office tof
      WHERE tc.comp_id = tco.comp_id
      AND tc.offi_id = tof.offi_id
      AND tc.cont_id
      IN ( 1, 3 )
     */
    $resContactDetail = mysqli_query($con, $sqlContactDetail);
    $i = 1;
    while ($rowContactDetail = mysqli_fetch_array($resContactDetail)) {
        if ($i % 2 == 0) {
            $rowType = 'class="even"';
        } else {
            $rowType = 'class="odd"';
        }
        ?>
        <tr <?php echo $rowType; ?>>
            <td><?php echo $i ?></td>
            <td><?php echo $rowContactDetail['cont_name'] ?></td>
            <td><?php
                if ($_SESSION['contViewSetting'] == "No" && $empId != $rowContactDetail['empl_id']) {
                    echo "Not Authorized";
                } else {
                    echo "Email: " . $rowContactDetail['cont_email'] . "<hr />Mobile:" . $rowContactDetail['cont_mobile'] . "<hr />Direct: " . $rowContactDetail['cont_direct'] . "<hr />Extension: " . $rowContactDetail['cont_ext'];
                }
                ?></td>
            <td><?php echo "Board Line : " . $rowContactDetail['offi_boardline']; ?></td>
            <td><?php echo "Designation: " . $rowContactDetail['cont_desg'] . "<br />Department: " . $rowContactDetail['cont_dept'] ?></td>
            <td><?php echo str_replace(",", " ,<br />", $rowContactDetail['touch']) ?></td>
            <td><?php
                echo $rowContactDetail['offi_address'] . "<br />" . $rowContactDetail['offi_city'] . ", " . $rowContactDetail['offi_country'];
                if ($rowContactDetail['offi_pin'] != "") {
                    echo "<br>PIN: " . $rowContactDetail['offi_pin'];
                }
                ?>
            </td>
            <td>
                <?php
                if ($_SESSION['empType'] == "Admin" || $empId == $rowContactDetail['empl_id']) {
                    ?>
                    <br />                    
                    <a id="editContact<?php echo $i; ?>" href="includes/php/ajax/ajax_editContact.php?contId=<?php echo $rowContactDetail['cont_id'] ?>" onclick="return displayUpdateContactModalBox(this.id);"><img src="edit-image.jpg" width="50"  alt="Edit Contact Details" title="Edit Contact Details" /></a>                                       
                    <?php
                }
                if ($_SESSION['empType'] == "Admin") {
                    ?>                
                    <br><img src="delete.png" style="height:15px;cursor: pointer;" alt="Delete this Contact" title="Delete this contact" onclick="deleteContact('<?php echo $rowContactDetail['cont_id']; ?>');" />                                               
                    <br><a id="toDo<?php echo $i; ?>" href="includes/php/ajax/ajax_AddToDoList.php?include=style&contId=<?php echo $rowContactDetail['cont_id'] ?>" onclick="return displayModalBox(this.id);"><img src="pencil.gif" width="16" height="16" alt="Add a Reminder for this Contact" title="Add a Reminder for this Contact" /></a>
                    <a  id="activity<?php echo $i; ?>" href="includes/php/ajax/ajax_addActivity.php?contId=<?php echo $rowContactDetail['cont_id'] ?>" onclick="return displayModalBox(this.id);"><img src="balloon.gif" width="16" height="16" alt="Add an Activity wrt this Contact" title="Add an Activity wrt this Contact" /></a>                    
                    <?php
                } else {
                    ?>                
                    <br><a id="toDo<?php echo $i; ?>" href="includes/php/ajax/ajax_AddToDoList.php?include=style&contId=<?php echo $rowContactDetail['cont_id'] ?>" onclick="return displayModalBox(this.id);"><img src="pencil.gif" width="16" height="16" alt="Add a Reminder for this Contact" title="Add a Reminder for this Contact" /></a>
                    <a  id="activity<?php echo $i; ?>" href="includes/php/ajax/ajax_addActivity.php?contId=<?php echo $rowContactDetail['cont_id'] ?>" onclick="return displayModalBox(this.id);"><img src="balloon.gif" width="16" height="16" alt="Add an Activity wrt this Contact" title="Add an Activity wrt this Contact" /></a>
                    <?php
                }
                ?>
                <br /> 
                <a id="toDoView<?php echo $i; ?>" href="includes/php/ajax/ajax_viewContactSchedule.php?contactId=<?php echo $rowContactDetail['cont_id']; ?>&include=style&contactName=<?php echo $rowContactDetail['cont_name']; ?>&compName=<?php echo $companyName; ?>" onclick="return displayModalBox(this.id);"><img src="reminder.png" width="50" alt="View All Reminders for this Contact" title="View All Reminders for this Contact" /></a>
                <br />
                <a  id="activityView<?php echo $i; ?>" href="includes/php/ajax/ajax_viewContactActivity.php?contactId=<?php echo $rowContactDetail['cont_id']; ?>&include=style&contactName=<?php echo $rowContactDetail['cont_name']; ?>&compName=<?php echo $companyName; ?>" onclick="return displayModalBox(this.id);"><img src="activities.png" width="50" alt="View All Activities wrt this Contact" title="View All Activities wrt this Contact" /></a> 
            </td>
        </tr>
        <?php
        $i++;
    }
}
