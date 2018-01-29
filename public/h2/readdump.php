<?php

function do_read() {
	// 中英對照
	$COMPARE = array (
		"Alcohol" => "酒類",
		"Bicycle" => "腳踏車",
		"Bread" => "麵包",
		"Dairy" => "乳製品",
		"Dizzy" => "頭暈",
		"Dessert" => "甜點",
		"Egg" => "蛋",
		"Fish" => "魚",
		"Fruits" => "水果",
		"Full" => "很飽",
		"Golf" => "高爾夫",
		"Grains" => "穀類",
		"Hungry" => "很餓",
		"Hyper" => "心情很好",
		"Hypo" => "心情不好",
		"Jogging" => "慢跑",
		"Juice" => "果汁",
		"Meat" => "肉類",
		"Nausea" => "噁心",
		"Other" => "其它",
		"Oil" => "油脂",
		"Other Proteins" => "其他蛋白質",
		"Other Grains" => "其他穀類",
		"Pasta" => "麵食",
		"Rice" => "米飯",
		"Seafood" => "海鮮",
		"Sick" => "生病",
		"Soft Drink" => "飲料",
		"Sports" => "球類",
		"Stressed" => "壓力太大",
		"Swimming" => "游泳",
		"Sweating" => "冒汗",
		"Shaking" => "發抖",
		"Strength Training" => "健身",
		"Thirsty" => "口渴",
		"Tired" => "很累",
		"Vegetables" => "蔬菜",
		"Walk" => "走路",
		"Walking" => "散步",
		"Coffee / Tea" => "咖啡 / 茶",
		"Starchy Veggies" => "根莖類",
		"Stretching" => "伸展操",
		"Yoga" => "瑜伽",
		"Dance (Slow)" => "跳舞（慢）",
		"Ping Pong" => "乒乓球",
		"Baseball/Softball" => "棒壘球",
		"Martial Arts" => "武術",
		"Badminton" => "羽毛球",
		"Dance (Fast)" => "跳舞 (快）",
		"Basketball" => "籃球",
		"Soccer/Football" => "足球",
		"Tennis" => "網球",
		"Aerobics" => "有氧舞蹈",
		"Biking (Fast)" => "腳踏車 (快)",
		"Biking (Slow)" => "腳踏車 (慢)"
	);

	$TYPE_SPORT = array (
		"",
		"Sports",
		"Biking (Slow)",
		"Golf",
		"Jogging",
		"Swimming", // 5
		"Walking",
		"Strength Training",
		"Other",
		"Stretching",
		"Yoga",
		"Dance (Slow)",
		"Biking (Fast)",
		"Ping Pong",
		"Baseball/Softball",
		"Martial Arts",
		"Badminton",
		"Dance (Fast)",
		"Basketball",
		"Soccer/Football",
		"Tennis",
		"Aerobics"
	);

	$TYPE_FOOD = array (
		"",   // 0
		"Dairy",
		"Bread",
		"Rice",
		"Pasta",
		"Other Grains", // 5
		"Meat",
		"Fish",
		"Seafood",
		"Egg",
		"Vegetables", // 10
		"Fruits",
		"Dessert",
		"Juice",
		"Alcohol",
		"Soft Drink", // 15
		"Oil",
		"Other Proteins",
		"Starchy Veggies",
		"Coffee / Tea"
	);

	//include("../../readdb.php");
	global $dbh;
  
	$file = file_get_contents('dump_diaries.json', true);
  
	// convert to stdclass object
	$cart = json_decode( $file );
	// echo $cart->message . "<br />\n";
	//echo $cart->data[0]->email . "<br />\n";
	//echo $cart->data[0]->count . "<br />\n";
  
	echo 'count: $cart->data:' . count($cart->data);

	$mailcount = count($cart->data);

	include("setpara.php");
	include_once("DateAdd.inc");
	include("DateDiff.inc");
	// print_r($BGtime);

	// write mydatetime to MDataID
	$mydatetime = date("YmdHis", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));
	$nowdatetime = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));
	// echo count($BGtime)."\n";
	$temp_MYDATE_ADD = "";
	$x = 0;
	$diff = 0;
	$aa = 0;  

	for($x = 0; $x < $mailcount; $x++) {
		$email = $cart->data[$x]->email;
		$PP_PatID = $cart->data[$x]->identify;
		echo "get from PID \n";
  
		if($PP_PatID == "") {
			$PP_PatID = get_pid_from_email($email);
			echo "by Email \n";
		}
  
		echo "PID: " . $PP_PatID , "\n";
  
		if ($PP_PatID != "") {
			$founded = 0; //記錄寫入筆數
  
			$i = $cart->data[$x]->count; // 讀入 count 筆資料
  
			for($j = 0; $j < $i; $j++) {
				if(false) {
					print_r( $cart->data[$x]->diaries[$j]->oral_medicines ) . "\n";
					print_r( $cart->data[$x]->diaries[$j]->attachments ) . "\n";
					echo $cart->data[$x]->diaries[$j]->sport_duration . "\n";
					echo $cart->data[$x]->diaries[$j]->detail . "\n";
					print_r( $cart->data[$x]->diaries[$j]->fellings ) . "\n";
					print_r( $cart->data[$x]->diaries[$j]->food ) . "\n";
					echo $cart->data[$x]->diaries[$j]->recorded_at . "\n";
					echo $cart->data[$x]->diaries[$j]->unit . "\n";
					echo $cart->data[$x]->diaries[$j]->tzoffset . "\n";
					echo $cart->data[$x]->diaries[$j]->period . "\n";
					echo $cart->data[$x]->diaries[$j]->carbs . "\n";
					echo $cart->data[$x]->diaries[$j]->glucose_value . "\n";
					echo $cart->data[$x]->diaries[$j]->created_at . "\n";
					print_r( $cart->data[$x]->diaries[$j]->insulins ) . "\n";
					print_r( $cart->data[$x]->diaries[$j]->sports ) . "\n";
					echo $cart->data[$x]->diaries[$j]->state . "\n";
					echo $cart->data[$x]->diaries[$j]->meal_type . "\n";
				}

				$CARBS = $cart->data[$x]->diaries[$j]->carbs;
				$DETAIL = $cart->data[$x]->diaries[$j]->detail;
				$oral =$cart->data[$x]->diaries[$j]->oral_medicines;
				$sport_duration = $cart->data[$x]->diaries[$j]->sport_duration;
				// 2015/12/09 add by eric For new function diary_id 
				$diary_id = "-1";
				if(isset($cart->data[$x]->diaries[$j]->diary_id)) {
					$diary_id = $cart->data[$x]->diaries[$j]->diary_id;
				}

				echo "foods->count: " . count($cart->data[$x]->diaries[$j]->foods) . "\n";
				echo "sports->count: " . count($cart->data[$x]->diaries[$j]->sports) . "\n";

				$fd = 0;
				$TYPE_DEC = ""; // 存放 AutoNote 註記

				foreach ($cart->data[$x]->diaries[$j]->foods as $key => $value) {
					$foods[$fd] = $key;
					$foods_num[$fd] = $value;
					if($fd) {
						$TYPE_DEC .= ",\n" . $COMPARE[$TYPE_FOOD[$key]] . ":" . $value . "份";
					} else {
						$TYPE_DEC .= $COMPARE[$TYPE_FOOD[$key]] . ":" . $value . "份";
					}
					//          $INSERT_NOTE[$fd]  = "insert into dm1.NTResult (NT_Slot,NT_Type,NT_Value1,NT_Value2,NT_Value3,NT_Value4,NT_AddNewDate,MR_AutoID) values (";
					//          $INSERT_NOTE[$fd] .= "1,1,";
					$fd++;
				}

				print_r($foods);
				print_r($foods_num);

				$fd = 0;
				foreach ($cart->data[$x]->diaries[$j]->sports as $key => $value) {
					$sports[$fd] = $key;
					$sports_num[$fd] = $value;
					if ($TYPE_DEC) {
						$TYPE_DEC .= ",\n" . $COMPARE[$TYPE_SPORT[$key]] . ":" . $value . "分鐘";
					} else {
						$TYPE_DEC .= $COMPARE[$TYPE_SPORT[$key]] . ":" . $value . "分鐘";
					}
					$fd++;
				}
				print_r($sports);
				print_r($sports_num);
				echo "AutoNote = " . $TYPE_DEC . "\n";
				echo "diary_id = " . $diary_id . "\n";

				$DATETIME = explode('T',$cart->data[$x]->diaries[$j]->recorded_at);  
				$GV = $cart->data[$x]->diaries[$j]->glucose_value;
				$medication = $cart->data[$x]->diaries[$j]->oral_medicines;

				$MYDATE = $DATETIME[0];
				$MYDATE1= $DATETIME[1];
				$MYTIME = explode('+',$MYDATE1);

				$DATETIME_ADD = explode('T',$cart->data[$x]->diaries[$j]->created_at);
				$MYDATE_ADD = $DATETIME_ADD[0];
				$MYDATE1= $DATETIME_ADD[1];
				$MYTIME_ADD = explode('+',$MYDATE1);
				echo "MYTIME_ADD=>";
				print_r($MYTIME_ADD); 

				$MYDATETIME = "";

				if($MYTIME_ADD[1] == "0000") {
					$date = new DateTime($MYDATE . " " . $MYTIME[0]);
					$date->add(new DateInterval('PT8H'));
					echo "GET TIME:" . $date->format('Y-m-d H:i:s') . "\n";
					$MYTIME[0] = $date->format('H:i:s');
					$MYDATETIME = $date->format('Y-m-d H:i:s');
				} else {
					$MYDATETIME = $MYDATE . " " . $MYTIME[0];
				}
      
				echo $MYTIME[0] . "\n";
  
				//  2015/04/20 add by eric for meal_type and state
				$STATE    = $cart->data[$x]->diaries[$j]->state;
				$MEALTYPE = $cart->data[$x]->diaries[$j]->meal_type;

				$SLOT = check_meal( $STATE , $MEALTYPE );
				if($SLOT == 0) {
					$SLOT = check_range($BGtime, substr($MYTIME[0], 0, 5));
				}
				//  2015/04/20 end

				echo "SLOT:". $SLOT. "\n";

				$activity_detail = "";
				$detail = "";
				$detail_note = "";

				if(isset($cart->data[$x]->diaries[$j]->activity_detail)) {
					// 若有 活動 資料再往下走
					$activity_detail = iconv("UTF-8", "big5", $cart->data[$x]->diaries[$j]->activity_detail);
					$detail = iconv("UTF-8","big5",$cart->data[$x]->diaries[$j]->detail);
					if($activity_detail && $detail) {
						$detail_note = $activity_detail . "\n" . $detail ;
					} else if ($activity_detail) {
						$detail_note = $activity_detail;
					} else if ($detail) {
						$detail_note = $detail;
						//if($detail_note) $$detail_note = iconv("UTF-8","big5",$detail_note);
					}
					$sql = "select count(*) as CNT from dm1.MResult where MR_GroupID = 'C0001' 
							and MR_PatientID = '". $PP_PatID ."'
							and MR_Time = '". $MYDATETIME ."'
							and MR_Slot = '". $SLOT ."'
							and MR_Value1 = '". $GV ."'
							and ModifyID = 'H2' ";
					if($diary_id > 0) {
					  $sql = "select count(*) as CNT from dm1.MResult where MR_GroupID = 'C0001'
							  and MR_PatientID = '" . $PP_PatID . "'
							  and ModifyID = 'H2'
							  and DIARY_ID = '" . $diary_id . "' ";
					}
  					//echo "count sql:".$sql;
					$IT_DATETIME = $MYDATE_ADD . " " . $MYTIME_ADD[0];
  
					if($temp_MYDATE_ADD == "" || $IT_DATETIME > $temp_MYDATE_ADD) {
						$temp_MYDATE_ADD = $IT_DATETIME;
					}
  
					$rs = mysql_query($sql, $dbh);
					$row = mysql_fetch_row($rs);
  
					// 2015/06/02 加入檢查90天內的資料才存檔
					echo "nowdatetime:" . $nowdatetime . "\n";
					echo "MYDATETIME:" . $MYDATETIME . "\n";
					$diff = DateDiff("d", $MYDATETIME, $nowdatetime);
					echo "diff_day:" . $diff . "\n";
					if ($row[0] == 0 && $diff < 90) {
						$founded++;
						if($diary_id == -1) {

							$sql = "INSERT INTO dm1.MResult(
								MR_GroupID
								,MR_PatientID
								,MR_Date
								,MR_Time
								,MR_Slot
								,MR_Type -- 1
								,MR_Value1
								,MR_AddNewDate
								,MR_Note
								,MR_AutoNote
								,MDataID
								,ModifyID
								) VALUES (
								'C0001'  -- MR_GroupID - IN varchar(5)
								,'". $PP_PatID ."'  -- MR_PatientID - IN varchar(20)
								,'". $MYDATETIME ."'  -- MR_Date - IN datetime
								,'". $MYDATETIME ."'  -- MR_Time - IN datetime
								,'". $SLOT ."'   -- MR_Slot - IN int(11)
								,1   -- MR_Type - IN int(11)
								, ". $GV ."   -- MR_Value1 - IN float
								,'". $MYDATE_ADD ." ". $MYTIME_ADD[0] ."'  -- MR_AddNewDate - IN datetime
								-- ,'H2_". (($medication)? "有用藥":"無用藥") ."'  -- MR_Note - IN varchar(512)
								,'".$detail_note."'
								,'".$TYPE_DEC."'
								,'".$mydatetime."'  -- MDataID - IN char(15)
								,'H2'  -- ModifyID - IN varchar(20)
								) ON DUPLICATE KEY UPDATE MR_AddNewDate = now() ";
						} else if($diary_id) {
							$H2 = "H2";

							if ($GV == -1) {
								// 手動 , 空值
								$GV = NULL;
								$H2 = "H2M";
							}

							$sql = "INSERT INTO dm1.MResult(
								MR_GroupID
								,MR_PatientID
								,MR_Date
								,MR_Time
								,MR_Slot
								,MR_Type -- 1
								,MR_Value1
								,MR_AddNewDate
								,MR_Note
								,MR_AutoNote
								,MDataID
								,ModifyID
								,DIARY_ID
								) VALUES (
								'C0001'  -- MR_GroupID - IN varchar(5)
								,'". $PP_PatID ."'  -- MR_PatientID - IN varchar(20)
								,'". $MYDATETIME ."'  -- MR_Date - IN datetime
								,'". $MYDATETIME ."'  -- MR_Time - IN datetime
								,'". $SLOT ."'   -- MR_Slot - IN int(11)
								,1   -- MR_Type - IN int(11)
								, ". $GV ."   -- MR_Value1 - IN float
								,'". $MYDATE_ADD ." ". $MYTIME_ADD[0] ."'  -- MR_AddNewDate - IN datetime
								-- ,'H2_". (($medication)? "有用藥":"無用藥") ."'  -- MR_Note - IN varchar(512)
								,'".$detail_note."'
								,'".$TYPE_DEC."'
								,'".$mydatetime."'  -- MDataID - IN char(15)
								,'H2'  -- ModifyID - IN varchar(20)
								,'".$diary_id."'
								) ON DUPLICATE KEY UPDATE MR_AddNewDate = now() ";
						} else {
							if($diary_id && $TYPE_DEC) {
								$sql = "update MResult
									set MR_AutoNote = '".$TYPE_DEC."'
									where MR_PatientID = '". $PP_PatID ."'
									and DIARY_ID = '".$diary_id."'";

								insert_data($sql); //  寫入資料 MResult
							}

							if ($GV == -2) {
								// delete
								$sql = "update MResult 
									set MR_Value3 = MR_Value1, MR_Value1 = null
									where MR_PatientID = '" . $PP_PatID . "'
									and DIARY_ID = '" . $diary_id . "' ";
							}
						}  
        
						insert_data($sql); //  寫入資料 MResult

						if(isset($cart->data[$x]->diaries[$j]->activity_detail)) {
							// 若有 活動 資料再往下走
							$sql = "select MR_AutoID from MResult where MR_PatientID = '" . $PP_PatID . "' and MR_Time = '" . $MYDATETIME . "' and MR_Slot = '" . $SLOT . "' and MDataID = '" . $mydatetime . "' ";
							echo "sql: " . $sql . "\n";
							$rs = mysql_query($sql, $dbh);
							while($rw = mysql_fetch_row($rs)) {
								$activity_detail = $cart->data[$x]->diaries[$j]->activity_detail;  // 飲食, 運動及心情 欄位: Foods(飲食) / Sports(運動) / Feelings(心情)
								#$attachments = $cart->data[$x]->diaries[$j]->attachments;      // 照片
								$detail = $cart->data[$x]->diaries[$j]->detail;           // 使用者 NOTE
								$medication = $cart->data[$x]->diaries[$j]->medication;
								$countatt = count_objects($cart->data[$x]->diaries[$j]->attachments);
								$notes = "";
								echo "countatt:" . $countatt . "\n";

								if($countatt) {
									echo "count(att.url1):".($cart->data[$x]->diaries[$j]->attachments[0]->url)."\n";
									for($a = 0; $a < $countatt; $a++ ) {
										$url = $cart->data[$x]->diaries[$j]->attachments[$a]->url;
										$id  = $cart->data[$x]->diaries[$j]->attachments[$a]->id;
										$filename = $cart->data[$x]->diaries[$j]->attachments[$a]->filename;
										$nsql = "insert into MR_ATTACHMENTS ( MA_PIC_URL, MA_ID, MA_FILENAME, MA_MR_AutoID ) values ('$url', '$id', '$filename', '$rw[0]')";
										echo "nsql: " . $nsql . "\n";
										mysql_query($nsql, $dbh);
										file_put_contents("photo/" . $filename, fopen($url, 'r'));
									}
								} // end of if($countatt)
							} // end of while($rw=mysql_fetch_row($rs))
						} // end of 若有 活動 資料再往下走
					} else if($diary_id && $TYPE_DEC) {
						$sql = "update MResult
							set MR_AutoNote = '" . $TYPE_DEC. "'
							where MR_PatientID = '" . $PP_PatID . "'
							and DIARY_ID = '" . $diary_id . "'";
						insert_data($sql); //  寫入資料 MResult
					}
				}
			} // End of for loop
   
			if ($founded > 0 && $diff < 90) {
				$SQL = "insert into MResult_TOP_PID ( MR_PatientID, MR_DateTime )";
				$SQL .= " values (";
				$SQL .= "  '". $PP_PatID ."'";
				$SQL .= " ,'". $temp_MYDATE_ADD ."'";
				$SQL .= " )";
				insert_data($SQL);
			}
		} else {
			// End of if($PP_PatID!="")
			echo "email: $email has not PID! \n";
		}
	} // end of for ( $x=0

	echo "current: ". $cart->pages->current. "\n";
	echo "hasNext: ". $cart->pages->hasNext. "\n";
	echo "total: ". $cart->pages->total. "\n";

	// 判斷是否有下一頁 2015/07/30
	$current = $cart->pages->current ;
	$hasNext = $cart->pages->hasNext ;
	$total   = $cart->pages->total ;

	if($x == 0) {
		echo "There is No Data!!" . "\n";
	} else {
		echo "All Data already insert to database!!" . "\n";
	}

	return $hasNext . "," . $current . "," . $total;
} // end of function do_read()


