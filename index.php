<?php
require_once "config.php";
require_once "fpdf.php";

// Telegram API
function bot($method, $data = []) {
    global $API_KEY;
    $url = "https://api.telegram.org/bot$API_KEY/$method";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $res = curl_exec($ch);
    curl_close($ch);
    return json_decode($res, true);
}

// So‘rovni olish
$update = json_decode(file_get_contents("php://input"));
$message = $update->message ?? null;
$callback = $update->callback_query ?? null;

// Chat va user ID
$chat_id = $message->chat->id ?? ($callback->message->chat->id ?? null);
$user_id = $message->from->id ?? ($callback->from->id ?? null);
$text = $message->text ?? null;

// === /start komandasi ===
if ($text == "/start") {
    // Kanal.txt dan kanallarni o‘qish
    $channels = file("channel.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (count($channels) > 0) {
        $keyboard = ["inline_keyboard" => []];
        foreach ($channels as $ch) {
            $keyboard["inline_keyboard"][] = [["text" => "➕ Obuna bo‘lish", "url" => $ch]];
        }
        $keyboard["inline_keyboard"][] = [["text" => "✅ Tekshirish", "callback_data" => "check_sub"]];
        bot("sendMessage", [
            "chat_id" => $chat_id,
            "text" => "Davom etish uchun quyidagi kanallarga obuna bo‘ling 👇",
            "reply_markup" => json_encode($keyboard)
        ]);
    } else {
        showMainMenu($chat_id);
    }
}

// === Obuna tekshirish ===
if ($callback && $callback->data == "check_sub") {
    $channels = file("channel.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $all_subscribed = true;
    foreach ($channels as $ch) {
        $channel = str_replace("https://t.me/", "@", $ch);
        $res = bot("getChatMember", [
            "chat_id" => $channel,
            "user_id" => $user_id
        ]);
        $status = $res["result"]["status"] ?? "left";
        if ($status == "left" || $status == "kicked") {
            $all_subscribed = false;
            break;
        }
    }
    if ($all_subscribed) {
        showMainMenu($chat_id);
    } else {
        bot("answerCallbackQuery", [
            "callback_query_id" => $callback->id,
            "text" => "❌ Siz barcha kanallarga obuna bo‘lmadingiz!",
            "show_alert" => true
        ]);
    }
}

// === Asosiy menu ===
function showMainMenu($chat_id) {
    $buttons = [
        ["text" => "📝 Rezyume yaratish"],
        ["text" => "🗑 Rezyumeni o‘chirish"]
    ];
    bot("sendMessage", [
        "chat_id" => $chat_id,
        "text" => "Asosiy menyu:",
        "reply_markup" => json_encode(["keyboard" => [$buttons], "resize_keyboard" => true])
    ]);
}

// === Rezyume yaratish ===
if ($text == "📝 Rezyume yaratish") {
    bot("sendMessage", [
        "chat_id" => $chat_id,
        "text" => "Ism va familiyangizni kiriting:"
    ]);
    // keyingi qadamlar session orqali olinadi (phpda fayl yoki mysqlda saqlash mumkin)
}

// === Rezyume o‘chirish ===
if ($text == "🗑 Rezyumeni o‘chirish") {
    bot("sendMessage", [
        "chat_id" => $chat_id,
        "text" => "O‘chirmoqchi bo‘lgan rezyumengiz post ID sini yuboring:"
    ]);
}

// === Admin panel ===
if ($text == "/admin" && $user_id == $ADMIN_ID) {
    $buttons = [
        [["text" => "📊 Statistika"], ["text" => "📢 Obuna kanal"]],
        [["text" => "📨 Rezyume kanal"], ["text" => "✉️ Xabar yuborish"]]
    ];
    bot("sendMessage", [
        "chat_id" => $chat_id,
        "text" => "Admin paneliga xush kelibsiz!",
        "reply_markup" => json_encode(["keyboard" => $buttons, "resize_keyboard" => true])
    ]);
}

echo "OK";
