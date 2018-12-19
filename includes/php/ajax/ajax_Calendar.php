<?php
/* This file is called 2 times. First, when the user first time open the calendar. (ie. from displaySubMen_1.js)
  Second, when the user refresh the calendar through the from of the calendar(ie. from calanderAction.js). */

// Including the file which contains database configuration for the application 
include_once '../db.php';
// Including the file which contains pagination configuration for the application 
include_once '../travelPlan_pagination.php';
include_once '../time.php';
// Start Session 
session_start();
// Assigning the Employee ID session variable 
$ownEmpId = $_SESSION['empId'];

if (isset($_GET['cntAction']) && !empty($_GET['cntAction'])) {
    // Now get the url get variable send from the "displaySubMenu_1.js" file through ajax call
    $calendarContent = $_GET['cntAction'];
    if ($calendarContent == "calendar") {
        // This gets today's date 
        $date = time();
        // Assign the session Employee ID 
        $empId = $ownEmpId;

        // This if statement executes when the calendar is refrshed
        if (isset($_GET['empId']) && !empty($_GET['empId'])) {
            // Assign the selected empoyee id through the calendar form send through the ajax call
            $empId = $_GET['empId'];
        }

        // Initialize the month and year variable
        $month = 0;
        $year = 0;

        // this if section execute while ajax call        
        if (isset($_GET['month']) && isset($_GET['year'])) {
            // Assign the selected the month and year through the calendar form
            $month = $_GET['month'];
            $year = $_GET['year'];
        } else {
            // This puts the current day, month, and year in seperate variables 
            //$day = date('d', $date);                 
            $month = date('m', $date);
            $year = date('Y', $date);
        }

        //It will only add the zero if it's a single characters for comparing it with today's date
        $month = sprintf("%02s", $month);

        // Here we generate the first day of the month 
        $first_day = mktime(0, 0, 0, $month, 1, $year);

        // This gets us the month name 
        $title = date('F', $first_day);

        // Here we find out what day of the week the first day of the month falls on 
        $day_of_week = date('D', $first_day);

        /*         * ***
         * Once we know what day of the week it falls on, we know how many blank days occure before it. 
         * If the first day of the week is a Sunday then it would be zero 
         * ** */
        switch ($day_of_week) {
            case "Sun": $blank = 0;
                break;
            case "Mon": $blank = 1;
                break;
            case "Tue": $blank = 2;
                break;
            case "Wed": $blank = 3;
                break;
            case "Thu": $blank = 4;
                break;
            case "Fri": $blank = 5;
                break;
            case "Sat": $blank = 6;
                break;
        }
        // We then determine how many days are in the current month
        $days_in_month = cal_days_in_month(0, $month, $year);
        ?>
        <!-- Div element containing the calendar, color notification section, and the travel plan section  -->
        <div class="divCalendar" style="margin:auto; width:100%;">    
            <!-- Table containing the calendar -->
            <table class="mainTableTOC" cellspacing="1" cellpadding="0">
                <tr> <!-- This table row contains all the calendar form elements like employee list, month, year combo-box -->
                    <td class="monthYearRowTOC" colspan="7">  
                        <!-- Table containing the header section of the calendar: month with year and the input form section -->
                        <table width="100%" class="mainTableHeader">
                            <tr>
                                <!-- This TD element will display the selected month and year -->
                                <td class="monthYearTextTOC"><?php echo "$title $year"; ?> </td>  
                                <!-- This TD element will display all the input element to change the calendar -->
                                <td style="text-align: right;">  
                                    <!-- This combobox display the Employee List -->
                                    <select name="cmbEmpLst" class="input-short" id="cmbEmpLst" style="font-size: 10px; width: 25%">
                                        <?php
                                        // select the "Self" option in the drop down for the first time when the calendar is open
                                        if ($empId == $ownEmpId) {
                                            echo "<option value='$ownEmpId' selected>Self</option>";
                                        } else {
                                            echo "<option value='$ownEmpId'>Self</option>";
                                        }
                                        // this section display the 2nd all option in the employee list
                                        if ($empId == "all") {
                                            echo "<option value='all' selected>All</option>";
                                        } else {
                                            echo "<option value='all'>All</option>";
                                        }
                                        // sql statement to displays the list of all CRM app user as the ascending order of their name
                                        $emeployeeSql = "SELECT `empl_id`,`empl_name` FROM `tbl_employee` order by empl_name asc";
                                        $employeeRes = mysqli_query($con, $emeployeeSql);
                                        // this displays the list of all employee in the employee list combo-box
                                        while ($employeeRow = mysqli_fetch_array($employeeRes)) {
                                            // this else section displays all the all employee except the "Self"
                                            if ($employeeRow[0] != $ownEmpId) {
                                                if ($empId == $employeeRow[0]) {
                                                    echo "<option value='$employeeRow[0]' selected>$employeeRow[1]</option>";
                                                } else {
                                                    echo "<option value='$employeeRow[0]'>$employeeRow[1]</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>                                    
                                    <!-- This combobox display the Month List -->
                                    <select name="cmbMonth" class="input-short" id="cmbMonth" style="font-size: 10px; width: 15%">
                                        <?php
                                        //This get the 12 months inside a option tag
                                        for ($monthNum = 1; $monthNum <= 12; $monthNum++) {
                                            //this convert a month number to a month name
                                            $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
                                            // this if section select the current month in the combobox.
                                            if ($monthName == $title) {
                                                echo "<option value='$monthNum' selected>$monthName</option>";
                                            } else {
                                                echo "<option value='$monthNum'>$monthName</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <!-- This combobox dispaly the Year List from 2009 upto current year+1 -->
                                    <select name="cmbYear" class="input-short" id="cmbYear" style="font-size: 10px; width: 15%">
                                        <?php
                                        // Get the current year
                                        $currentYear = date('Y', $date);
                                        $lastYear = 0;
                                        // this for sectino display the list of year from 2009 to next year inside the year combo-box
                                        for ($yearNum = 2009, $firstYear = $yearNum, $lastYear = $currentYear + 1; $yearNum <= $lastYear; $yearNum++) {
                                            // ths if section selects the current year in the year combo-box
                                            if ($yearNum == $year) {
                                                echo "<option selected>$yearNum</option>";
                                            } else {
                                                echo "<option>$yearNum</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <!-- Hidden input element to store the last year form the year list ie. current year+1 -->
                                    <input type='hidden' name='hidLastYear' id='hidLastYear' value='<?php echo $lastYear; ?>' />
                                    <!-- Hidden input element to store the first year from the year list ie. 2009 -->
                                    <input type='hidden' name='hidFirstYear' id='hidFirstYear' value='<?php echo $firstYear; ?>' />
                                    <!-- Button  on click run a javascript function which is then refresh the calendar -->
                                    <input name="btnDateChange" type="button" class="submit-green" id="btnDateChange" value="Go" onclick="changeCalendar('goButton', 0, 0);" style="font-size: 10px;margin: 0 0 0 0">
                                    <!-- Button on click run a javascript function which is then display the previous month -->
                                    <input name="btnPrevMonth" type="button" id="btnPrevMonth" value="<<" class="submit-green" onclick="changeCalendar('prevButton', <?php echo $month; ?>, <?php echo $year; ?>);" style="font-size: 10px;margin: 0 0 0 0" title="Previous Month">
                                    <!-- Button on click run a javascript function which is then display the current month -->
                                    <input name="btnToday" type="button" id="btnToday" value="This Month" class="submit-green" onclick="changeCalendar('thisButton', 0, 0);" style="font-size: 10px;margin: 0 0 0 0">  
                                    <!-- Button on click which run a javascript function which is then display the next month -->
                                    <input name="btnNextMonth" type="button" id="btnNextMonth" value=">>" class="submit-green" onclick="changeCalendar('nextButton', <?php echo $month; ?>, <?php echo $year; ?>);" style="font-size: 10px;margin: 0 0 0 0" title="Next Month">                                
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>                
                <tr class="dayNamesTextTOC"> <!-- This table row displays all days in the first row of the calendar  -->
                    <td class="dayNamesRowTOC">Sunday</td>
                    <td class="dayNamesRowTOC">Monday</td>
                    <td class="dayNamesRowTOC">Tuesday</td>
                    <td class="dayNamesRowTOC">Wednesday</td>
                    <td class="dayNamesRowTOC">Thursday</td>
                    <td class="dayNamesRowTOC">Friday</td>
                    <td class="dayNamesRowTOC">Saturday</td>
                </tr>
                <?php
                // days counter to count the days of the week, which is initialized to 1 which counts up to 7        
                $day_count = 1;

                // display the first 2nd row which is the days list of the calendar
                echo "<tr class='rowsTOC'>";
                // this calculate how may days are in the previous month
                $days_in_prev_month = cal_days_in_month(0, $month - 1, $year);

                // This would be the first disabled previous month day of the calendar
                $pre_month_last_day = $days_in_prev_month - ($blank - 1);

                // then we take care of those blank days where displays the previous month dates
                while ($blank > 0) {
                    echo "<td class='sOtherTOC'>$pre_month_last_day</td>";
                    $blank = $blank - 1;
                    $day_count++;
                    $pre_month_last_day++;
                }
                // sets the first day of the month to 1 
                $day_num = 1;

                // assign the current date
                $today = date('Y-m-d', $date);

                // initialize the calendar color notification display variable, which is displayed the travel plan color notification below the calendar
                // initialize the variable with "none" first, these value of these variables are set to "block" if there is a travel plan the travel plan on a specific date
                $noTravelColorNotification = "none";
                $singleTravelColorNotification = "none";
                $multiTravelColorNotification = "none";
                $multiTravel_flag = 1;
                /* count up the days, check the reminder, appointment, activity, travel plan of the selected employee for that date
                 * and it will do like this untill we've done all of them in the month                
                 */
                while ($day_num <= $days_in_month) {
                    // now make the day into two digit fomat
                    $day_num = sprintf("%02s", $day_num);
                    // concatenate the year month day 
                    $date_day_num = "$year-$month-$day_num";
                    $travel_date_day_num_start_time = "$date_day_num 00:05:00";
                    $travel_date_day_num_end_time = "$date_day_num 23:55:55";

                    /*
                     * this if section check the the current day change the style of the head  of the date cell to current day color class
                     */
                    if ($date_day_num == $today) {
                        $dateDisplayClass = "todayTOC";
                    } else {
                        $dateDisplayClass = "daynumTOC";
                    }
                    // if user select any employee other than "All" option from the employee list
                    if ($empId != "all") {
                        // This section set the variable $num_row_chk_travel_plan, which has been used to check whether two employee are travelling to same place or not
                        // This query display the travel plan if the current date is in between any travel plan date of the selected employee of the employee combo box
                        $sql_chk_travel_plan = "SELECT trav_id, sett_id FROM `tbl_travel` "
                                . "WHERE (((`trav_start_date` between '$travel_date_day_num_start_time' and '$travel_date_day_num_end_time') "
                                . "             or (`trav_end_date` between '$travel_date_day_num_start_time' and '$travel_date_day_num_end_time')) "
                                . "         or (('$travel_date_day_num_start_time' between `trav_start_date` and `trav_end_date`) "
                                . "             or ('$travel_date_day_num_end_time' between `trav_start_date` and `trav_end_date`))) "
                                . "     and empl_id = $empId";
                        $res_chk_travel_plan = mysqli_query($con, $sql_chk_travel_plan);
                        /* store the total number of travel plan for current date to a particular destination
                         * This variable is used to change the cell color of a cell according to the travel plan
                         * if == 0, then change cell background to default color
                         * if == 1, then change cell background to pink color                    
                         */
                        $num_row_chk_travel_plan = mysqli_num_rows($res_chk_travel_plan);
                        $num_trav = 0;
                        // to store travel destination address
                        $trav_dest = "";
                        while ($row_chk_travel_plan = mysqli_fetch_array($res_chk_travel_plan)) {
                            // if any travel plan exists then capture its destination
                            $travel_destination = $row_chk_travel_plan['sett_id'];
                            // to display the travel destination name
                            $trav_dest_sql = "SELECT sett_value FROM `tbl_setting` WHERE `sett_id` = $travel_destination";
                            $trav_dest_res = mysqli_query($con, $trav_dest_sql);
                            $trav_dest_row = mysqli_fetch_array($trav_dest_res);
                            if ($num_trav > 0) {
                                $trav_dest .= ", " . $trav_dest_row[0];
                            } else {
                                $trav_dest = $trav_dest_row[0];
                            }
                            $num_trav++;
                        }
                        // query to display if other than the selected employee of the combobox is travelling to the same destination
                        $sql_multi_trav_desti_chk = "SELECT trav_id FROM `tbl_travel` WHERE (('$travel_date_day_num_start_time' between `trav_start_date` and `trav_end_date`) or ('$travel_date_day_num_end_time' between `trav_start_date` and `trav_end_date`)) and empl_id <> $empId and sett_id = $travel_destination";
                        $res_multi_trav_desti_chk = mysqli_query($con, $sql_multi_trav_desti_chk);
                        if (mysqli_num_rows($res_multi_trav_desti_chk) > 0) {
                            $num_row_chk_travel_plan++;
                        }
                        // used for multiple travel plan on a single date
                        // used to make the text of the travel plan bold
//                    $multi_trav_same_destn = "";
//                    // used to display the bold notification
//                    $multiMemberTravel = "none";
                        // store the calendar date cell color value except the weekend day
                        $calendarTdClass = "";
                        // store the calendar date cell color value of the weekend day
                        $calendarWeekEndTdClass = "";
                        // store the modalbox dispaly value
                        $displayTravelPlanModalBox = "";
                        // store travel color notification dispaly value
                        $noTravelColorNotification = "";
                        // change the calendar date color according to the number of travel plan
                        // and change the date color notification dispaly value
                        if ($num_row_chk_travel_plan == 0) {
                            $calendarTdClass = "s20TOC";
                            $calendarWeekEndTdClass = "s20TOC0";
                            //$displayTravelPlanModalBox = "";
                            $noTravelColorNotification = "block";
                        } else { //($num_row_chk_travel_plan >= 1) {
                            $calendarTdClass = "s20TOCSingleTravel";
                            $calendarWeekEndTdClass = "s20TOCSingleTravel";
                            //$displayTravelPlanModalBox = "onclick=\"displayCalendarModalBox('includes/php/ajax/ajax_viewCalTravLst.php?date=$date_day_num&empId=$empId','Travel Plan List');\"";
                            $singleTravelColorNotification = "block";
                        }
//                    else {
//                        $calendarTdClass = "s20TOCMultiTravel";
//                        $calendarWeekEndTdClass = "s20TOCMultiTravel";
//                        //$displayTravelPlanModalBox = "onclick=\"displayCalendarModalBox('includes/php/ajax/ajax_viewCalTravLst.php?date=$date_day_num&empId=$empId','Travel Plan List');\"";
//                        $multiTravelColorNotification = "block";
//                        // if another employee's travel plan is there to the same destination on the same date display "Travel Plan" text in bold
//                        $multiTravel_flag++;
//                        $multi_trav_same_destn = "multiTrav";
//                    }
                        // Query for counting the reminder of type Meeting ie. appointment with end date of the selected employee
                        $sql_appont_count = "SELECT count(*) FROM `tbl_todo` WHERE `todo_endDate` = '$date_day_num' and `empl_id` = $empId and todo_type='Meeting'";
                        $res_appont_count = mysqli_query($con, $sql_appont_count);
                        $rowAppont = mysqli_fetch_array($res_appont_count);
                        // appointment counter
                        $row_appont_count = $rowAppont[0];

                        // Query for counting the reminder of type Email and Telephone with end date of the selected employee
                        $sql_todo_count = "SELECT count(*) FROM `tbl_todo` WHERE `todo_endDate` = '$date_day_num' and `empl_id` = $empId and (todo_type='Email' or todo_type='Telephone / Conversation')";
                        $res_todo_count = mysqli_query($con, $sql_todo_count);
                        $rowTodo = mysqli_fetch_array($res_todo_count);
                        // reminder counter
                        $row_todo_count = $rowTodo[0];

                        // Query for counting the activity date of the selected employee
                        $sql_acivity_count = "SELECT count(*) FROM `tbl_emp_contact` WHERE `act_date` = '$date_day_num' and `empl_id` = $empId";
                        $res_acivity_count = mysqli_query($con, $sql_acivity_count);
                        $rowActivity = mysqli_fetch_array($res_acivity_count);
                        // activity counter
                        $row_acivity_count = $rowActivity[0];

                        // Query for counting the travel for the date of the selected employee
                        $sql_travel_count = "SELECT count(*) FROM `tbl_travel` WHERE (((`trav_start_date` between '$travel_date_day_num_start_time' and '$travel_date_day_num_end_time') or (`trav_end_date` between '$travel_date_day_num_start_time' and '$travel_date_day_num_end_time')) or (('$travel_date_day_num_start_time' between `trav_start_date` and `trav_end_date`) or ('$travel_date_day_num_end_time' between `trav_start_date` and `trav_end_date`))) and `empl_id` = $empId";
                        $res_travel_count = mysqli_query($con, $sql_travel_count);
                        $rowTravel = mysqli_fetch_array($res_travel_count);
                        // activity counter
                        $row_travel_count = $rowTravel[0];

                        // store the number of appointment 
                        $row_appont_count_string = '';
                        // store the number of reminder
                        $row_todo_count_string = '';
                        // store the number of activity 
                        $row_acivity_count_string = '';
                        // store the number of travel
                        $row_travel_count_string = '';
                        // if reminder of type meeting(appointment) or reminder or activity are there then display it
                        if ($row_todo_count > 0 || $row_acivity_count > 0 || $row_appont_count > 0 || $row_travel_count > 0) {
                            // if one or more appointment there in the date dispaly it
                            if ($row_appont_count > 0) {
                                // if there is only single appointment then it would display "Appointment"
                                if ($row_appont_count == 1) {
                                    $row_appont_count_string = "<a id='calanderAppont$day_num' class='calanderHoverAcivity' href='includes/php/ajax/ajax_viewCalContScheduleLst.php?include=style&date=$date_day_num&type=appointment&empId=$empId' title='View Appointment List' onclick='return displayModalBox(this.id);'>&nbsp;<span class='s23'>&nbsp;&nbsp;</span> $row_appont_count Appointment</a><br>";
                                }
                                // if multiple appointments then it would display "Appointment(s)"
                                else {
                                    $row_appont_count_string = "<a id='calanderAppont$day_num' class='calanderHoverAcivity' href='includes/php/ajax/ajax_viewCalContScheduleLst.php?include=style&date=$date_day_num&type=appointment&empId=$empId' title='View Appointment List' onclick='return displayModalBox(this.id);'>&nbsp;<span class='s23'>&nbsp;&nbsp;</span> $row_appont_count Appointments</a><br>";
                                }
                            }
                            // if one or more reminder there in this date dispaly it
                            if ($row_todo_count > 0) {
                                // if there is only single reminder then it would display "Reminder"
                                if ($row_todo_count == 1) {
                                    $row_todo_count_string = "<a id='calanderTodo$day_num' class='calanderHoverAcivity' href='includes/php/ajax/ajax_viewCalContScheduleLst.php?include=style&date=$date_day_num&empId=$empId' title='View Reminder List' onclick='return displayModalBox(this.id);'>&nbsp;<span class='s24'>&nbsp;&nbsp;</span> $row_todo_count Reminder</a><br>";
                                }
                                // if multiple reminders then it would display "Reminder(s)"
                                else {
                                    $row_todo_count_string = "<a id='calanderTodo$day_num' class='calanderHoverAcivity' href='includes/php/ajax/ajax_viewCalContScheduleLst.php?include=style&date=$date_day_num&empId=$empId'  title='View Reminder List' onclick='return displayModalBox(this.id);'>&nbsp;<span class='s24'>&nbsp;&nbsp;</span> $row_todo_count Reminders</a><br>";
                                }
                            }
                            // if multiple reminders then it would display "Reminder(s)"
                            if ($row_acivity_count > 0) {
                                // if there is only single activity then it would display "Activity"
                                if ($row_acivity_count == 1) {
                                    $row_acivity_count_string = "<a id='calanderActivity$day_num' href='includes/php/ajax/ajax_viewCalContActiLst.php?include=style&date=$date_day_num&empId=$empId' title='View Activity List' onclick='return displayModalBox(this.id);' class='calanderHoverAcivity'>&nbsp;<span class='s21'>&nbsp;&nbsp;</span> $row_acivity_count Activity</a><br>";
                                }
                                // if multiple activities then it would display "Activit(ies)"
                                else {
                                    $row_acivity_count_string = "<a id='calanderActivity$day_num' href='includes/php/ajax/ajax_viewCalContActiLst.php?include=style&date=$date_day_num&empId=$empId' title='View Activity List' onclick='return displayModalBox(this.id);' class='calanderHoverAcivity'>&nbsp;<span class='s21'>&nbsp;&nbsp;</span> $row_acivity_count Activities</a><br>";
                                }
                            }
                            // if multiple reminders then it would display "Reminder(s)"
                            if ($row_travel_count > 0) {
                                // if there is only single travel plan then it would display "font-weight: normal"
//                            if ($row_travel_count == 1)
                                $row_travel_count_string = "<a id='calanderTravel$day_num' href='includes/php/ajax/ajax_viewCalTravLst.php?date=$date_day_num&empId=$empId' title='View Travel Plan List' onclick='return displayModalBox(this.id);' class='calanderHoverAcivity'>&nbsp;<span class='s25'>&nbsp;&nbsp;</span> Travel to $trav_dest<br></a>";
//                            // if multiple employee traveling to same destination it would display "font-weight: bold"
//                            else
//                                $row_travel_count_string = "<a id='calanderTravel$day_num' href='includes/php/ajax/ajax_viewCalTravLst.php?date=$date_day_num' title='Travel Plan List' onclick='return displayModalBox(this.id);' class='calanderHoverAcivity $multi_trav_same_destn'>&nbsp;<span class='s25'>&nbsp;&nbsp;</span> Travel to $trav_dest<br></a>";
                            }
                            // if the date is either Saturday or Sunday then change the color of the date cell to Grey
                            if ($day_count == 7 || $day_count == 1) {
                                echo "<td class='$calendarWeekEndTdClass'><div class='$dateDisplayClass'> $day_num </div><span class='titleTOC'>$row_appont_count_string $row_todo_count_string $row_acivity_count_string $row_travel_count_string</span></td>";
                            }
                            // else change the date to light cyan
                            else {
                                echo "<td class='$calendarTdClass'><div class='$dateDisplayClass'> $day_num </div><span class='titleTOC'>$row_appont_count_string $row_todo_count_string $row_acivity_count_string $row_travel_count_string</span></a></td>";
                            }
                        }
                        // if the date has no appointment, reminders, activity
                        else {
                            // if the date is either Saturday or Sunday then the change the color of the date cell to Grey
                            if ($day_count == 7 || $day_count == 1) {
                                echo "<td class='$calendarWeekEndTdClass'><div class='$dateDisplayClass'> $day_num </div></td>";
                            }// else change the date to light cyan
                            else {
                                echo "<td class='$calendarTdClass'><div class='$dateDisplayClass'> $day_num </div></td>";
                            }
                        }
                    } else { // if user select the "All" option from the employee list combo-box  
                        // This section set the variable $num_row_chk_travel_plan, which has been used to check whether two employee are travelling to same place or not
                        // This query display the travel plan if the current date is in between any travel plan date of the selected employee of the employee combo box
                        $sql_chk_travel_plan = "SELECT trav_id, sett_id FROM `tbl_travel` "
                                . "WHERE (((`trav_start_date` between '$travel_date_day_num_start_time' and '$travel_date_day_num_end_time') "
                                . "             or (`trav_end_date` between '$travel_date_day_num_start_time' and '$travel_date_day_num_end_time')) "
                                . "         or (('$travel_date_day_num_start_time' between `trav_start_date` and `trav_end_date`) "
                                . "             or ('$travel_date_day_num_end_time' between `trav_start_date` and `trav_end_date`))) ";
                        $res_chk_travel_plan = mysqli_query($con, $sql_chk_travel_plan);
                        /* store the total number of travel plan for current date to a particular destination
                         * This variable is used to change the cell color of a cell according to the travel plan
                         * if == 0, then change cell background to default color
                         * if == 1, then change cell background to pink color                    
                         */
                        $num_row_chk_travel_plan = mysqli_num_rows($res_chk_travel_plan);
                        $calendarTdClass = "";
                        // store the calendar date cell color value of the weekend day
                        $calendarWeekEndTdClass = "";
                        // store the modalbox dispaly value
                        $displayTravelPlanModalBox = "";
                        // store travel color notification dispaly value
                        $noTravelColorNotification = "";
                        // change the calendar date color according to the number of travel plan
                        // and change the date color notification dispaly value
                        if ($num_row_chk_travel_plan == 0) {
                            $calendarTdClass = "s20TOC";
                            $calendarWeekEndTdClass = "s20TOC0";
                            //$displayTravelPlanModalBox = "";
                            $noTravelColorNotification = "block";
                        } else { //($num_row_chk_travel_plan >= 1) {
                            $calendarTdClass = "s20TOCSingleTravel";
                            $calendarWeekEndTdClass = "s20TOCSingleTravel";
                            //$displayTravelPlanModalBox = "onclick=\"displayCalendarModalBox('includes/php/ajax/ajax_viewCalTravLst.php?date=$date_day_num&empId=$empId','Travel Plan List');\"";
                            $singleTravelColorNotification = "block";
                        }

                        // Query for counting the reminder of type Meeting i.e. appointment with end date of all employee
                        $sql_appont_count = "SELECT count(*) FROM `tbl_todo` WHERE `todo_endDate` = '$date_day_num' and todo_type='Meeting'";
                        $res_appont_count = mysqli_query($con, $sql_appont_count);
                        $rowAppont = mysqli_fetch_array($res_appont_count);
                        // appointment counter
                        $row_appont_count = $rowAppont[0];

                        // Query for counting the reminder of type Email and Telephone with end date of all employee
                        $sql_todo_count = "SELECT count(*) FROM `tbl_todo` WHERE `todo_endDate` = '$date_day_num' and (todo_type='Email' or todo_type='Telephone / Conversation')";
                        $res_todo_count = mysqli_query($con, $sql_todo_count);
                        $rowTodo = mysqli_fetch_array($res_todo_count);
                        // reminder counter
                        $row_todo_count = $rowTodo[0];

                        // Query for counting the activity date of all employee 
                        $sql_acivity_count = "SELECT count(*) FROM `tbl_emp_contact` WHERE `act_date` = '$date_day_num'";
                        $res_acivity_count = mysqli_query($con, $sql_acivity_count);
                        $rowActivity = mysqli_fetch_array($res_acivity_count);
                        // activity counter
                        $row_acivity_count = $rowActivity[0];

                        // Query for counting the travel for the date of all employee
                        $sql_travel_count = "SELECT count(*) FROM `tbl_travel` WHERE (((`trav_start_date` between '$travel_date_day_num_start_time' and '$travel_date_day_num_end_time') or (`trav_end_date` between '$travel_date_day_num_start_time' and '$travel_date_day_num_end_time')) or (('$travel_date_day_num_start_time' between `trav_start_date` and `trav_end_date`) or ('$travel_date_day_num_end_time' between `trav_start_date` and `trav_end_date`)))";
                        $res_travel_count = mysqli_query($con, $sql_travel_count);
                        $rowTravel = mysqli_fetch_array($res_travel_count);
                        // activity counter
                        $row_travel_count = $rowTravel[0];

                        // store the number of appointment 
                        $row_appont_count_string = '';
                        // store the number of reminder
                        $row_todo_count_string = '';
                        // store the number of activity 
                        $row_acivity_count_string = '';
                        // store the number of travel
                        $row_travel_count_string = '';
                        // if reminder of type meeting(appointment) or reminder or activity are there then display it
                        if ($row_todo_count > 0 || $row_acivity_count > 0 || $row_appont_count > 0 || $row_travel_count > 0) {
                            // if one or more appointment there in the date dispaly it
                            if ($row_appont_count > 0) {
                                // if there is only single appointment then it would display "Appointment"
                                if ($row_appont_count == 1) {
                                    $row_appont_count_string = "<a id='calanderAppont$day_num' class='calanderHoverAcivity' href='includes/php/ajax/ajax_viewCalContScheduleLst.php?include=style&date=$date_day_num&type=appointment' title='View all Appointment List' onclick='return displayModalBox(this.id);'>&nbsp;<span class='s23'>&nbsp;&nbsp;</span> $row_appont_count Appointment</a><br>";
                                }
                                // if multiple appointments then it would display "Appointment(s)"
                                else {
                                    $row_appont_count_string = "<a id='calanderAppont$day_num' class='calanderHoverAcivity' href='includes/php/ajax/ajax_viewCalContScheduleLst.php?include=style&date=$date_day_num&type=appointment' title='View all Appointment List' onclick='return displayModalBox(this.id);'>&nbsp;<span class='s23'>&nbsp;&nbsp;</span> $row_appont_count Appointments</a><br>";
                                }
                            }
                            // if one or more reminder there in this date dispaly it
                            if ($row_todo_count > 0) {
                                // if there is only single reminder then it would display "Reminder"
                                if ($row_todo_count == 1) {
                                    $row_todo_count_string = "<a id='calanderTodo$day_num' class='calanderHoverAcivity' href='includes/php/ajax/ajax_viewCalContScheduleLst.php?include=style&date=$date_day_num' title='View all Reminder List' onclick='return displayModalBox(this.id);'>&nbsp;<span class='s24'>&nbsp;&nbsp;</span> $row_todo_count Reminder</a><br>";
                                }
                                // if multiple reminders then it would display "Reminder(s)"
                                else {
                                    $row_todo_count_string = "<a id='calanderTodo$day_num' class='calanderHoverAcivity' href='includes/php/ajax/ajax_viewCalContScheduleLst.php?include=style&date=$date_day_num' title='View all Reminder List' onclick='return displayModalBox(this.id);' >&nbsp;<span class='s24'>&nbsp;&nbsp;</span> $row_todo_count Reminders</a><br>";
                                }
                            }
                            // if multiple reminders then it would display "Reminder(s)"
                            if ($row_acivity_count > 0) {
                                // if there is only single activity then it would display "Activity"
                                if ($row_acivity_count == 1) {
                                    $row_acivity_count_string = "<a id='calanderActivity$day_num' href='includes/php/ajax/ajax_viewCalContActiLst.php?include=style&date=$date_day_num' title='View all Activity List' onclick='return displayModalBox(this.id);' class='calanderHoverAcivity'>&nbsp;<span class='s21'>&nbsp;&nbsp;</span> $row_acivity_count Activity</a><br>";
                                }
                                // if multiple activities then it would display "Activit(ies)"
                                else {
                                    $row_acivity_count_string = "<a id='calanderActivity$day_num' href='includes/php/ajax/ajax_viewCalContActiLst.php?include=style&date=$date_day_num' title='View all Activity List' onclick='return displayModalBox(this.id);' class='calanderHoverAcivity'>&nbsp;<span class='s21'>&nbsp;&nbsp;</span> $row_acivity_count Activities</a><br>";
                                }
                            }
                            // if multiple reminders then it would display "Reminder(s)"
                            if ($row_travel_count > 0) {
                                // if there is only single travel plan then it would display "font-weight: normal"
//                            if ($row_travel_count == 1)
                                $row_travel_count_string = "<a id='calanderTravel$day_num' href='includes/php/ajax/ajax_viewCalTravLst.php?date=$date_day_num' title='View all Travel Plan List' onclick='return displayModalBox(this.id);' class='calanderHoverAcivity'>&nbsp;<span class='s25'>&nbsp;&nbsp;</span> $row_travel_count Travel Plan<br></a>";
//                            // if multiple employee traveling to same destination it would display "font-weight: bold"
//                            else
//                                $row_travel_count_string = "<a id='calanderTravel$day_num' href='includes/php/ajax/ajax_viewCalTravLst.php?date=$date_day_num' title='Travel Plan List' onclick='return displayModalBox(this.id);' class='calanderHoverAcivity $multi_trav_same_destn'>&nbsp;<span class='s25'>&nbsp;&nbsp;</span> Travel to $trav_dest<br></a>";
                            }
                            // if the date is either Saturday or Sunday then change the color of the date cell to Grey
                            if ($day_count == 7 || $day_count == 1) {
                                echo "<td class='$calendarWeekEndTdClass'><div class='$dateDisplayClass'> $day_num </div><span class='titleTOC'>$row_appont_count_string $row_todo_count_string $row_acivity_count_string $row_travel_count_string</span></td>";
                            }
                            // else change the date to light cyan
                            else {
                                echo "<td class='$calendarTdClass'><div class='$dateDisplayClass'> $day_num </div><span class='titleTOC'>$row_appont_count_string $row_todo_count_string $row_acivity_count_string $row_travel_count_string</span></a></td>";
                            }
                        }
                        // if the date has no appointment, reminders, activity
                        else {
                            // if the date is either Saturday or Sunday then the change the color of the date cell to Grey
                            if ($day_count == 7 || $day_count == 1) {
                                echo "<td class='$calendarWeekEndTdClass'><div class='$dateDisplayClass'> $day_num </div></td>";
                            }// else change the date to light cyan
                            else {
                                echo "<td class='$calendarTdClass'><div class='$dateDisplayClass'> $day_num </div></td>";
                            }
                        }
                    }
                    $day_num++;
                    $day_count++;

                    // Make sure we start a new row every week
                    if ($day_count > 7) {
                        echo "</tr><tr class='rowsTOC'>";
                        $day_count = 1;
                    }
                }

                // Finaly we finish out the table with some blank details if needed
                // To display the date of the next month in a disabled screen date cell
                $next_month_days = 1;
                while ($day_count > 1 && $day_count <= 7) {
                    echo "<td class='sOtherTOC'>$next_month_days</td>";
                    $day_count++;
                    $next_month_days++;
                }
                echo "</tr>";
                ?>                
            </table> 

            <!-- Table to dispaly color notification -->
            <table style="border: 0px;margin-left: 50px;width: 90%">
                <tr>
                    <!--                        
                    <td>
                         Div to display No Travel Plan color notification 
                        <div  style="display: <?php //echo $noTravelColorNotification;   ?>;"><span style="background-color: #b7ecb3;border:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span> You have No Travel Plan</div>
                    </td>
                    -->
                    <td>
                        <!-- Div to display Single Travel Plan color notification -->
                        <div style="display: <?php echo $singleTravelColorNotification; ?>;"><span style="background-color: #f2c6f2;border:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span> - Travel Plan</div>
                    </td>
                    <td>
                        <!-- Div to display Multiple Travel Plan color notification -->
                        <div style="display: <?php echo $multiTravelColorNotification; ?>;"><span style="background-color: lightgoldenrodyellow;border:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span> Another member Traveling with you</div>
                    </td>
                    <td>
                        <!-- Div to display Multiple Travel Plan color notification -->
                        <div style="display: 
                        <?php
                        if ($multiTravel_flag > 1) {
                            echo $multiMemberTravel = "inline";
                        } else {
                            echo $multiMemberTravel = "none";
                        }
                        ?>;"><span style="font-weight: bolder;">Bold</span> - Multiple member traveling to same place.</div>
                    </td>
                </tr>
            </table>           
            <?php
            $monthStartDate = "$year-$month-01";
            $monthEndDate = "$year-$month-$days_in_month";
            $travelLstSql = "SELECT trav_id, trav_start_date, trav_end_date,sett_value FROM `tbl_travel` as tt,`tbl_setting` as ts WHERE trav_start_date between '$monthStartDate' and '$monthEndDate' and empl_id = $empId and tt.sett_id=ts.sett_id";
            $travelLstRes = mysqli_query($con, $travelLstSql);
            $i = 1;
            $travelLst1 = '';
            $travelLst2 = '';
            $travelLst3 = '';
            if (mysqli_num_rows($travelLstRes) > 0) {
                while ($travelLstRow = mysqli_fetch_array($travelLstRes)) {
                    $travId = $travelLstRow['trav_id'];
                    $travStartDate = date("d", strtotime($travelLstRow['trav_start_date']));
                    $travStartMonth = date("M", strtotime($travelLstRow['trav_start_date']));
                    $travEndDate = date("d", strtotime($travelLstRow['trav_end_date']));
                    $travEndMonth = date("M", strtotime($travelLstRow['trav_end_date']));
                    $travDestination = $travelLstRow['sett_value'];

                    $travDuration = "$travStartDate $travStartMonth - $travEndDate $travEndMonth";
                    $travelPlanModalBox = "onclick=\"displayCalendarModalBox('includes/php/ajax/ajax_viewCalTravLst.php?travelId=$travId&empId=$empId','Travel Plan to $travDestination');\"";

                    if ($i == 1) {
                        $travelLst1 .= "<tr>
                                            <td style='width: 25%;font-weight: bold;font-family: sans-serif cursive monospace; '>
                                                <span class='calanderHoverAcivity' $travelPlanModalBox style='color: black;'>$travDuration</span> 
                                            </td>
                                            <td style='width: 75%;'>
                                                <span class='calanderHoverAcivity' $travelPlanModalBox style='color: gray;'>Travel to $travDestination</span>
                                            </td>
                                        </tr>";
                    } elseif ($i == 2) {
                        $travelLst2 .= "<tr>
                                            <td style='width: 25%;font-weight: bold;font-family: sans-serif cursive monospace; '>
                                                <span class='calanderHoverAcivity' $travelPlanModalBox style='color: black;'>$travDuration</span> 
                                            </td>
                                            <td style='width: 75%;'>
                                                <span class='calanderHoverAcivity' $travelPlanModalBox style='color: gray;'>Travel to $travDestination</span>
                                            </td>
                                        </tr>";
                    } elseif ($i == 3) {
                        $travelLst3 .= "<tr>
                                            <td style='width: 25%;font-weight: bold;font-family: sans-serif cursive monospace; '>
                                                <span class='calanderHoverAcivity' $travelPlanModalBox style='color: black;'>$travDuration</span> 
                                            </td>
                                            <td style='width: 75%;'>
                                                <span class='calanderHoverAcivity' $travelPlanModalBox style='color: gray;'>Travel to $travDestination</span>
                                            </td>
                                        </tr>";
                        $i = 0;
                    }
                    $i++;
                }
                ?>
                <span style="font-size: 16;font-weight: bold; text-decoration: underline;font-style: italic;margin-bottom: 10px">Travel Plan</span>
                <table style="border: 0px;">
                    <tr style="border: 0px">
                        <td style="width: 33.33%;">
                            <table style="border: 0px">                                
                                <?php echo $travelLst1; ?>                                
                            </table>                        
                        </td>
                        <td style="width: 33.33%;">
                            <table style="border: 0px">                                
                                <?php echo $travelLst2; ?>                                
                            </table> 
                        </td>
                        <td style="width: 33.33%;">
                            <table style="border: 0px">                                
                                <?php echo $travelLst3; ?>                                
                            </table> 
                        </td>                    
                    </tr>
                </table>
                <?php
            }
            ?>            
        </div>
        <?php
    } else if ($calendarContent == "travel plan") {
        ?>
        <div id="todoSuccessMsg"></div>         
        <table style="border: 0px;width:100%">
            <tr width="100%">
                <td width="30%" style="border-right: none">
                    <form name='frmTravelPlan' method='post' id='frmTravelPlan'>                        
                        <input type="hidden" id="hidTravelId"/>
                        <p>
                            <label for="txtStartDate">Select a Date Range</label>                        
                            <b>Start Time</b> 
                            <input type="text" name='txtStartDay' id="txtStartDate" class='input-short' placeholder="Start Date" style="width: 30%;"/>                                
                            <select name="cmbStartTime" id="cmbStartTime">
                                <?php
                                echo $start_time;
                                ?>
                            </select>
                        </p>
                        <p>
                            <b>End Time</b>
                            <input type="text" name='txtEndDay' id="txtEndDate" class='input-short' placeholder="End Date" style="margin-left: 6px;width: 30%;" />                        
                            <select name="cmbEndTime" id="cmbEndTime">
                                <?php
                                echo $end_time;
                                ?>
                            </select>
                        </p>
                        <p>
                            <label>Select Destination</label>
                            <select class="input-short" style='width: 95%' name="cmbDestinationCity" id="cmbDestinationCity">
                                <option value="NA">Select a Destination</option>
                                <?php
                                $addrSql = "SELECT `sett_id`,`sett_value` FROM `tbl_setting` WHERE `sett_type`='travel' and `sett_status`='Active' order by sett_value asc";
                                $addrRes = mysqli_query($con, $addrSql);
                                while ($addrRow = mysqli_fetch_array($addrRes))
                                    echo "<option value='$addrRow[0]'>$addrRow[1]</option>";
                                ?>
                            </select>
                        </p>                         
                        <p>
                            <label>Description</label>
                            <textarea rows="7" cols="90" name='txtAreaDesc' id="txtAreaDesc" class="input-short" placeholder="Write your description here" style="resize: none; width: 95%;"></textarea>
                        </p>
                        <fieldset id="fldButton">
                            <input class="submit-green" type="button" onclick="saveTravelPlan();" name="btnTodoSubmit" value="Add" /> 
                            <input class="submit-gray" type="reset" name="btnTodoClear" value="Clear" />
                        </fieldset>  
                    </form>  
                </td>                                          
                <td width="70%" style="border-right: none">
                    <!--Refresh Button -->
                    <div>
                        <a href="javascript:void(0)" class="button" onclick="refreshTravelPlan();">
                            <span id="refreshButton">Refresh Travel Plan List <img src="bin.gif" width="16" height="12" alt="Display Travel Plan List" title="Display Travel Plan List"/></span>
                        </a>
                    </div>
                    &nbsp;
                    <div id="paginationTable">
                        <?php
                        if (isset($_SESSION['column_head']))
                            unset($_SESSION['column_head']);
                        if (isset($_SESSION['table_heading']))
                            unset($_SESSION['table_heading']);
                        if (isset($_SESSION['sql']))
                            unset($_SESSION['sql']);
                        $_SESSION['table_heading'] = "Travel Plan List View";
                        $_SESSION['column_head'] = array("#" => "5", "Start Date" => "15", "End Date" => "15", "Destination" => "20", "Description" => "25", "Edit" => "10");
                        //$_SESSION['sql'] = "SELECT `trav_id`,`trav_start_date`,`trav_end_date`,`sett_value`,`trav_desc`,tt.`sett_id` FROM `tbl_travel` as tt,`tbl_setting` as ts WHERE tt.empl_id=$ownEmpId and tt.sett_id=ts.sett_id and trav_end_date > curdate() order by trav_start_date asc";
                        $_SESSION['sql'] = "SELECT `trav_id`,`trav_start_date`,`trav_end_date`,`sett_value`,`trav_desc`,tt.`sett_id` FROM `tbl_travel` as tt,`tbl_setting` as ts WHERE tt.empl_id=$ownEmpId and tt.sett_id=ts.sett_id order by trav_start_date desc";
                        viewTravelPlanPagination();
                        ?>
                    </div>
                </td>                                
            </tr>                    
        </table>
        <?php
    }
}
?>