<?php

//$date = "23-07-2014 12:30 AM";
//$timestamp = strtotime($date);
//echo date("d-m-y h:i A", $timestamp);

// This file needs to be included where ever the AM/PM HH:MM combobox is required
$start_time = "";
$end_time = "";
for ($i = 0, $h = 12, $m = 0, $am = "AM", $c = 1; $i < 48; $i++, $c++) {
    if ($i > 23) {
        $am = "PM";
    }
    // format the hour
    if ($h < 10) {
        $hh = "0$h";
    } else {
        $hh = $h;
    }

    // form the minute
    if ($m < 10) {
        $mm = "0$m";
    } else {
        $mm = $m;
    }
    $display_time = "$am $hh:$mm";
    $time_value = "$hh:$mm $am";
    $start_time .= "<option value='$time_value'>$display_time</option>";
    // display the last value of the combobox
//    if($i == 48){
        $end_time .= "<option value='$time_value'>$display_time</option>";
//    }else{
//        $end_time .= "<option value='$time_value' selected>$display_time</option>";
//    }
    if ($h >= 12 && $m == 30) {
        $h = 0;
    }if ($c == 2) {
        $h++;
        $c = 0;
    }
    if ($m == 30) {
        $m = 0;
    } else {
        $m = 30;
    }
}

