<?php
include_once '../db.php';


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
                        