<?php
global $dbh;
global $PP_PatID;
global $debug;

$rsFK = mysql_query("select * from FoodKind where FD_ENABLED = 1 order by FD_ID",$dbh);

if (mysql_error()) echo ':Error<BR>'.mysql_error();

//$NType1=array("份","麵食","米飯","蔬菜","蛋類","奶類","水果","飲料");
$NType1[0]="份";
$i = 1;
while($rw=mysql_fetch_row($rsFK))
{
  $NType1[$i]=$rw[1];
  $i++;
}
mysql_free_result($rsFK);

$NType2=array("小時","輕度運動","中度運動","重度運動");
$NValue2=array(0,0.5,1,1.5,2,2.5,3,3.5,4,4.5);

$NType3=array("無","有");

$NType4=array("單位","速效","短效","中效","長效","混合型70/30","混合型75/25","幫浦","口服藥");

$NTitle=array("","飲食","運動","低血糖","胰島素");

if ($PP_PatID == '' && isset($_SESSION["pid"]))
  $PP_PatID == $_SESSION["pid"] ;

$strBSR = "select * from BSRange where PatID = '".$PP_PatID."';";
if($debug) echo "<p>$strBSR</p>";
$rsBSR = mysql_query($strBSR,$dbh);
if (mysql_error()) echo $strBSR.':Error<BR>'.mysql_error();
$row = mysql_fetch_row($rsBSR);
if (mysql_error()) echo $strBSR.':Error Code:37<BR>'.mysql_error();

if ( isset( $row[2] ) && $row[2] )
{
  $BGrange=array(
   array(999,999)
  ,array($row[2],$row[3])
  ,array($row[4],$row[5])
  ,array($row[6],$row[7])
  ,array($row[8],$row[9])
  ,array($row[10],$row[11])
  ,array($row[12],$row[13])
  ,array($row[14],$row[15])
  ,array($row[16],$row[17])
  ,array($row[18],$row[19])
  );

  $BGtime=array(
  array("06:01","07:00")
  ,array($row[20],$row[21])
  ,array($row[22],$row[23])
  ,array($row[24],$row[25])
  ,array($row[26],$row[27])
  ,array($row[28],$row[29])
  ,array($row[30],$row[31])
  ,array($row[32],$row[33])
  ,array($row[34],$row[35])
  ,array($row[36],$row[37])
  );
}
else
{
  $BGrange=array(
   array(90,130)
  ,array(90,130)
  ,array(90,130)
  ,array(100,190)
  ,array(90,130)
  ,array(100,190)
  ,array(90,130)
  ,array(100,190)
  ,array(100,150)
  ,array(100,150)
  );

/*
 '晨起'.'(時間:'.$BGtime[1][0].'~'.$BGtime[1][1].', 目標:'.$BGrange[1][0].'~'.$BGrange[1][1].')' 
,'早餐前'.'(時間:'.$BGtime[2][0].'~'.$BGtime[2][1].', 目標:'.$BGrange[2][0].'~'.$BGrange[2][1].')' 
,'早餐後'.'(時間:'.$BGtime[3][0].'~'.$BGtime[3][1].', 目標:'.$BGrange[3][0].'~'.$BGrange[3][1].')' 
,'中餐前'.'(時間:'.$BGtime[4][0].'~'.$BGtime[4][1].', 目標:'.$BGrange[4][0].'~'.$BGrange[4][1].')' 
,'中餐後'.'(時間:'.$BGtime[5][0].'~'.$BGtime[5][1].', 目標:'.$BGrange[5][0].'~'.$BGrange[5][1].')' 
,'晚餐前'.'(時間:'.$BGtime[6][0].'~'.$BGtime[6][1].', 目標:'.$BGrange[6][0].'~'.$BGrange[6][1].')' 
,'晚餐後'.'(時間:'.$BGtime[7][0].'~'.$BGtime[7][1].', 目標:'.$BGrange[7][0].'~'.$BGrange[7][1].')' 
,'睡前'.'(時間:'.$BGtime[8][0].'~'.$BGtime[8][1].', 目標:'.$BGrange[8][0].'~'.$BGrange[8][1].')' 
,'凌晨'.'(時間:'.$BGtime[9][0].'~'.$BGtime[9][1].', 目標:'.$BGrange[9][0].'~'.$BGrange[9][1].')' 

*/


  $BGtime=array(
  array("00:00","07:00")
  ,array("06:01","07:00")
  ,array("07:01","09:00")
  ,array("09:01","11:00")
  ,array("11:01","13:00")
  ,array("13:01","17:00")
  ,array("17:01","19:00")
  ,array("19:01","20:30")
  ,array("20:31","24:00")
  ,array("00:01","06:00")
  );
}
mysql_free_result($rsBSR);
$BPRange=array(
array("90","120")
,array("90","120")
,array("90","120")
,array("90","120")
,array("90","120")
,array("90","120")
);

