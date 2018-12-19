<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>CRM :: Add new Event/Notice</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- List of style sheets to be included-->
        <!-- CSS Reset -->
        <link rel="stylesheet" type="text/css" href="../../reset.css" media="screen" />

        <!-- Fluid 960 Grid System - CSS framework -->
        <link rel="stylesheet" type="text/css" href="../../grid.css" media="screen" />

        <!-- IE Hacks for the Fluid 960 Grid System -->
        <!--[if IE 6]><link rel="stylesheet" type="text/css" href="../../ie6.css"  media="screen" /><![endif]-->
        <!--[if IE 7]><link rel="stylesheet" type="text/css" href="../../ie.css" media="screen" /><![endif]-->

        <!-- Main stylesheet -->
        <link rel="stylesheet" type="text/css" href="../../styles.css"  media="screen" />
    </head>
    <body>
        <br>       
        <div class="grid_12" style="width: 450px;">

            <div class="module">
                <h2><span>Add New Event/Notice</span></h2>

                <div class="module-body">
                    <div id="successNotice"></div>
                    <?php
                    include_once 'db.php';
                    if ((isset($_SESSION['empId']) && $_SESSION['empType'] == "Admin")) {
                        ?>
                    <form name="frmAddEvent" action="" method="post" enctype="multipart/form-data" onsubmit="return eventValidate();" autocomplete="off">               
                            <p>
                                <label>Subject</label>
                                <input type="text" name="txtSubject" class="input-short" style="width: 65%" placeholder="Enter subject here" autofocus/>
                            </p>

                            <p>
                                <label>Type</label>
                                <select name="cmbType" class="input-short" style="width:50%">
                                    <option value="NA">Select the type</option>
                                    <option value="Event">Event</option>
                                    <option value="Notice">Notice</option>                                
                                </select>
                            </p>      

                            <p>
                                <label>Description</label>
                                <textarea rows="7" cols="90" name="txtAreaDesc" class="input-medium" style="width: 80%" placeholder="Enter the details here"></textarea>
                            </p>                        

                            <p>
                                <label>Upload Notice/Event: <span style="color: red; font-weight:bold;">(*Please upload a PDF/JPG file)</span></label>
                                <input type="file" name="fileNotice">
                            </p>

                            <fieldset>
                                <input class="submit-green" type="submit" name="btnSubmit" value="Publish" /> 
                                <input class="submit-gray" type="reset" name="btnReset" value="Clear" />
                            </fieldset>
                        </form>                        
                        <script>
                            function eventValidate() {
                                var msgDiv = document.getElementById("successNotice");
                                msgDiv.innerHTML = "";
                                var msgSpan = document.createElement("span");
                                msgDiv.appendChild(msgSpan);
                                if (document.frmAddEvent.txtSubject.value === "") {
                                    msgSpan.setAttribute("class", "notification n-error");
                                    msgSpan.innerHTML = "Subject should not empty.";
                                    document.frmAddEvent.txtSubject.focus();
                                    return false;
                                }
                                else if (document.frmAddEvent.cmbType.value === "NA") {
                                    msgSpan.setAttribute("class", "notification n-error");
                                    msgSpan.innerHTML = "Please selet a type";
                                    document.frmAddEvent.cmbType.focus();
                                    return false;
                                }
                                else if (document.frmAddEvent.txtAreaDesc.value === "") {
                                    msgSpan.setAttribute("class", "notification n-error");
                                    msgSpan.innerHTML = "Please enter Description.";
                                    document.frmAddEvent.txtAreaDesc.focus();
                                    return false;
                                }
                            }
                        </script> 
                        <?php
                        if (isset($_POST['btnSubmit']) && !empty($_POST['btnSubmit'])) {
                            $heading = mysqli_real_escape_string($con, $_POST['txtSubject']);
                            $event_type = $_POST['cmbType'];
                            $event_details = nl2br(mysqli_real_escape_string($con, $_POST['txtAreaDesc']));
                            $empId = $_SESSION['empId'];
                            $current_timestamp = time();

                            $sql = "INSERT INTO `tbl_event` (`evet_id`, `evet_heading`, `evet_type`, `evet_date`, `evet_details`, `evet_link`, `empl_id`) VALUES (NULL, '$heading', '$event_type', $current_timestamp, '$event_details', '', '$empId');";
                            //echo $sql;
                            $res = mysqli_query($con, $sql);
                            $event_id = mysqli_insert_id($con);

                            //var_dump($_FILES);
                            if (isset($_FILES[fileNotice]["name"]) && !empty($_FILES[fileNotice]["name"])) {
                                $fileName = $_FILES["fileNotice"]["name"];
                                $allowedExts = array("pdf", "jpeg", "jpg");
                                $extension = end(explode(".", $fileName));
                                if ((($_FILES["fileNotice"]["type"] == "application/pdf") || ($_FILES["fileNotice"]["type"] == "image/jpeg") || ($_FILES["fileNotice"]["type"] == "image/jpg") || ($_FILES["fileNotice"]["type"] == "image/pjpeg")) && in_array($extension, $allowedExts)) {
                                    $fileName = $event_type . $event_id . "." . $extension;
                                    $destination = "../../images/notice/" . $event_type . $event_id . "." . $extension;
                                    $upload_result = move_uploaded_file($_FILES["fileNotice"]["tmp_name"], $destination);
                                    if ($upload_result) {
                                        $sql_image_update = "UPDATE `tbl_event` SET `evet_link` = '$fileName' WHERE `tbl_event`.`evet_id` = $event_id;";
                                        $res_image_upload = mysqli_query($con, $sql_image_update);
                                        if ($res_image_upload) {
                                            ?>
                                            <script>
                                                var msgDiv = document.getElementById("successNotice");
                                                msgDiv.innerHTML = "";
                                                var msgSpan = document.createElement("span");
                                                msgSpan.setAttribute("class", "notification n-success");
                                                msgSpan.innerHTML = "<?php echo $event_type; ?>" + " added successfully.";
                                                msgDiv.appendChild(msgSpan);
                                            </script>
                                            <?php
                                        } else {
                                            $sql_delete_notice = "DELETE FROM `tbl_event` WHERE `evet_id` = $event_id";
                                            $res_delete_notice = mysqli_query($con, $sql_delete_notice);
                                            //echo $res_delete_notice;
                                            ?>
                                            <script>
                                                var msgDiv = document.getElementById("successNotice");
                                                msgDiv.innerHTML = "";
                                                var msgSpan = document.createElement("span");
                                                msgSpan.setAttribute("class", "notification n-error");
                                                msgSpan.innerHTML = "Error while uplaoding image data in the database.";
                                                msgDiv.appendChild(msgSpan);
                                            </script>
                                            <?php
                                        }
                                    } else {
                                        $sql_delete_notice = "DELETE FROM `tbl_event` WHERE `evet_id` = $event_id";
                                        $res_delete_notice = mysqli_query($con, $sql_delete_notice);
                                        ;
                                        ?>
                                        <script>
                                            var msgDiv = document.getElementById("successNotice");
                                            msgDiv.innerHTML = "";
                                            var msgSpan = document.createElement("span");
                                            msgSpan.setAttribute("class", "notification n-error");
                                            msgSpan.innerHTML = "Error while uplaoding image to server.";
                                            msgDiv.appendChild(msgSpan);
                                        </script>
                                        <?php
                                    }
                                } else {
                                    $sql_delete_notice = "DELETE FROM `tbl_event` WHERE `evet_id` = $event_id";
                                    $res_delete_notice = mysqli_query($con, $sql_delete_notice);
                                    ?>
                                    <script>
                                        var msgDiv = document.getElementById("successNotice");
                                        msgDiv.innerHTML = "";
                                        var msgSpan = document.createElement("span");
                                        msgSpan.setAttribute("class", "notification n-error");
                                        msgSpan.innerHTML = "Please upload a PDF/JPG file.";
                                        msgDiv.appendChild(msgSpan);
                                    </script>
                                    <?php
                                }
                            } else {
                                ?>
                                <script>
                                    var msgDiv = document.getElementById("successNotice");
                                    msgDiv.innerHTML = "";
                                    var msgSpan = document.createElement("span");
                                    msgSpan.setAttribute("class", "notification n-success");
                                    msgSpan.innerHTML = "<?php echo $event_type; ?>" + " added successfully.";
                                    msgDiv.appendChild(msgSpan);
                                </script>
                                <?php
                            }
                        }
                    } else {
                        ?>
                        <script>
                            var msgDiv = document.getElementById("successNotice");
                            msgDiv.innerHTML = "";
                            var msgSpan = document.createElement("span");
                            msgSpan.setAttribute("class", "notification n-error");
                            msgSpan.innerHTML = "Please login as a Administrator to add notice";
                            msgDiv.appendChild(msgSpan);
                        </script>
                        <?php
                    }
                    ?>                 
                </div> <!-- End .module-body -->

            </div>  <!-- End .module -->

        </div>        
    </body>
</html>
