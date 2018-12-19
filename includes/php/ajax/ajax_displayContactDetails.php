<!DOCTYPE html>
<html>
    <head>
        <title>CRM :: Contact Details</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- List of style sheets to be included-->
        <!-- CSS Reset -->
        <link rel="stylesheet" type="text/css" href="../../../reset.css" media="screen" />

        <!-- Fluid 960 Grid System - CSS framework -->
        <link rel="stylesheet" type="text/css" href="../../../grid.css" media="screen" />


        <!-- IE Hacks for the Fluid 960 Grid System -->
        <!--[if IE 6]><link rel="stylesheet" type="text/css" href="../../ie6.css"  media="screen" /><![endif]-->
        <!--[if IE 7]><link rel="stylesheet" type="text/css" href="../../ie.css" media="screen" /><![endif]-->

        <!-- Main stylesheet -->
        <link rel="stylesheet" type="text/css" href="../../../styles.css"  media="screen" />
    </head>
    <body>
        <form method="post" action="" id="editContactForm" onsubmit="return validateEditContact();">
            <div class="grid_6" style="margin-top: 10px;width: 95%" >
                <div class="module">
                    <h2><span>Contact Details</span></h2>
                    <div class="module-body">
                        <?php
                        include_once '../db.php';
                        if (isset($_GET['contId']) && !empty($_GET['contId'])) {
                            $contId = $_GET['contId'];
                            $contSql = "select cont_name, cont_dept, cont_email, cont_mobile, cont_direct, cont_ext, cont_desg, comp_name, comp_type, comp_website, offi_type, offi_rec, offi_boardline, offi_address, offi_country, offi_city from tbl_contact tcon, tbl_company tcom, tbl_office toff where tcon.cont_id = $contId and tcon.comp_id = tcom.comp_id and tcon.offi_id = toff.offi_id ";

                            $contRes = mysqli_query($con, $contSql);
                            $contRow = mysqli_fetch_array($contRes);

                            $contName = $contRow['cont_name'];
                            $contDept = $contRow['cont_dept'];
                            $contEmail = $contRow['cont_email'];
                            $contMobile = $contRow['cont_mobile'];
                            $contDirect = $contRow['cont_direct'];
                            $contExt = $contRow['cont_ext'];
                            $contDesg = $contRow['cont_desg'];
                            $compName = $contRow['comp_name'];
                            $compType = $contRow['comp_type'];
                            $compWebSite = $contRow['comp_website'];
                            $offiType = $contRow['offi_type'];
                            $offiRecrut = $contRow['offi_rec'];
                            $offiBoardLine = $contRow['offi_boardline'];
                            $offiAddr = $contRow['offi_address'];
                            $offiCountry = $contRow['offi_country'];
                            $offiCity = $contRow['offi_city'];
                            ?>                                                                                  
                            <p>
                                <label>Name</label>                                
                                <input type="text" class="input-long" name="txtContName" value="<?php echo $contName; ?>" readonly/>
                            </p>
                            <p>
                                <label>Email</label>
                                <input type="text" class="input-long" name="txtEmail" value="<?php echo $contEmail; ?>" readonly />
                            </p>
                            <p>
                                <label>Mobile</label> 
                                <input type="text" class="input-long" name="txtMobile" value="<?php echo $contMobile; ?>" readonly/>
                            </p>
                            <p>
                                <label>Direct Number</label> 
                                <input type="text" class="input-long" name="txtDirect" value="<?php echo $contDirect; ?>" readonly />
                            </p>
                            <p>
                                <label>Extension Number</label> 
                                <input type="text" class="input-long" name="txtExten" value="<?php echo $contExt; ?>" readonly />
                            </p>
                            <p>                
                                <label>Designation</label> 
                                <input type="text" class="input-long" name="txtDesig" value="<?php echo $contDesg; ?>" readonly/>
                            </p>
                            <p>
                                <label>Department</label> 
                                <input type="text" class="input-long" name="txtDept" value="<?php echo $contDept; ?>" readonly/>
                            </p>                              
                        </div> 
                    </div>
                    <div class="module">
                        <h2><span>Office Details</span></h2>
                        <div class="module-body">
                            <p>
                                <label>Office Type</label>
                                <input type="text" class="input-long" name="txtOffiType" value="<?php echo $offiType; ?>" readonly/>
                            </p>
                            <p>
                                <label>Recruitment Calls are taken from this office</label>
                                <input type="text" class="input-long" name="txtOffiRecrut" value="<?php echo $offiRecrut; ?>" readonly/>
                            </p>
                            <p>
                                <label>Board Line Number</label>
                                <input type="text" class="input-long" name="txtBoard" value="<?php echo $offiBoardLine; ?>" readonly />
                            </p>
                            <p>
                                <label>Address</label> 
                                <textarea class="input-long" rows="4" cols="45" style="resize: none;" readonly><?php echo "$offiAddr"; ?></textarea>
                            </p>
                            <p>
                                <label>Country</label> 
                                <input type="text" class="input-long" name="txtCountry" value="<?php echo $offiCountry; ?>" readonly />
                            </p>
                            <p>
                                <label>City</label> 
                                <input type="text" class="input-long" name="txtExten" value="<?php echo $offiCity; ?>" readonly />
                            </p>             
                        </div> 
                    </div>
                    <div class="module">
                        <h2><span>Company Details</span></h2>
                        <div class="module-body">
                            <p>
                                <label>Company Name</label>
                                <input type="text" class="input-long" name="txtCompName" value="<?php echo $compName; ?>" readonly/>
                            </p>
                            <p>
                                <label>Company Type</label>
                                <input type="text" class="input-long" name="txtCompType" value="<?php echo $compType; ?>" readonly/>
                            </p>
                            <p>
                                <label>Company Website</label>
                                <input type="text" class="input-long" name="txtSite" value="<?php echo $compWebSite; ?>" readonly />
                            </p>                                
                        </div> 
                    </div>
                </div>                 
            </form>  
            <?php
        } else {
            echo "<div>
                      <span class='notification n-success'>You cannot access this page directly.</span>
                  </div>";
        }
        ?>           
    </body>
</html>