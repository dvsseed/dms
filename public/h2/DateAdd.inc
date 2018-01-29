<?php
/*
Interval（時間間隔字符串表達式）可以是以下任意值: 
yyyy year年 
q Quarter季度 
m Month月 
y Day of year一年的數 
d Day天 
w Weekday一周的天數 
ww Week of year周 
h Hour小時 
n Minute分 
s Second秒 
w、y和d的作用是完全一樣的，即在目前的日期上加一天，q加3個月，ww加7天。 
*/
function DateAdd ($interval, $number, $date) { 
$date_time_array = getdate($date); 
$hours = $date_time_array["hours"]; 
$minutes = $date_time_array["minutes"]; 
$seconds = $date_time_array["seconds"]; 
$month = $date_time_array["mon"]; 
$day = $date_time_array["mday"]; 
$year = $date_time_array["year"]; 
switch ($interval) { 
case "yyyy": $year +=$number; break; 
case "yyy": $year +=($number-1911); break;
case "q": $month +=($number*3); break; 
case "m": $month +=$number; break; 
case "y": 
case "d": 
case "w": $day+=$number; break; 
case "ww": $day+=($number*7); break; 
case "h": $hours+=$number; break; 
case "n": $minutes+=$number; break; 
case "s": $seconds+=$number; break; 
} 
$timestamp = mktime($hours ,$minutes, $seconds,$month ,$day, $year); 
return $timestamp;} 
?>

