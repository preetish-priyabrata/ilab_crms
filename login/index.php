<?php
session_start();
include_once "../includes/php/db.php";

$password_status = 0;
if (!empty($_POST)) {
    $uid = mysqli_escape_string($con, $_POST['uid']);
    $pwd = mysqli_escape_string($con, $_POST['pwd']);
    $sql = "SELECT * FROM `tbl_employee` te,tbl_setting ts WHERE te.`empl_userId`='$uid' AND te.`password`='$pwd' and ts.sett_type='cont_view' ";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_array($res);
        $_SESSION['empId'] = $row['empl_id'];
        $empId = $row['empl_id'];
        $_SESSION['empType'] = $row['empl_type'];
        $_SESSION['empName'] = $row['empl_name'];
        if ($row['empl_type'] != "Admin")
            $_SESSION['contViewSetting'] = $row['sett_value'];
        else
            $_SESSION['contViewSetting'] = "Yes";
        $currDate = date("Y-m-d");
        $now = date('Y-m-d H:i:s');
        $login_sql = "INSERT INTO `tbl_login` (`login_id`, `login`, `logout`, `login_date`, `empl_id`) VALUES (NULL, '$now', '', '$currDate', '$empId');";
        $login_res = mysqli_query($con, $login_sql);
        $_SESSION['login_id'] = mysqli_insert_id($con);
        //if ($login_res)
        header("Location: ../admin.php");
    }
    else {
        $password_status = 1;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0">         
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        <title>KIIT UNIVERSITY :: CRM Login  </title>
        <meta name="description" content="KIIT Corporate Relationship Management CRM" />
        <meta name="keywords" content="kiit, university, crm, corporate, relationship, management, achutya, samanta, kalinga" />
        <meta name="author" content="Innovadors Lab Pvt. Ltd" />
        <link rel="shortcut icon" href="image/favicon.ico">
        <style type="text/css"><!--
            body {
                margin-top: 0px;
                margin-left: 0px;
                background-image: url(image/main-bg.jpg);
            }
            --></style>
        <link href="style.css" rel="stylesheet" type="text/css">
        <style type="text/css"><!--
            .style52 {
                color: #0066FF;
                font-weight: bold;
            }
            .style53 {color: #0033FF}
            .style54 {
                font-family: Arial, Helvetica, sans-serif;
                font-weight: bold;
                font-size: 24px;
                color: #FFFFFF;
            }
            .style55 {color: #FFFFFF; font-weight: bold; font-size: 14; }
            .style58 {font-size: 12px}
            .style59 {
                color: #0066FF;
                font-size: 16px;
            }
            --></style>
    </head>
    <body>
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="1000">
            <tbody>
                <tr>
                    <td bgcolor="0C3E74" height="0" valign="top"><br>
                    </td></tr>
                <tr>
                    <td height="0" valign="top">
                        <table border="0" cellpadding="10" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td background="image/bl1.jpg" height="0" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td><img alt="" src="image/top1.jpg" height="76" width="322"></td>
                                                    <td>
                                                        <div class="style54" align="right">Corporate Relationship Management (CRM) </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="0" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td colspan="2" background="image/bdot.jpg" bgcolor="0C3E74" height="0" valign="top">
                                        <div id="menu-holder">
                                            <!-- saved from url=(0032)http://kiit.ac.in/caas/menu.html -->

                                            <meta content="text/html; charset=windows-1252" http-equiv="Content-Type">
                                            <meta name="GENERATOR" content="MSHTML 8.00.6001.19403">




                                        </div>

                                    </td>
                                </tr>
                                <tr>
                                    <td background="image/bl2.jpg" height="16" valign="top" width="73%">
                                        <div align="right"><a href="vacancy.html"></a></div><br>
                                    </td></tr>
                                <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" height="0" valign="top"><img alt="" src="image/PIXEL.jpg" height="7" width="7"></td></tr>
                                <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" height="0" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td valign="top">
                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td valign="top" width="77%" style="height: 400px; ">
                                                                        <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="linefont" align="left" valign="top">
                                                                                        <table border="0" cellpadding="0" cellspacing="0" width="1026">
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <td width="700">
                                                                                                        <div style="
                                                                                                             margin-top: 50px;
                                                                                                             ">
                                                                                                            <div>
                                                                                                                <div class="style55" align="center">
                                                                                                                    <br>                                                                                                       
                                                                                                                    <form class="form-4" name="form1" method="post" action="" autocomplete="off">                                                    
                                                                                                                        <div id="wrapper">
                                                                                                                            <div id="wrappertop"></div>
                                                                                                                            <div id="wrappermiddle">
                                                                                                                                <h2>User Login</h2>
                                                                                                                                <div id="username_input">
                                                                                                                                    <div id="username_inputleft"></div>
                                                                                                                                    <div id="username_inputmiddle">
                                                                                                                                        <input type="text" name="uid" id="url" placeholder="User Name" required autofocus/>
                                                                                                                                        <img id="url_user" src="./image/user.png" alt="">
                                                                                                                                    </div>
                                                                                                                                    <div id="username_inputright"></div>
                                                                                                                                </div>
                                                                                                                                <div id="password_input">
                                                                                                                                    <div id="password_inputleft"></div>
                                                                                                                                    <div id="password_inputmiddle">

                                                                                                                                        <input type="password" name="pwd" id="url" placeholder="Password" required />
                                                                                                                                        <img id="url_password" src="./image/passicon.png" alt="">

                                                                                                                                    </div>
                                                                                                                                    <div id="password_inputright"></div>
                                                                                                                                </div>
                                                                                                                                <div id="submit">
                                                                                                                                    <input type="submit" class="btn_submit" value="SIGN IN" />
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            <div id="wrapperbottom"></div>
                                                                                                                        </div>
                                                                                                                        <?php
                                                                                                                        if ($password_status == 1) {
                                                                                                                            echo "<p style='text-align: center;color: red;font-weight: bold;'>Please enter correct Username and Password</p>";
                                                                                                                        }
                                                                                                                        ?>
                                                                                                                    </form>                                               </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </td>

                                                                                                </tr>
                                                                                            </tbody></table></td></tr>
                                                                            </tbody></table></td></tr></tbody></table></td></tr></tbody></table></td></tr>
                                <tr>
                                    <td colspan="2" background="./image/bl.jpg" valign="top">
                                        <p class="footer" align="center"> Site designed &amp; maintained by Innovadors Lab visit <a href="http://www.innovadorslab.com" target="_blank">www.innovadorslab.com</a></p></td></tr></tbody></table></td></tr></tbody></table>

    </body></html>