$BPdia=array(
array("50","80")
,array("50","80")
,array("50","80")
,array("50","80")
,array("50","80")
,array("50","80")
);

$BPtime=array(
array("06:01","08:00")
,array("06:01","08:00")
,array("08:01","11:00")
,array("11:01","14:00")
,array("14:01","18:00")
,array("18:01","06:00")
);

$NTTypeValue = array(
array("","","","","")
,array("","","","","")
,array("","","","","")
,array("","","","","")
,array("","","","","")
,array("","","","","")
,array("","","","","")
,array("","","","","")
,array("","","","","")
,array("","","","","")
,array("","","","","")
,array("","","","","")
,array("","","","","")
,array("","","","","")
,array("","","","","")
,array("","","","","")
,array("","","","","")
,array("","","","","")
,array("","","","","")
,array("","","","","")
,array("","","","","")
// "NT_Slot","NT_Value1","NT_Value2","NT_Value3","NT_AutoID"
);

$NTType = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
$NTTypeSlot = array(
array("","","","","","","","","","","","")
,array("","","","","","","","","","","","")
,array("","","","","","","","","","","","")
,array("","","","","","","","","","","","")
,array("","","","","","","","","","","","")
,array("","","","","","","","","","","","")
,array("","","","","","","","","","","","")
,array("","","","","","","","","","","","")
,array("","","","","","","","","","","","")
,array("","","","","","","","","","","","")
,array("","","","","","","","","","","","")
,array("","","","","","","","","","","","")
,array("","","","","","","","","","","","")
,array("","","","","","","","","","","","")
,array("","","","","","","","","","","","")
,array("","","","","","","","","","","","")
,array("","","","","","","","","","","","")
,array("","","","","","","","","","","","")
,array("","","","","","","","","","","","")
,array("","","","","","","","","","","","")
);

// ********** 2015/07/30 ********** 加註運動,食物,感覺 start

$SPORTS = array(
array( "Sports", "運動"            ),
array( "Bicycle", "腳踏車"         ),
array( "Golf", "高爾夫"            ),
array( "Jog", "慢跑"               ),
array( "Swim", "游泳"              ),
array( "Walk", "走路"              ),
array( "Strength Training", "健身" ),
array( "Other", "其它運動"         ) 
);


$FOODS = array(
array(  "Dairy","乳製品"               ),
array(  "Bread","麵包"                 ),
array(  "Rice", "米飯"                 ),
array(  "Pasta", "麵食"                ),
array(  "Other Grains", "其他穀類"     ),
array(  "Meat", "肉類"                 ),
array(  "Fish", "魚"                   ),
array(  "Seafood", "海鮮"              ),
array(  "Egg", "蛋"                    ),
array(   "Vegetables", "蔬菜"          ),
array(   "Fruits", "水果"              ),
array(   "Dessert", "甜點"             ),
array(   "Juice", "果汁"               ),
array(   "Alcohol", "酒類"             ),
array(   "Soft Drink", "飲料"          ),
array(   "Oil", "油脂"                 ),
array(   "Other Proteins", "其他蛋白質") 
);


$FEELING = array(
array("Full", "很飽"         ),
array("Hungry", "很餓"       ),
array("Hyper", "心情很好"    ),
array("Hypo", "心情不好"     ),
array("Sick", "生病"         ),
array("Stressed", "壓力太大" ),
array("Tired", "很累"        ),
array("Dizzy", "頭暈"        ),
array("Thirsty", "口渴"      ),
array( "Sweating", "冒汗"    ),
array( "Shaking", "發抖"     ),
array( "Nausea", "噁心"      )
);


// ********** 2015/07/30 ********** 加註運動,食物,感覺 end
?>
