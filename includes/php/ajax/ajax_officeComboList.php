<?php
include_once '../db.php';
if (isset($_GET['task']) && $_GET['task'] == "officeDetail") {
    $officeId = $_GET['officeId'];
    $sqlOffice = "select * from tbl_office where offi_id=$officeId";
    //echo $sqlOffice;
    $resOffice = mysqli_query($con, $sqlOffice);
    $rowOffice = mysqli_fetch_array($resOffice);
    ?>
    <br />
    <p>
        <label>Office Type</label>
        <!--<input type="text" class="input-long" name="txtOfficeType" value="<?php echo $rowOffice['offi_type']; ?>" />-->
        <select name="cmbOfficeType1" class="input-long">
            <?php
            $sqlOfficeType = "select sett_value from tbl_setting where sett_type='offi_type'";
            $resOfficeType = mysqli_query($con, $sqlOfficeType);
            $officeType = "";
            while ($row = mysqli_fetch_array($resOfficeType)) {
                if ($row[0] == $office_type) {
                    $officeType.="<option selected>" . $row[0] . "</option>";
                } else {
                    $officeType.="<option>" . $row[0] . "</option>";
                }
            }
            echo $officeType;
            ?>
        </select>
        <input type="hidden" name="hid_cmbOfficeType1" value='<?php echo $rowOffice['offi_type']; ?>'/>
    </p>
    <p>
        <label>Board Line Number</label>
        <input type="text" class="input-long" name="txtOffiBoardLine1" value="<?php echo $rowOffice['offi_boardline']; ?>" />
        <input type="hidden" name="hid_txtOffiBoardLine1" value ="<?php echo $rowOffice['offi_boardline']; ?>" />
    </p>
    <p>
        <label>Address </label> 
        <textarea class="input-long" name="areaOfficeAddress1" rows="4" cols="45"><?php echo $rowOffice['offi_address']; ?></textarea>
        <input type="hidden" name="hid_areaOfficeAddress1" value="<?php echo $rowOffice['offi_address']; ?>"/>
    </p>

    <p>
        <label>Country</label> 
        <!-- <input type="text" class="input-long" name="txtCountry" value="<?php echo $rowOffice['offi_country']; ?>" />-->
        <select class="input-long" name="cmbCountry1" onchange="fetchCityList(this.value,'existing')">
            <?php if ($rowOffice['offi_country'] == 'India') { ?>
                <option selected>India</option>
                <option>Other</option>
            <?php } else { ?>
                <option>India</option>
                <option selected>Other</option>
            <?php } ?>
        </select>                                    
        <input type="hidden" name="hid_cmbCountry1" value="<?php echo $rowOffice['offi_country']; ?>" />
    </p>
    <p>
        <label>City</label> 
        <!--<input type="text" name="txtCity" class="input-long" value="<?php echo $rowOffice['offi_city']; ?>" /> -->
        <span id="cityListContainer-existing">
            <?php
            $sqlAddress = "select addr_city from tbl_address where addr_country='India' order by addr_city asc";
            $resAddress = mysqli_query($con, $sqlAddress);
            if (mysqli_num_rows($resAddress) > 0) {
                $city = "<select class='input-long' name='cmbCity1'>";
                while ($row = mysqli_fetch_array($resAddress)) {
                    if ($rowOffice['offi_city'] == $row[0]) {
                        $city.="<option selected>" . $row[0] . "</option>";
                    } else {
                        $city.="<option>" . $row[0] . "</option>";
                    }
                }
                $city.="</select>";
            } else {
                $city = "No City Available";
            }
            echo $city;
            ?>
        </span>   
        <input type="hidden" name="hid_cmbCity1" value="<?php echo $rowOffice['offi_city']; ?>" >
    </p>    
    <p>
        <label>PIN</label> 
        <input type="text" name="txtPin1" class="input-long" <?php
        if ($rowOffice['offi_pin'] != '') {
            $pin = $rowOffice['offi_pin'];
            echo "value='$pin'";
        } else {
            echo "placeholder='Not Available'";
        }
        ?>>
        <input type="hidden" name="hid_txtPin1" value="<?php echo $rowOffice['offi_pin']; ?>"/>
    </p>
    <p>
        <?php
        if ($rowOffice['offi_rec'] == "Yes") {
            $recrument_status = "checked='checked'";
        } else {
            $recrument_status = "";
        }
        ?>
        <label><input type="checkbox" name="chkRecrument1" <?php echo $recrument_status; ?> value="Yes"/> Recruitment Calls are taken from this office</label>  
        <input type="hidden" name="hid_chkRecrument1" value="<?php echo $rowOffice['offi_rec']; ?>" >
    </p>
    <?php
} else {
    $id = $_GET['compId'];
    $tagType = $_GET['tagType'];
    $sql = "select offi_id,offi_type,offi_city from tbl_office where comp_id=$id order by offi_type ";
//echo $sql;
    $rs = mysqli_query($con, $sql);
    if (mysqli_num_rows($rs) > 0) {
        if ($tagType == "checkbox") {
            while ($row = mysqli_fetch_array($rs)) {
                echo "<input type='checkbox' value='" . $row[0] . "' name='offi_list' onchange=\"deSelectAllEle('allOffice',this.checked)\"  checked='checked' />" . $row[1] . " - " . $row[2] . "<br />";
            }
            ?>
            <input type='checkbox' value='All' id='allOffice'  checked='checked'  onchange="selectAllItems('allOffice', document.getElementsByName('offi_list'));"/>All<br /><br />
            <a href="javascript:void(0)" class="button" onclick="fetchOfficeDetail(document.getElementsByName('offi_list'));">
                <span>Fetch Office Details<img src="bin.gif" width="12" height="9" alt="Fetch Office List" /></span>
            </a>
            <?php
        } else {
            echo "<select class='input-long' name='offi_list' onchange='displaySelOfficeDetail(this.value)'>";
            echo "<option value='NA'>Select an Office</option>";
            while ($row = mysqli_fetch_array($rs)) {
                echo "<option value='" . $row[0] . "'>" . $row[1] . " - " . $row[2] . "</option>";
            }
            echo "</select>";
        }
    } else {
        echo "<p>
        <input type='hidden' value='NA' name='offi_list' />
        <br /><br />No existing Offices have been added till date for this comapny. Please continue with the addition of a New Office</p>";
    }
}
?>
