<?php
// Load composer
require __DIR__ . '/vendor/autoload.php';

$API_KEY = '358685416:AAEaqMDfXUZdDSmsZ_aks1xPgUt6Wzf8va0';
$BOT_NAME = 'tgsample_bot';
$hook_url = 'https://dmclinicyu.com/tgsamplebot/hook.php';
try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($API_KEY, $BOT_NAME);

    // Set webhook
    $result = $telegram->setWebhook($hook_url);

    // Uncomment to use certificate
    //$result = $telegram->setWebhook($hook_url, ['certificate' => $path_certificate]);

    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e;
}
