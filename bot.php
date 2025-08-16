<?php
$update = json_decode(file_get_contents("php://input"), true);

if (isset($update["message"])) {
    $chat_id = $update["message"]["chat"]["id"];
    $text = $update["message"]["text"];

    $token = "8406794244:AAEJ6RoKkEH40F0XTKMdNZA9SGz3pmZehXU";

    $url = "https://api.telegram.org/bot$token/sendMessage";

    file_get_contents($url . "?chat_id=" . $chat_id . "&text=Salom! Siz yozdingiz: " . urlencode($text));
}

http_response_code(200);
echo "OK";
