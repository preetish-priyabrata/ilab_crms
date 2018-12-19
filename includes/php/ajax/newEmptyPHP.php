<?php

// get time interval using intval function
$seconds = intval($totalTime->format('%s'));
$total_minutes = intval($totalTime->format('%i'));
$total_hours = intval($totalTime->format('%H'));
$total_days = intval($totalTime->format('%d'));
$total_months = intval($totalTime->format('%M'));
$total_years = intval($totalTime->format('%Y'));

$total_interval = "";
if ($total_years < 1) {
    if ($total_months < 1) {
        if ($total_days < 1) {
            if ($total_hours < 1) {
                if ($total_minutes == 1) {
                    $total_interval = "$total_minutes minute";
                } else {
                    $total_interval = "$total_minutes minutes";
                }
            } else {
                if ($total_hours == 1) {
                    $total_interval = "$total_hours hour $total_minutes minutes";
                } else {
                    $total_interval = "$total_hours hours $total_minutes minutes";
                }
            }
        } else {
            if ($total_days == 1) {
                $total_interval = "$total_days day $total_hours hours $total_minutes minutes";
            } else {
                $total_interval = "$total_days days $total_hours hours $total_minutes minutes";
            }
        }
    } else {
        if ($total_months == 1) {
            $total_interval = "$total_months month $total_days days $total_hours hours $total_minutes minutes";
        } else {
            $total_interval = "$total_months months $total_days days $total_hours hours $total_minutes minutes";
        }
    }
} else {
    if ($total_years == 1) {
        $total_interval = "$total_years year $total_months month $total_days days $total_hours hours $total_minutes minutes";
    } else {
        $total_interval = "$total_years years $total_months month $total_days days $total_hours hours $total_minutes minutes";
    }
}
