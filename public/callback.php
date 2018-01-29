<?php
// callback.php
ob_start();

define("LINE_MESSAGING_API_CHANNEL_SECRET", 'a487be44ec94ead5b8d7476454243348');
define("LINE_MESSAGING_API_CHANNEL_TOKEN", '+SEkFi5fTcDhpMyrOSZepSvOImx/N7rXnktDnVbixl73/meIZ2MrenBuIx8JMC4OytTy1ARQ/dFqYFYzLLYm+DNoR1bX9Mk0FZwBfMUOU0jHhSJ63nX99wUWLk4RaZfZWPopfR1fzXjM5IiGQqBAvQdB04t89/1O/w1cDnyilFU=');

require __DIR__ . "/../vendor/autoload.php";

$bot = new \LINE\LINEBot(
    new \LINE\LINEBot\HTTPClient\CurlHTTPClient(LINE_MESSAGING_API_CHANNEL_TOKEN),
    ['channelSecret' => LINE_MESSAGING_API_CHANNEL_SECRET]
);

$signature = $_SERVER["HTTP_" . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
$body = file_get_contents("php://input");
var_dump(json_decode($body, 1));
$raw = ob_get_clean();
file_put_contents('linedump.txt', $raw."\n=====================================\n", FILE_APPEND);

$events = $bot->parseEventRequest($body, $signature);

foreach ($events as $event) {
    if ($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage) {
        $reply_token = $event->getReplyToken();
	// 回覆電話及email
        if (false !== (strpos($event->getText(), '怡慧'))) {
            $text = '手機: 0918-225-772' . PHP_EOL . 'Email: tracylee.dm@gmail.com';
        } else if (false !== (strpos($event->getText(), '嵐芬'))) {
            $text = '手機: 0918-163-213' . PHP_EOL . 'Email: lanfen415@gmail.com';
        } else if (false !== (strpos($event->getText(), '小映'))) {
            $text = '手機: 0952-123-975' . PHP_EOL . 'Email: yvanne1009@gmail.com';
        } else if (false !== (strpos($event->getText(), '呈蔚'))) {
            $text = '手機: 0919-903-068' . PHP_EOL . 'Email: michlin2001@hotmail.com';
        } else if (false !== (strpos($event->getText(), '景誼'))) {
            $text = '手機: 0912-094-301' . PHP_EOL . 'Email: maysister1@gmail.com';
        } else if (false !== (strpos($event->getText(), '淑華'))) {
            $text = '手機: 0928-614-350' . PHP_EOL . 'Email: diabetesfon@gmail.com';
        } else if (false !== (strpos($event->getText(), '婉瑜'))) {
            $text = '手機: 0932-907-270' . PHP_EOL . 'Email: fish30013@gmail.com';
        } else if (false !== (strpos($event->getText(), '琬婷'))) {
            $text = '手機: 0919-216-032' . PHP_EOL . 'Email: anne08261121@amail.com';
        } else if (false !== (strpos($event->getText(), '曉葦'))) {
            $text = '手機: 0910-664-265' . PHP_EOL . 'Email: sylvia740723@gmail.com';
        } else if (false !== (strpos($event->getText(), '佳惠'))) {
            $text = '手機: 0934-080-518' . PHP_EOL . 'Email: r432188@gmail.com';
        } else if (false !== (strpos($event->getText(), '苑菁'))) {
            $text = '手機: 0956-090-471' . PHP_EOL . 'Email: r94426030@ntu.edu.tw';
        } else if (false !== (strpos($event->getText(), '莉君'))) {
            $text = '手機: 0981-890-735' . PHP_EOL . 'Email: kimi73531@gmail.com';
        } else if (false !== (strpos($event->getText(), '昕慈'))) {
            $text = '手機: 0955-587-793' . PHP_EOL . 'Email: varonica587793@gmail.com';
        } else if (false !== (strpos($event->getText(), '玉琴'))) {
            $text = '手機: 0911-153-009' . PHP_EOL . 'Email: tina9590612@gmail.com';
        } else if (false !== (strpos($event->getText(), '令燊'))) {
            $text = '手機: 0910-245-547' . PHP_EOL . 'Email: dvsseed@gmail.com';
        } else if (false !== (strpos($event->getText(), '煜順'))) {
            $text = '手機: 0932-541-893' . PHP_EOL . 'Email: shunbmw@gmail.com';
        } else if (false !== (strpos($event->getText(), '靜怡'))) {
            $text = '手機: 0933-987-728' . PHP_EOL . 'Email: meimei1201025@gmail.com';
        } else if (false !== (strpos($event->getText(), '惠苓'))) {
            $text = '手機: 0935-500-949' . PHP_EOL . 'Email: shinomay@gmail.com';
        } else if (false !== (strpos($event->getText(), '鏡儒'))) {
            $text = '手機: 0978-715-030' . PHP_EOL . 'Email: suae88@gmail.com';
        } else if (false !== (strpos($event->getText(), '漢隆'))) {
            $text = '手機: 空' . PHP_EOL . 'Email: whl530415@gmail.com';
        } else if (false !== (strpos($event->getText(), '芳宜'))) {
            $text = '手機: 0928-323-652' . PHP_EOL . 'Email: enoch.chiu55@gmail.com';
        } else if (false !== (strpos($event->getText(), '一萍'))) {
            $text = '手機: 0923-201-177' . PHP_EOL . 'Email: epingeva@gmail.com';
        } else if (false !== (strpos($event->getText(), '淑珍'))) {
            $text = '手機: 0912-588-384' . PHP_EOL . 'Email: littlelisa0958@gmail.com';
        } else if (false !== (strpos($event->getText(), '政良'))) {
            $text = '手機: 0928-532-408' . PHP_EOL . 'Email: clchi1215@gmail.com';
        } else if (false !== (strpos($event->getText(), '泳琳'))) {
            $text = '手機: 0910-145-458' . PHP_EOL . 'Email: berlintw@gmail.com';
        } else if (false !== (strpos($event->getText(), '舜文'))) {
            $text = '手機: 0933-244-723' . PHP_EOL . 'Email: lsw9108@gmail.com';
        } else if (false !== (strpos($event->getText(), '思琦'))) {
            $text = '手機: 0916-897-182' . PHP_EOL . 'Email: 空';
        } else if (false !== (strpos($event->getText(), '淑娟'))) {
            $text = '手機: 0952-026-059' . PHP_EOL . 'Email: 空';
        } else if (false !== (strpos($event->getText(), '醫師'))) {
            $text = '手機: 0939-239-475' . PHP_EOL . 'Email: dm9556670@gmail.com';
        } else if (false !== (strpos($event->getText(), '能俊'))) {
            $text = '手機: 0939-239-475' . PHP_EOL . 'Email: dm9556670@gmail.com';
	} else {
	        $text = '游能俊診所關心您...' . $event->getText();
	}
        $bot->replyText($reply_token, $text);
    }
}

echo "OK";
