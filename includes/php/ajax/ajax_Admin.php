<?php
include_once '../db.php';
include_once '../admin_pagination.php';
include_once '../state_country_list.php';
if (isset($_GET['cntAction']) && !empty($_GET['cntAction'])) {
    $adminContent = $_GET['cntAction'];
    if ($adminContent == "edit approval") {
        ?>
        <div id="adminSuccessMsg"></div>
        <div id="approve-section-div">

        </div>
        <div class="module" id="approval-list-table">
            <h2><span>Pending for Approval List</span></h2>

            <div class="module-table-body">                    	
                <table id="myTable" class="tablesorter">
                    <thead>
                        <tr>
                            <th style="width:3%">#</th>
                            <th style="width:25%">Company Name</th>
                            <th style="width:20%">Contact Name</th>
                            <th style="width:22%">Category</th>
                            <th style="width:10%">Time</th>
                            <th style="width:15%">Employee Name</th>
                            <th style="width:5%"></th>
                        </tr>
                    </thead>
                    <tbody id="pending-edit-list">
                        <?php
                        $pending_list_sql = "SELECT comp_name,cont_name,edit_category,edit_time,empl_name,tedit.cont_id,tcon.offi_id FROM `tbl_contact` as tcon, `tbl_company` as tcom, `tbl_employee` as temp, `tbl_cont_edit_status` as tedit WHERE tedit.cont_id=tcon.cont_id and tedit.comp_id=tcom.comp_id and tedit.empl_id=temp.empl_id and tedit.edit_status='Pending' order by edit_time asc";
                        $pending_list_res = mysqli_query($con, $pending_list_sql);
                        if (mysqli_num_rows($pending_list_res) > 0) {
                            $i = 1;
                            while ($row = mysqli_fetch_array($pending_list_res)) {
                                $company_name = $row[0];
                                $contact_name = $row[1];
                                $category = $row[2];
                                $timestamp = $row[3];
                                $empl_name = $row[4];
                                $cont_id = $row[5];
                                $office_id = $row[6];
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
                                    <td><?php echo $empl_name; ?></td>
                                    <td>                                    
                                        <a href="javascript:void(0)" onclick="displayPendingApprovalList('<?php echo $cont_id; ?>', '<?php echo $category; ?>', '<?php echo $office_id; ?>')">View</a>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="7">No pending request for approval.</td>
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
    else if ($adminContent == "company") {
        $sql = "select sett_id, sett_value from tbl_setting where sett_type='comp_type' and sett_status='Active'"; // requie order by sett_value asc
        $res = mysqli_query($con, $sql);
        if (mysqli_num_rows($res) > 0) {
            $companyList .= "<option value='NA'>Select a Company Type</option>";
            while ($row = mysqli_fetch_array($res)) {
                $companyList .= "<option value='$row[1]'>$row[1]</option>";
            }
        } else {
            $companyList = "<option value='NA'>Please add a company type first</option>";
        }
        ?>
        <div id="adminSuccessMsg"></div> 
        <div id="hiddenSection"></div>      <!-- to store the id of the corresponding Admin Task Type for updating purpose -->
        <table style="border: 0px">
            <tr width="100%">
                <td width="30%" style="border-right: none">
                    <form name='frmCompany' method='post' id='frmCompany' enctype="multipart/form-data" autocomplete="off">
                        <p>
                            <label>Company Name</label>
                            <input type='text' name='txtCmpName' class='input-short' style='width: 75%' placeholder="Enter Company Name" autofocus> 
                        </p> 
                        <p>
                            <label>Company Type</label>
                            <select class="input-short" style='width: 65%' name="cmbCmpType">
                                <?php echo $companyList; ?>
                            </select>
                        </p>      
                        <p>
                            <label>Web Site</label>
                            <input type='text' name='txtWebSite' class='input-short' placeholder="Enter Web Address" style='width: 75%'> 
                        </p>          
                        <!-- <p>
                            <label>Company Logo</label>
                            <input type="file" name="fileCmpLogo" id="file">
                        </p> -->                     

                        <fieldset id="frmCompanFieldset">
                            <input class="submit-green" type="button" onclick="addCompany();" name="btnAdminCmpSubmit" value="Add"> 
                            <input class="submit-gray" type="reset" name="btnAdminCompanyClear" value="Clear">
                        </fieldset>                                   
                    </form> 
                </td>                
                <td width="70%" style="border-right: none">
                    <!--Refresh Button -->
                    <div>
                        <a href="javascript:void(0)" class="button" onclick="displayCompanyList();">
                            <span id="refreshButton">Show Company List <img src="bin.gif" width="16" height="12" alt="Display Company List" title="Dispaly Company List"/></span>
                        </a>
                        <input type="text" name="txtCmpSearchBox" class="input-short" style="width: 35%;float: right;" placeholder="Enter Company Name to Search" onkeyup="searchTableData(this.value, 'Company');">
                    </div>
                    &nbsp;
                    <div id="paginationTable"> 
                        <?php
                        $_SESSION['table_heading'] = "Company List";
                        $_SESSION['column_head'] = array("#" => "5", "Company Name" => "25", "Company Type" => "25", "Company Website" => "25", "Edit Panel" => "20");
                        $_SESSION['sql'] = "SELECT comp_id, comp_name, comp_type, comp_website, comp_type FROM tbl_company ORDER BY comp_name ASC ";
                        viewAdminPagination($adminContent);
                        ?>
                    </div> 
                </td>
            </tr>                    
        </table>                     
        <?php
    } 
    else if ($adminContent == "company type") {
        ?>
        <div id="adminSuccessMsg"></div>  
        <div id="hiddenSection"></div>
        <table style="border: 0px">
            <tr width="100%">
                <td width="30%" style="border-right: none">
                    <form name='frmType' method='post' id='frmCompanyType' autocomplete="off">                                 
                        <p>
                            <label>Company Type</label>
                            <input type='text' id="txtAdminTypeTask" name='txtCmpType' class='input-short' placeholder="Enter Company Type" style='width: 70%' autofocus /> 
                        </p> 
                        <div id="typeStatusSection"></div>
                        <fieldset id="frmBtnFieldset">
                            <input class="submit-green" type="button" onclick="saveAdminTaskForm('Company Type');" name="btnAdminSubmit" value="Add" /> 
                            <input class="submit-gray" type="reset" name="btnAdminClear" value="Clear" />
                        </fieldset>            
                    </form>  
                </td>
                <td width="70%" style="border-right: none">
                    <!--Refresh Button -->
                    <div>
                        <a href="javascript:void(0)" class="button" onclick="displayAdminTaskListTable('comp_type');">
                            <span id="refreshButton">Show Company Type List <img src="bin.gif" width="16" height="12" alt="Display Company Type List" title="Dispaly Company Type List"/></span>
                        </a>
                        <input type="text" name="txtCmpTypeSearchBox" class="input-short" style="width: 35%;float: right;" placeholder="Enter Company Type to Search" onkeyup="searchTableData(this.value, 'Company Type');">
                    </div>
                    &nbsp;
                    <div id="paginationTable">   
                        <?php
                        $_SESSION['table_heading'] = "Company Type List";
                        $_SESSION['column_head'] = array("#" => "10", "Company Type" => "30", "Type Status" => "30", "Edit Panel" => "30");
                        $_SESSION['sql'] = "select sett_id, sett_value, sett_status, sett_type from tbl_setting where sett_type='comp_type' order by sett_value asc";
                        viewAdminPagination($adminContent);
                        ?>
                    </div>                    
                </td>
            </tr>
        </table>
        <?php
    } 
    else if ($adminContent == "office type") {
        ?>
        <div id="adminSuccessMsg"></div>  
        <div id="hiddenSection"></div>
        <table style="border: 0px">
            <tr width="100%">
                <td width="30%" style="border-right: none">        
                    <form name='frmType' method='post' id='frmOfficeType'>          
                        <p>
                            <label>Office Type</label>
                            <input type='text' id="txtAdminTypeTask" name='txtOfcType' class='input-short' placeholder="Enter Office Type" style='width: 70%' autofocus /> 
                        </p> 
                        <div id="typeStatusSection"></div>
                        <fieldset id="frmBtnFieldset">
                            <input class="submit-green" type="button" onclick="saveAdminTaskForm('Office Type');" name="btnAdminSubmit" value="Add" /> 
                            <input class="submit-gray" type="reset" name="btnAdminClear" value="Clear" />
                        </fieldset>            
                    </form>  
                </td>
                <td width="70%" style="border-right: none">
                    <!--Refresh Button -->
                    <div>
                        <a href="javascript:void(0)" class="button" onclick="displayAdminTaskListTable('offi_type');">
                            <span id="refreshButton">Show Office Type List <img src="bin.gif" width="16" height="12" alt="Display Office Type List" title="Dispaly Office Type List"/></span>
                        </a>
                        <input type="text" name="txtOffiTypeSearchBox" class="input-short" style="width: 35%;float: right;" placeholder="Enter Office Type to Search" onkeyup="searchTableData(this.value, 'Office Type');">
                    </div>
                    &nbsp;
                    <div id="paginationTable">   
                        <?php
                        $_SESSION['table_heading'] = "Office Type List";
                        $_SESSION['column_head'] = array("#" => "10", "Office Type" => "30", "Office Status" => "30", "Edit Panel" => "30");
                        $_SESSION['sql'] = "select sett_id, sett_value, sett_status, sett_type from tbl_setting where sett_type='offi_type' order by sett_value asc";
                        viewAdminPagination($adminContent);
                        ?>
                    </div>                    
                </td>
            </tr>
        </table>                    
        <?php
    } 
    else if ($adminContent == "contact status") {
        ?>
        <div id="adminSuccessMsg"></div>  
        <div id="hiddenSection"></div>
        <table style="border: 0px">
            <tr width="100%">
                <td width="30%" style="border-right: none">         
                    <form name='frmType' method='post' id='frmCompanyStatus'>            
                        <p>
                            <label>Contact Status</label>
                            <input type='text' id="txtAdminTypeTask" name='txtCmpStatus' class='input-short' placeholder="Enter Status related to Company" style='width: 70%' autofocus /> 
                        </p> 
                        <div id="typeStatusSection"></div>
                        <fieldset id="frmBtnFieldset">
                            <input class="submit-green" type="button" onclick="saveAdminTaskForm('Company Status');" name="btnAdminSubmit" value="Add" /> 
                            <input class="submit-gray" type="reset" name="btnAdminClear" value="Clear" />
                        </fieldset>            
                    </form>
                </td>
                <td width="70%" style="border-right: none">
                    <!--Refresh Button -->
                    <div>
                        <a href="javascript:void(0)" class="button" onclick="displayAdminTaskListTable('acti_status');">
                            <span id="refreshButton">Show Contact Status List <img src="bin.gif" width="16" height="12" alt="Display Company Status List" title="Dispaly Company Status List"/></span>
                        </a>
                        <input type="text" name="txtContactStatusSearchBox" class="input-short" style="width: 35%;float: right;" placeholder="Enter Contact Status to Search" onkeyup="searchTableData(this.value, 'Contact Status');">
                    </div>
                    &nbsp;
                    <div id="paginationTable"> 
                        <?php
                        $_SESSION['table_heading'] = "Contact Status List";
                        $_SESSION['column_head'] = array("#" => "10", "Contact Status Type" => "30", "Status" => "30", "Edit Panel" => "30");
                        $_SESSION['sql'] = "select sett_id, sett_value, sett_status, sett_type from tbl_setting where sett_type='acti_status' order by sett_value asc";
                        viewAdminPagination($adminContent);
                        ?>
                    </div>                    
                </td>
            </tr>
        </table>                    
        <?php
    } 
    else if ($adminContent == "contact relationship") {
        ?>
        <div id="adminSuccessMsg"></div>  
        <div id="hiddenSection"></div>
        <table style="border: 0px">
            <tr width="100%">
                <td width="30%" style="border-right: none">              
                    <form name='frmType' method='post' id='frmContRelation'>          
                        <p>
                            <label>Relationship of Additional Contacts</label>
                            <input type='text' id="txtAdminTypeTask" name='txtCntRlt' class='input-short' placeholder="Enter Relationship of additional Contact" style='width: 70%' autofocus /> 
                        </p> 
                        <div id="typeStatusSection"></div>
                        <fieldset id="frmBtnFieldset">
                            <input class="submit-green" type="button" onclick="saveAdminTaskForm('Contact Relation');" name="btnAdminSubmit" value="Add" /> 
                            <input class="submit-gray" type="reset" name="btnAdminClear" value="Clear" />
                        </fieldset>            
                    </form>  
                </td>
                <td width="70%" style="border-right: none">
                    <!--Refresh Button -->
                    <div>
                        <a href="javascript:void(0)" class="button" onclick="displayAdminTaskListTable('add_relation');">
                            <span id="refreshButton">Show Additional Contact List <img src="bin.gif" width="16" height="12" alt="Display relationship with additional contact" title="Dispaly relationship with additional"/></span>
                        </a>
                        <input type="text" name="txtContactRelSearchBox" class="input-short" style="width: 35%;float: right;" placeholder="Enter Contact Relationship to Search" onkeyup="searchTableData(this.value, 'Contact Relationship');">
                    </div>
                    &nbsp;
                    <div id="paginationTable">  
                        <?php
                        $_SESSION['table_heading'] = "Additional Relationship List";
                        $_SESSION['column_head'] = array("#" => "10", "Additional Relationship" => "30", "Status" => "30", "Edit Panel" => "30");
                        $_SESSION['sql'] = "select sett_id, sett_value, sett_status, sett_type from tbl_setting where sett_type='add_relation' order by sett_value asc";
                        viewAdminPagination($adminContent);
                        ?>
                    </div>                    
                </td>
            </tr>
        </table>                     
        <?php
    } 
    else if ($adminContent == "department") {
        ?>
        <div id="adminSuccessMsg"></div>  
        <div id="hiddenSection"></div>
        <table style="border: 0px">
            <tr width="100%">
                <td width="30%" style="border-right: none">           
                    <form name='frmDepartment' method='post' id='frmDepartment'>           
                        <p>
                            <label>Functional Department</label>
                            <input type='text' id="txtAdminTypeTask" name='txtDept' class='input-short' placeholder="Enter Functional Department" style='width: 70%' autofocus /> 
                        </p> 
                        <div id="typeStatusSection"></div>
                        <fieldset id="frmBtnFieldset">            
                            <input class="submit-green" type="button" onclick="saveAdminTaskForm('Functional Department');" name="btnAdminSubmit" value="Add" /> 
                            <input class="submit-gray" type="reset" name="btnAdminClear" value="Clear" />
                        </fieldset>            
                    </form>  
                </td>
                <td width="70%" style="border-right: none">
                    <!--Refresh Button -->
                    <div>
                        <a href="javascript:void(0)" class="button" onclick="displayAdminTaskListTable('func_dept');">
                            <span id="refreshButton">Show Functional Department List <img src="bin.gif" width="16" height="12" alt="Display relationship with additional contact" title="Dispaly relationship with additional"/></span>
                        </a>
                        <input type="text" name="txtDeptSearchBox" class="input-short" style="width: 35%;float: right;" placeholder="Enter Department Name to Search" onkeyup="searchTableData(this.value, 'Department');">
                    </div>
                    &nbsp;
                    <div id="paginationTable">  
                        <?php
                        $_SESSION['table_heading'] = "Functional Departments List";
                        $_SESSION['column_head'] = array("#" => "10", "Functional Departments" => "30", "Status" => "30", "Edit Panel" => "30");
                        $_SESSION['sql'] = "select sett_id, sett_value, sett_status, sett_type from tbl_setting where sett_type='func_dept' order by sett_value asc";
                        viewAdminPagination($adminContent);
                        ?>
                    </div>                    
                </td>
            </tr>
        </table>                     
        <?php
    } 
    else if ($adminContent == "salutation") {
        ?> 
        <div id="adminSuccessMsg"></div>  
        <div id="hiddenSection"></div>
        <table style="border: 0px">
            <tr width="100%">
                <td width="30%" style="border-right: none">           
                    <form name='frmSalutation' method='post' id='frmSalutation'>         
                        <p>
                            <label>Salutation</label>
                            <input type='text' id="txtAdminTypeTask" name='txtSalute' class='input-short' placeholder="Enter Salutation" style='width: 70%' autofocus /> 
                        </p> 
                        <div id="typeStatusSection"></div>
                        <fieldset id="frmBtnFieldset">            
                            <input class="submit-green" type="button" onclick="saveAdminTaskForm('Salutation');" name="btnAdminSubmit" value="Add" /> 
                            <input class="submit-gray" type="reset" name="btnAdminClear" value="Clear" />
                        </fieldset>            
                    </form>  
                </td>
                <td width="70%" style="border-right: none">
                    <!--Refresh Button -->
                    <div>
                        <a href="javascript:void(0)" class="button" onclick="displayAdminTaskListTable('salute');">
                            <span id="refreshButton">Show Salutation List <img src="bin.gif" width="16" height="12" alt="Display Salutation List" title="Dispaly Salutation List"/></span>
                        </a>
                        <input type="text" name="txtSalutationSearchBox" class="input-short" style="width: 35%;float: right;" placeholder="Enter Salutation to Search" onkeyup="searchTableData(this.value, 'Salutation');">
                    </div>
                    &nbsp;
                    <div id="paginationTable"> 
                        <?php
                        $_SESSION['table_heading'] = "Salutation List";
                        $_SESSION['column_head'] = array("#" => "10", "Salutation" => "30", "Status" => "30", "Edit Panel" => "30");
                        $_SESSION['sql'] = "select sett_id, sett_value, sett_status, sett_type from tbl_setting where sett_type='salute' order by sett_value asc";
                        viewAdminPagination($adminContent);
                        ?>
                    </div>                    
                </td>
            </tr>
        </table>        
        <?php
    } 
    else if ($adminContent == "college list") {
        ?> 
        <div id="adminSuccessMsg"></div>  
        <div id="hiddenSection"></div>
        <table style="border: 0px">
            <tr width="100%">
                <td width="30%" style="border-right: none">           
                    <form name='frmCollgList' method='post' id='frmCollgList'>         
                        <p>
                            <label>College Name</label>
                            <input type='text' id="txtAdminTypeTask" name='txtCollege' placeholder="Enter College Name" class='input-short' style='width: 70%' autofocus /> 
                        </p> 
                        <div id="typeStatusSection"></div>
                        <fieldset id="frmBtnFieldset">            
                            <input class="submit-green" type="button" onclick="saveAdminTaskForm('College');" name="btnAdminSubmit" value="Add" /> 
                            <input class="submit-gray" type="reset" name="btnAdminClear" value="Clear" />
                        </fieldset>            
                    </form>  
                </td>
                <td width="70%" style="border-right: none">
                    <!--Refresh Button -->
                    <div>
                        <a href="javascript:void(0)" class="button" onclick="displayAdminTaskListTable('college');">
                            <span id="refreshButton">Show College List <img src="bin.gif" width="16" height="12" alt="Display College List" title="Dispaly College List"/></span>
                        </a>
                        <input type="text" name="txtCollegeSearchBox" class="input-short" style="width: 35%;float: right;" placeholder="Enter College Name to Search" onkeyup="searchTableData(this.value, 'College');">
                    </div>
                    &nbsp;
                    <div id="paginationTable">   
                        <?php
                        $_SESSION['table_heading'] = "College List";
                        $_SESSION['column_head'] = array("#" => "10", "College Name" => "30", "Status" => "30", "Edit Panel" => "30");
                        $_SESSION['sql'] = "select sett_id, sett_value, sett_status, sett_type from tbl_setting where sett_type='college' order by sett_value asc";
                        viewAdminPagination($adminContent);
                        ?>
                    </div>                    
                </td>
            </tr>
        </table>        
        <?php
    } 
    else if ($adminContent == "stream") {
        ?> 
        <div id="adminSuccessMsg"></div>  
        <div id="hiddenSection"></div>
        <table style="border: 0px">
            <tr width="100%">
                <td width="30%" style="border-right: none">           
                    <form name='frmStream' method='post' id='frmStream'>         
                        <p>
                            <label>Stream Name</label>
                            <input type='text' id="txtAdminTypeTask" name='txtStream' class='input-short' placeholder="Enter Stream" style='width: 70%' autofocus /> 
                        </p> 
                        <div id="typeStatusSection"></div>
                        <fieldset id="frmBtnFieldset">            
                            <input class="submit-green" type="button" onclick="saveAdminTaskForm('Stream');" name="btnAdminSubmit" value="Add" /> 
                            <input class="submit-gray" type="reset" name="btnAdminClear" value="Clear" />
                        </fieldset>            
                    </form>  
                </td>
                <td width="70%" style="border-right: none">                                        <!--Refresh Button -->
                    <div>
                        <a href="javascript:void(0)" class="button" onclick="displayAdminTaskListTable('stream');">
                            <span id="refreshButton">Show Stream List <img src="bin.gif" width="16" height="12" alt="Display stream list" title="Dispaly stream list"/></span>
                        </a>
                        <input type="text" name="txtSteamSearchBox" class="input-short" style="width: 35%;float: right;" placeholder="Enter Stream Name to Search" onkeyup="searchTableData(this.value, 'Stream');">
                    </div>
                    &nbsp;
                    <div id="paginationTable">   
                        <?php
                        $_SESSION['table_heading'] = "Stream List";
                        $_SESSION['column_head'] = array("#" => "10", "Stream Name" => "30", "Status" => "30", "Edit Panel" => "30");
                        $_SESSION['sql'] = "select sett_id, sett_value, sett_status, sett_type from tbl_setting where sett_type='stream' order by sett_value asc";
                        viewAdminPagination($adminContent);
                        ?>
                    </div>                    
                </td>
            </tr>
        </table>        
        <?php
    } 
    else if ($adminContent == "district") {
        ?>
        <div id="adminSuccessMsg"></div>  
        <div id="hiddenSection"></div>
        <table style="border: 0px">
            <tr width="100%">
                <td width="30%" style="border-right: none">
                    <form name='frmDistrict' method='post' id='frmDistrict' autocomplete="off">                                 
                        <p>
                            <label>City</label>
                            <input type='text' id="txtCity" name='txtCity' class='input-short' placeholder="Enter City Name" style='width: 70%' autofocus /> 
                        </p> 
                        <p>
                            <label>State</label>
                            <select class="input-short" style='width: 60%' name="cmbState" onchange="otherState(this.value);">
                                <option vlaue="NA">Please select a State</option>
                                <?php echo $state; ?>
                            </select>
                        </p>      
                        <div id="otherState"></div>
                        <p>
                            <label>Country</label>                            
                            <select class="input-short" style='width: 60%' name="cmbCountry" onchange="otherCountry(this.value);">
                                <option value="NA">Please select a Country</option>
                                <?php echo $country; ?>
                            </select>
                        </p> 
                        <!--
                        <p>
                            <label>PIN</label>
                            <input type='text' id="txtPin" name='txtPin' class='input-short' placeholder="Enter PIN Code" style='width: 70%' /> 
                        </p>                                                  
                        -->
                        <fieldset id="frmBtnFieldset">
                            <input class="submit-green" type="button" onclick="addDistrict();" name="btnAddDistrict" value="Add" /> 
                            <input class="submit-gray" type="button" onclick="clearDistrictFrm();" name="btnAdminClear" value="Clear" />
                        </fieldset>            
                    </form>  
                </td>
                <td width="70%" style="border-right: none">                                       <!--Refresh Button -->
                    <div>
                        <a href="javascript:void(0)" class="button" onclick="displayDistrictList();">
                            <span id="refreshButton">Show District List <img src="bin.gif" width="16" height="12" alt="Display District List" title="Dispaly District List"/></span>
                        </a>
                        <input type="text" name="txtDistrictSearchBox" class="input-short" style="width: 35%;float: right;" placeholder="Enter District Name to Search" onkeyup="searchTableData(this.value, 'District');">
                    </div>
                    &nbsp;
                    <div id="paginationTable">
                        <?php
                        $_SESSION['table_heading'] = "District List";
                        $_SESSION['column_head'] = array("#" => "5", "City" => "20", "State" => "30", "Country" => "30", "Edit Panel" => "15");
                        $_SESSION['sql'] = "SELECT * FROM `tbl_address` order by addr_city asc ";
                        viewAdminPagination($adminContent);
                        ?>
                    </div>                    
                </td>
            </tr>
        </table>
        <?php
    } 
    else if ($adminContent == "manage reminder") {
        ?> 
        <div id="adminSuccessMsg"></div>  
        <div id="hiddenSection"></div>
        <input type="hidden" id="hiddenEmpId" />
        <table style="border: 0px">
            <tr width="100%">
                <td width="30%" style="border-right: none">           
                    <form name='frmUpdateTodo' method='post' id='frmUpdateTodo'>         
                        <p>
                            <label>Select an Employee</label>
                            <select class="input-short" style='width: 65%' name="cmbEmplLst" id ="cmbEmplLst" >
                                <?php
                                $sql = "SELECT `empl_id`,`empl_name` FROM `tbl_employee` order by empl_name asc";
                                $res = mysqli_query($con, $sql);

                                if (mysqli_num_rows($res) > 0) {
                                    echo "<option value='NA'>Select an Employee</option>";
                                    while ($row = mysqli_fetch_array($res)) {
                                        echo "<option value='$row[0]'>$row[1]</option>";
                                    }
                                } else {
                                    echo "<option value='NA' disabled>No Employee Found</option>";
                                }
                                ?>
                            </select>
                        </p>  
                        <p>
                            <label>From Date Range</label>
                            <input type="text" name="startDate" size="12" id="inputField" class="input-short" placeholder="Click here to select" style="width: 70%">
                        </p>
                        <p>
                            <label>To Date Range</label>
                            <input type="text" name="endDate" size="12" id="inputField1" class="input-short" placeholder="Click here to select" style="width: 70%">
                        </p>
                        <fieldset id="frmBtnFieldset">            
                            <input class="submit-green" type="button" onclick="displayTodoListTable();" name="btnTodoEmplSubmit" value="Submit" /> 
                            <input class="submit-gray" type="reset" name="btnTodoEmplSubmitReset" value="Reset" />
                        </fieldset>    
                    </form>  
                </td>
                <td width="70%" style="border-right: none">
                    <!--Refresh Button -->                    
                    &nbsp;
                    <div id="paginationTable">   
                    </div>                    
                </td>
            </tr>
        </table>        
        <?php
    } 
    else if ($adminContent == "manage user") {
        ?>
        <div id="adminSuccessMsg"></div>   
        <div id="hiddenSection"></div>      <!-- to store the id of the corresponding User Type for updating purpose -->
        <table style="border: 0px; table-layout: fixed;">
            <tr style="width:100%;">
                <td style="border-right: none;width: 20%">
                    <form name='frmManageUser' id='frmManageUser'>                        
                        <p>
                            <label>Employees's Name</label>
                            <input type='text' name='txtEmpName' class='input-short' placeholder="Enter Employee's Name" style='width: 85%' autofocus /> 
                        </p> 
                        <p>
                            <label>User ID</label>
                            <input type='text' name='txtUserId' class='input-short' placeholder="Enter only Alphanumeric User ID" style='width: 85%' /> 
                        </p> 
                        <p>
                            <label>Password</label>
                            <input type='text' name='txtPassword' class='input-short' placeholder="Enter Password" style='width: 85%' /> 
                        </p>                           
                        <p>
                            <label>Select User Type</label>
                            <select class="input-short" style='width: 60%' name="cmbUserType">
                                <option value="NA">Select User Type</option>
                                <option value="Admin">Admin</option>
                                <option value="User">User</option>                                
                            </select>
                        </p>
                        <p>
                            <label>Date of Birth</label>
                            <input type="text" name='txtDob' size="12" id="inputField" class='input-short' placeholder="Click here to select" style='width: 60%' />
                        </p>
                        <p>
                            <label>E-mail</label>
                            <input type='text' name='txtEmail' class='input-short' placeholder="Enter User's E-mail Address" style='width: 85%' /> 
                        </p> 
                        <p>
                            <label>Mobile</label>
                            <input type='text' name='txtMobile' class='input-short' placeholder="Enter User's Mobile Number" style='width: 85%' /> 
                        </p> 
                        <p>
                            <label>Address</label>
                            <input type='text' name='txtAddress' class='input-short' placeholder="Enter User's Address" style='width: 85%' /> 
                        </p>
                        <div id="hidCmbStatus"></div>
                        <fieldset id="fldUserBtnContainer">
                            <input class="submit-green" type="button" onclick="addUser();" name="btnFrmUserAdd" value="Add" /> 
                            <input class="submit-gray" type="reset" name="btnFrmUserClear" value="Clear" onclick="javascript: document.frmManageUser.txtEmpName.focus();"/>
                        </fieldset>            
                    </form>  
                </td>                
                <td style="border-right: none;width:80%;">      
                    <!--Refresh Button -->
                    <div>
                        <a href="javascript:void(0)" class="button" onclick="displayUserList();">
                            <span id="refreshButton">Show User List <img src="bin.gif" width="16" height="12" alt="Display User List" title="Dispaly User List"/></span>
                        </a>
                        <input type="text" name="txtUserSearchBox" class="input-short" style="width: 35%;float: right;" placeholder="Enter User Name to Search" onkeyup="searchTableData(this.value, 'User Name');">
                    </div>
                    &nbsp;
                    <div id="paginationTable">   
                        <?php
                        $_SESSION['table_heading'] = "User List";
                        $_SESSION['column_head'] = array("#" => "4", "User Name" => "7", "User ID" => "9", "Password" => "12", "User Type" => "7", "DOB" => "12", "Email" => "15", "Mobile" => "10", "Address" => "10", "Status" => "8", "Action" => "7");
                        $_SESSION['sql'] = 'select empl_id, empl_name, empl_userId, password, empl_type, DATE_FORMAT(empl_dob,"%d-%m-%Y"), empl_email, empl_mobile, empl_address, empl_status from tbl_employee order by empl_name asc ';
                        viewAdminPagination($adminContent);
                        ?>
                    </div> 
                </td>
            </tr>                    
        </table>  
        <?php
    } else if ($adminContent == "contact visibility") {
        ?>
        <div id="adminSuccessMsg"></div>   
        <?php
        $sql = "SELECT `sett_value` FROM `tbl_setting` WHERE `sett_type`='cont_view'";
        $res = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($res);
        $sett_value = $row['sett_value'];
        ?>
        <form name='frmContVisibility'>
            <fieldset>
                <legend>Set the corporate contact visibility amongst CR Member</legend>
                <ul>                    
                    <li><label><input type="radio" name="rdoVisibility" value="No" <?php if ($sett_value == 'No') echo "checked='checked'" ?> onchange='adminChangeContactVisibility(this.value);' /> View only own contacts</label></li>
                    <li><label><input type="radio" name="rdoVisibility" value="Yes" <?php if ($sett_value == 'Yes') echo "checked='checked'" ?> onchange='adminChangeContactVisibility(this.value);' /> View all contacts</label></li>
                </ul>
            </fieldset>        
        </form>        
        <?php
    }
    else if ($adminContent == "swapping contact") {
        ?> 
        <div id="adminSuccessMsg"></div>                  
        <table style="border: 0px">
            <tr width="100%">
                <td width="30%" style="border-right: none">           
                    <form name='frmSwapContact'>         
                        <p>
                            <label>From</label>
                            <select class="input-short" style='width: 65%' name="cmbFromEmplLst" id="cmbFromEmplLst" onchange="displayEmplContact(this.value, 'No');">
                                <?php
                                $sql = "SELECT `empl_id`,`empl_name` FROM `tbl_employee` order by empl_name asc";
                                $res = mysqli_query($con, $sql);

                                if (mysqli_num_rows($res) > 0) {
                                    echo "<option value='NA'>Select an Employee</option>";
                                    while ($row = mysqli_fetch_array($res)) {
                                        echo "<option value='$row[0]'>$row[1]</option>";
                                    }
                                } else {
                                    echo "<option value='NA' disabled>No Employee Found</option>";
                                }
                                ?>
                            </select>
                        </p>         
                        <p>
                            <label>To</label>
                            <select class="input-short" style='width: 65%' name="cmbToEmplLst" id="cmbToEmplLst" disabled>
                                <option value="NA">First select an employee</option>
                            </select><span id="toEmpAjaxLoader"></span>
                        </p>         
                        <fieldset id="frmBtnFieldset">            
                            <input class="submit-green" type="button" onclick="swapContact();" name="btnSwapContact" value="Swap" /> 
                            <input class="submit-gray" type="reset" name="btnResetSwapContact" value="Reset" onclick="resetSwapContact();" />
                        </fieldset>    
                    </form>  
                </td>
                <td width="70%" style="border-right: none">
                    <!--Refresh Button -->                    
                    &nbsp;
                    <div id="paginationTable">   
                    </div>                    
                </td>
            </tr>
        </table>        
        <?php
    } else if ($adminContent == "travel destination") {
        ?> 
        <div id="adminSuccessMsg"></div>  
        <div id="hiddenSection"></div>
        <table style="border: 0px">
            <tr width="100%">
                <td width="30%" style="border-right: none">           
                    <form name='frmTravelDest' method='post' id='frmTravelDest'>         
                        <p>
                            <label>Destination Name</label>
                            <input type='text' id="txtAdminTypeTask" name='txtAdminTypeTask' placeholder="Enter Destination Name" class='input-short' style='width: 70%' autofocus /> 
                        </p>      
                        <div id="typeStatusSection"></div>
                        <fieldset id="frmBtnFieldset">            
                            <input class="submit-green" type="button" onclick="saveAdminTaskForm('Travel');" name="btnAdminSubmit" value="Add" /> 
                            <input class="submit-gray" type="reset" name="btnAdminClear" value="Clear" />
                        </fieldset>            
                    </form>  
                </td>
                <td width="70%" style="border-right: none">
                    <!--Refresh Button -->
                    <div>
                        <a href="javascript:void(0)" class="button" onclick="displayAdminTaskListTable('travel');">
                            <span id="refreshButton">Show Travel Destination List <img src="bin.gif" width="16" height="12" alt="Display Travel Destination List" title="Dispaly Travel Destination List"/></span>
                        </a>
                        <input type="text" name="txtDestinationSearchBox" class="input-short" style="width: 35%;float: right;" placeholder="Enter Travel Destination to Search" onkeyup="searchTableData(this.value, 'Destination');">
                    </div>
                    &nbsp;
                    <div id="paginationTable">   
                        <?php
                        $_SESSION['table_heading'] = "Travel Destination List";
                        $_SESSION['column_head'] = array("#" => "10", "Travel Destination" => "30", "Status" => "30", "Edit Panel" => "30");
                        $_SESSION['sql'] = "select sett_id, sett_value, sett_status, sett_type from tbl_setting where sett_type='travel' order by sett_value asc";
                        viewAdminPagination($adminContent);
                        ?>
                    </div>                    
                </td>
            </tr>
        </table>
        <?php
    }
}

if (isset($_POST['btnAdminSubmit']) && !empty($_POST['btnAdminSubmit'])) {
    //var_dump($_POST);
    // $companyType =  mysqli_real_escape_string($con,$_POST['txtCmpType']);
    // $settingType =  mysqli_real_escape_string($con,$_POST['settingType']);
    foreach ($_POST as $key => $value) {
        if ($key == "settingType")
            $settingType = $value;
        if ($key != "btnAdminSubmit" && $key != "settingType")
            $settingValue = str_replace("*()", "+", $value);
    }
    $sql = "insert into tbl_setting values(NULL,'$settingValue','$settingType','Active')";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo "Success";
    } else {
        echo "Fail";
    }
}
?>