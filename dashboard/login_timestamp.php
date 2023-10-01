<?php
// LOGIN TIMESTAMP

$datetime = explode(' ', $row['logintime']);

$date = $datetime[0];
$time = $datetime[1];

$dateRow = explode('-', $date);
$year = $dateRow[0];
$month = $dateRow[1];
$day = $dateRow[2];

$timeRow = explode(':', $time);
$hours = $timeRow[0];
$minutes = $timeRow[1];
$seconds = $timeRow[2];

$Login_timestamp = mktime($hours,$minutes,$seconds,$month,$day,$year);

?>