function COMPARE_DEC( $kd ) { 
	global $COMPARE;

	$result = $kd;
	/*
	while (list($key, $value) = each($COMPARE)) { 
	echo "$key => $value \r\n"; 
	if($kd == $key )
	$result = $value;

	} 
	*/
	/*
	foreach($COMPARE as $key => $value ){
	if($kd == $key )
	$result = $value;
	}
	*/

	for($i = 0; $i < count($COMPARE); $i++) {
		if(strcmp($COMPARE[$i]->name, $kd) == 0){
			$result = $COMPARE[$kd]->value;
			return $result;
		}
	}

	return $result;
}

function check_meal( $state, $mealtype ) { // 檢查早、午、晚餐前後,睡前,晨起
  $result = 0;
  if( $mealtype == "breakfast" ) {

    if( $state == "before_meal" ){
      $result = 2;
    }else if ( $state == "after_meal" ){
      $result = 3;
    }

  }else if ( $mealtype == "lunch" ){

    if( $state == "before_meal" ){
      $result = 4;
    }else if ( $state == "after_meal" ){
      $result = 5;
    }

  }else if ( $mealtype == "dinner" ){

    if( $state == "before_meal" ){
      $result = 6;
    }else if ( $state == "after_meal" ){
      $result = 7;
    }

  }else { // 其他非用餐時間
    if ( $state == "wakeup" ){
      $result = 1;
    }else if ( $state == "bedtime" ){
      $result = 8;
    }else if ( $state == "midnight" ){
      $result = 9;
    }
  }
  return $result;
}

