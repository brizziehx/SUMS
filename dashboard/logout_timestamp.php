<?php 
    // LOGOUT TIMESTAMP 

    $datetime2 = explode(' ', $row['logouttime']);

    $date2 = $datetime2[0];
    $time2 = $datetime2[1];

    $dateRow2 = explode('-', $date2);
    $year2 = $dateRow2[0];
    $month2 = $dateRow2[1];
    $day2 = $dateRow2[2];

    $timeRow2 = explode(':', $time2);
    $hours2 = $timeRow2[0];
    $minutes2 = $timeRow2[1];
    $seconds2 = $timeRow2[2];

    $Logout_timestamp = mktime($hours2,$minutes2,$seconds2,$month2,$day2,$year2);
