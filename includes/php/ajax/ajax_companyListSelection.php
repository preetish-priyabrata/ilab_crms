<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$type = $_GET['type'];
if ($type == "details by office") {
    ?>
    <p>
        <label>Please select a Company</label>
        <select name="txtCompSearchBox" class="input-long" onchange="fetchOfficeList(document.getElementsByName('txtCompSearchBox')[0].value, 'checkbox');">
            <?php include './ajax_companyComboList.php'; ?>
        </select>
    </p>
    <?php
} else if ($type == "activities by company") {
    ?>
    <p>
        <label>Please select a Company</label>
        <select name="txtCompSearchBox" class="input-long">
            <?php include './ajax_companyComboList.php'; ?>
        </select>
    </p>

<?php } else if ($type == "scheduled items") { ?>
    <p>
        <label>Please select a Company</label>
        <select name="txtCompSearchBox" class="input-long">
            <?php include './ajax_companyComboList.php'; ?>
        </select>
    </p>
<?php } else if ($type == "activities by contact") { ?>
    <p>
        <label>Please select a Company</label>
        <select name="txtCompSearchBox" class="input-long" onchange="fetchContactListByComp(document.getElementsByName('txtCompSearchBox')[0].value, 'radio', 'activity');">
            <?php include './ajax_companyComboList.php'; ?>
        </select>
    </p>
<?php } else if ($type == "new") {
    ?>
    <p>
        <label>Please select a Company</label>
        <select name="txtCompSearchBox" class="input-long">
            <?php include './ajax_companyComboList.php'; ?>
        </select>
    </p>

<?php } else if ($type == "view") { ?>
    <p>
        <label>Please select a Company</label>
        <select name="txtCompSearchBox" class="input-long" style='width: 100%;' onchange="fetchContactListByComp(this.value, 'checkbox', 'contact');">
            <?php include './ajax_companyComboList.php'; ?>
        </select>

    </p>
<?php } else if ($type == "myCompany") { ?>
    <p>
        <label>Please select a Company</label>
        <select name="txtCompSearchBox" class="input-long" style='width: 100%;' onchange="fetchContactListByComp(this.value, 'checkbox', 'contact');">
            <?php include './ajax_myCompanyComboList.php'; ?>
        </select>

    </p>
<?php } ?>