function insert_data($sql) {
	global $dbh;
	echo $sql . "\n";
	mysql_query($sql, $dbh);

	if(mysql_error())
		echo "error:" . mysql_error() . "\n";
}

function count_objects($value) {
    if (is_scalar($value) || is_null($value)) {
        $count = 0;
	} else if (is_array($value)) {
        $count = 0;
        foreach ($value as $val) {
            $count += count_objects($val);
        }
	} else if (is_object($value)) {
        $count = 1;
        $reflection_object = new \ReflectionObject($value);
        foreach ($reflection_object->getProperties() as $property) {
            $count += count_objects($property->getValue($value));
		}
	}

    return $count;
}

function check_range($BGtime, $MYTIME) {
	$slot = 8; // bed_time
	for($i = 0; $i < count($BGtime); $i++) {
		if( $MYTIME>$BGtime[$i][0] && $MYTIME<$BGtime[$i][1] ) {
			$slot = $i;
		}
	}
	return $slot;
}

function get_pid_from_email($email) {
	global $dbh;

	$sql = "select PP_PatientID from PatientProfile1 where PP_Email = '" . $email . "'";
	echo $sql . "\n";
	$rs = mysql_query($sql,$dbh);
	$pid = "";

	if(mysql_error())
		echo "error:" . mysql_error();

	if($row = mysql_fetch_row($rs)) {
		$pid = $row[0];
		mysql_free_result($rs);
	}

	return $pid;
}

?>
