<?php
$access_token ='MrdklU/eR63kV2KIHYpwm4sdoTCya/RcjIy0Vp9cnPn+DIa2+u1/7q6Okvor8OBr+iI/800fPgrZwV3+fLF5rb9ZsVxbiqGs7ZteOneh7wNlLjTwd9uFMC/RGEfMmVGsRLUH28bRA1xfSGUySSbBMgdB04t89/1O/w1cDnyilFU=';
// define('TOKEN', '你的Channel Access Token');

// 接收LINE Server送過來的訊息
$json_string = file_get_contents('php://input');
$file = fopen("Line_log.txt", "a+");
fwrite($file, $json_string . "\n");

// 將JSON進行解碼動作
$json_obj = json_decode($json_string);

// 處理JSON內容
$event = $json_obj->{"events"}[0];
$type  = $event->{"message"}->{"type"};
$message = $event->{"message"};
$reply_token = $event->{"replyToken"};

$post_data = [
  "replyToken" => $reply_token,
  "messages" => [
    [
      "type" => "text",
      "text" => $message->{"text"}
    ]
  ]
];
fwrite($file, json_encode($post_data) . "\n");

// 訊息傳送到LINE上
$ch = curl_init("https://api.line.me/v2/bot/message/reply");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $access_token
    //'Authorization: Bearer '. TOKEN
));

$result = curl_exec($ch);
fwrite($file, $result . "\n");
fclose($file);
curl_close($ch);

?>
