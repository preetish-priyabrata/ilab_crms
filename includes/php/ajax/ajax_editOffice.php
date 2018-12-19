<!DOCTYPE html>
<html>
    <head>
        <title>CRM :: Add new Event/Notice</title>
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
        <div class="grid_6" style="margin-top: 10px;width: 100%" >
            <div class="module">
                <h2><span>Office Details</span></h2>
                <div class="module-body">
                    <?php
                    include_once '../db.php';
                    if (isset($_POST['btnUpdateOffice']) && !empty($_POST['btnUpdateOffice']) && $_POST['btnUpdateOffice'] == 'Update Office') {
                        $offi_id = mysqli_real_escape_string($con, $_POST['hid_offi_id']);
                        $offi_type = mysqli_real_escape_string($con, $_POST['offi_type']);
                        $offi_board = mysqli_real_escape_string($con, $_POST['offi_board']);
                        $offi_addr = mysqli_real_escape_string($con, $_POST['txtAreaOffiAddr']);
                        $offi_country = mysqli_real_escape_string($con, $_POST['offi_country']);
                        $offi_city = mysqli_real_escape_string($con, $_POST['offi_city']);
                        $offi_recruitment_status = mysqli_real_escape_string($con, $_POST['rec_status']);
                        if ($offi_recruitment_status == true) {
                            $offi_recruitment_status = "Yes";
                        } else {
                            $offi_recruitment_status = "No";
                        }

                        $sql = "UPDATE `tbl_office` SET `offi_type` = '$offi_type', `offi_rec` = '$offi_recruitment_status', `offi_boardline` = '$offi_board', `offi_address` = '$offi_addr', `offi_country` = '$offi_country', `offi_city` = '$offi_city' WHERE `tbl_office`.`offi_id` = $offi_id;";
                        $res = mysqli_query($con, $sql);
                        if (mysqli_affected_rows($con) > 0) {
                            echo "<div>
                                    <span class='notification n-success'>Office details has been updated sucessfully.</span>
                                </div>";
                        } else {
                            echo "<div>
                                    <span class='notification n-error'>No updates done.</span>
                                  </div>";
                        }
                    } else if (isset($_GET['officeId']) && !empty($_GET['officeId'])) {
                        //office id
                        $offi_id = $_GET['officeId'];

                        $sql = "SELECT * FROM `tbl_office` WHERE `offi_id` = $offi_id";
                        $res = mysqli_query($con, $sql);
                        $row = mysqli_fetch_array($res);

                        //office type
                        $offi_type = $row['offi_type'];

                        //office type
                        $sqlOffiType = "select sett_value from tbl_setting where sett_type='offi_type'";
                        $resOffiType = mysqli_query($con, $sqlOffiType);
                        $officeType = "";
                        while ($rowOffiType = mysqli_fetch_array($resOffiType)) {
                            if ($offi_type == $rowOffiType[0])
                                $officeType .="<option selected>" . $rowOffiType[0] . "</option>";
                            else
                                $officeType .="<option>" . $rowOffiType[0] . "</option>";
                        }

                        //boardline number
                        $boardLine = $row['offi_boardline'];

                        //office address
                        $officeAddress = $row['offi_address'];

                        //office country
                        $offi_country = $row['offi_country'];
                        $officeCountry = "";
                        if ($offi_country == "India") {
                            $officeCountry .= "<option selected>India</option><option>Other</option>";
                        } else
                            $officeCountry .= "<option>India</option><option selected>Other</option>";

                        //office city
                        $offi_city = $row['offi_city'];
                        $city = "";
                        if ($offi_country == "India")
                            $sqlCity = "select addr_city from tbl_address where addr_country='India'";
                        else
                            $sqlCity = "select addr_city from tbl_address where addr_country != 'India'";
                        $resCity = mysqli_query($con, $sqlCity);
                        if (mysqli_num_rows($resCity) > 0) {
                            $city = "<select class='input-long' name='offi_city'>";
                            while ($rowCity = mysqli_fetch_array($resCity)) {
                                if ($offi_city == $rowCity[0])
                                    $city.="<option selected>" . $rowCity[0] . "</option>";
                                else
                                    $city.="<option>" . $rowCity[0] . "</option>";
                            }
                            $city.="</select>";
                        } else {
                            $city = "No City Available";
                        }
                        //office pin code
                        $pin = $row['offi_pin'];

                        //office recuriment call status
                        $offi_rec = $row['offi_rec'];
                        $officeRecrument = "";
                        if ($offi_rec == "Yes")
                            $officeRecrument = "checked='checked'";
                        ?>
                        <form method="post" action="" id="editOfficeForm" onsubmit="return validateEditOffice();">
                            <div id="notificationMsg"></div>
                            <input type="hidden" value="<?php echo $offi_id; ?>" name="hid_offi_id" />                      
                            <p>
                                <label>Office Type</label> 
                                <select class="input-long" name="offi_type"><?php echo $officeType; ?></select>
                            </p>
                            <p>
                                <label>Board Line Number</label>
                                <input type="text" class="input-long" placeholder="Enter the Board Line Number" name="offi_board" value="<?php echo $boardLine; ?>"/>
                            </p>
                            <p>
                                <label>Address Line</label>            
                                <textarea rows="7" cols="90" name='txtAreaOffiAddr' class="input-short" placeholder="Write office address here" style="resize: none; width: 75%;"><?php echo $officeAddress; ?></textarea>                                
                            </p>                            
                            <p>
                                <label>Country</label> 
                                <select class="input-long" name="offi_country" onchange="fetchCityList(this.value);">
                                    <?php echo $officeCountry; ?>
                                </select>
                            </p>
                            <p>
                                <label>City</label> 
                                <span id="cityListContainer">
                                    <?php echo $city; ?>
                                </span>
                            </p>
                            <p>
                                <label>PIN</label> 
                                <input type="text" class="input-long" placeholder="Enter PIN Code" name="offi_pin" value="<?php echo $pin; ?>"/>
                            </p>
                            <p>
                                <label>
                                    <input type="checkbox" style="margin-top:10px;" name="rec_status" <?php echo $officeRecrument; ?>/> 
                                    Recruitment Calls are taken from this office
                                </label>
                            </p>
                            <fieldset>
                                <input class="submit-green" type="submit" name="btnUpdateOffice" value="Update Office" style="margin-left:30px;" />
                            </fieldset>
                        </form>    
                    </div>
                </div> 
            </div>
            <?php
        }
        ?>
        <script>
            function validateEditOffice() {
                var msgDiv = document.getElementById("notificationMsg");
                var msgSpan = document.createElement("span");
                if (document.getElementById("notificationMsg").innerHTML !== "") {
                    msgDiv.innerHTML = "";
                }

                var form = document.getElementById("editOfficeForm");
                if (form.offi_board.value === "") {
                    msgSpan.setAttribute("class", "notification n-error");
                    msgSpan.innerHTML = "Please enter the Broad Line Number";
                    msgDiv.appendChild(msgSpan);
                    form.offi_board.focus();
                    return false;
                }
                if (form.txtAreaOffiAddr.value === "") {
                    msgSpan.setAttribute("class", "notification n-error");
                    msgSpan.innerHTML = "Please enter the office address";
                    msgDiv.appendChild(msgSpan);
                    form.txtAreaOffiAddr.focus();
                    return false;
                }
            }
            function fetchCityList(country) {
                //alert(compId);
                //alert(country);
                var contentArea = document.getElementById("cityListContainer");
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
                        var data = xmlhttp.responseText;
                        if (data === "No City Available")
                            contentArea.innerHTML = "Please Contact Admin to add the City to the Database";
                        else
                            contentArea.innerHTML = "<select class='input-long' name='offi_city'>" + data + "</select>";
                    }
                };
                xmlhttp.open('GET', 'ajax_cityComboList.php?country=' + country, true);
                xmlhttp.send();
            }
        </script>
    </body>
</html>