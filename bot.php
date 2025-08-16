<?php
$input = file_get_contents("php://input");
file_put_contents("log.txt", $input . PHP_EOL, FILE_APPEND);

$update = json_decode($input, true);

if (isset($update['message'])) {
    $chat_id = $update['message']['chat']['id'];
    $text = $update['message']['text'] ?? '';

    $token = "BOT_TOKEN"; // Tokeningizni shu yerga yozing
    $url = "https://api.telegram.org/bot$token/sendMessage";

    $data = [
        "chat_id" => $chat_id,
        "text" => "Siz yubordingiz: $text"
    ];

    file_get_contents($url . "?" . http_build_query($data));
}

http_response_code(200);
echo "OK";
