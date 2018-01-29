<?php

require '../vendor/autoload.php';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient('+SEkFi5fTcDhpMyrOSZepSvOImx/N7rXnktDnVbixl73/meIZ2MrenBuIx8JMC4OytTy1ARQ/dFqYFYzLLYm+DNoR1bX9Mk0FZwBfMUOU0jHhSJ63nX99wUWLk4RaZfZWPopfR1fzXjM5IiGQqBAvQdB04t89/1O/w1cDnyilFU=');
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => 'a487be44ec94ead5b8d7476454243348']);
$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('hello');

$redis = new Redis();
$redis->connect('127.0.0.1', 6379, 2.5);
$msg = $redis->lPop('msgq');
if ($msg){
        $json_array = json_decode($msg,true);
        $response = $bot->pushMessage($json_array["events"][0]["source"]["userId"], $textMessageBuilder);
};
$redis->close();

?>
