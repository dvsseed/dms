<?php
/*
根據文檔，DateDiff(interval,date1,date2)函數的定義為“返回兩個日期之間的時間間隔”。 
Intervals參數的用法與DateAdd函數中的相同。出於避免過於復雜的考慮，
我們決定忽略Vbscript中DateDiff函數中其它復雜的參數，
即其兩個可選的參數變量[firstdayofweek[, firstweekofyear]]
（它們用於決定星期中第一天是星期天還是星期一和一年中第一周的常數。
而且我們只允許intervals有以下五個值：
"w"(周)、
"d"（天）、
"h"（小時）、
"n"（分鐘） 和
"s"（秒）。 

Let's see what we can come up with: 下面的代碼是我們所需要的： 
*/
if( !function_exists( "DateDiff" ) )
{

Function DateDiff ($interval, $date1,$date2) 
{ 
// 得到兩日期之間間隔的秒數

// $DT = split(' ',$date1);
// $iYMD = split('-',$DT[0]);
// $iHMS = split(':',$DT[1]);
$DT = explode(' ',$date1);
$iYMD = explode('-',$DT[0]);
$iHMS = explode(':',$DT[1]);

$date1 = mktime( $iHMS[0] , $iHMS[1] , $iHMS[2] , $iYMD[1] , $iYMD[2], $iYMD[0] ); 

// $DT = split(' ',$date2);
// $iYMD = split('-',$DT[0]);
// $iHMS = split(':',$DT[1]);
$DT = explode(' ',$date2);
$iYMD = explode('-',$DT[0]);
$iHMS = explode(':',$DT[1]);

$date2 = mktime( $iHMS[0] , $iHMS[1] , $iHMS[2] , $iYMD[1] , $iYMD[2], $iYMD[0] );

$timedifference = $date2 - $date1; 
//echo $timedifference;
switch ($interval) 
{ 
  case "w": $retval = bcdiv($timedifference ,604800); break; 
  case "d": $retval = bcdiv( $timedifference,86400); break; 
  case "h": $retval = bcdiv ($timedifference,3600); break; 
  case "n": $retval = bcdiv( $timedifference,60); break; 
  case "s": $retval = $timedifference; break; 
} 
return $retval;
} 
}

if( !function_exists( "bcdiv" ) )
{
    function bcdiv( $first, $second, $scale = 0 )
    {
        $res = $first / $second;
        return round( $res, $scale );
    }
} 


?>
