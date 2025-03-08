<?php

// توکن ربات تلگرام خود را در اینجا قرار دهید
$BOT_TOKEN = "YOUR_BOT_TOKEN_HERE";
$API_URL = "https://api.telegram.org/bot$BOT_TOKEN/";

// تابع دریافت پیام‌ها از تلگرام
$update = json_decode(file_get_contents("php://input"), true);
if (!$update) exit;

// اطلاعات اصلی
$message = $update["message"] ?? null;
$chat_id = $message["chat"]["id"] ?? null;
$file_id = $message["document"]["file_id"] ?? null;

// بررسی اینکه آیا فایل ارسال شده است
if (!$file_id) {
    sendMessage($chat_id, "لطفاً یک فایل دیتابیس SQLite ارسال کنید.");
    exit;
}

// دریافت اطلاعات فایل از تلگرام
$file_info = json_decode(file_get_contents($API_URL . "getFile?file_id=" . $file_id), true);
if (!$file_info["ok"]) {
    sendMessage($chat_id, "خطا در دریافت اطلاعات فایل.");
    exit;
}

// دانلود فایل از تلگرام
$file_path = $file_info["result"]["file_path"];
$file_url = "https://api.telegram.org/file/bot$BOT_TOKEN/$file_path";
$file_name = basename($file_path);
$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
$local_file = "uploads/original_$file_name";
$modified_file = "uploads/" . date("Ymd_His") . ".$file_ext";

file_put_contents($local_file, file_get_contents($file_url));

// بررسی و اصلاح دیتابیس
try {
    $pdo = new PDO("sqlite:$local_file");

    // بررسی وجود جدول settings
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='settings';");
    if (!$stmt->fetch()) {
        sendMessage($chat_id, "دیتابیس نامعتبر است. جدول 'settings' یافت نشد.");
        exit;
    }

    // اصلاح مقدار کلیدهای مشخص‌شده در جدول settings
    $keysToUpdate = ["webCertFile", "webKeyFile", "webDomain", "webListen"];
    $stmt = $pdo->prepare("UPDATE settings SET value='' WHERE key=?;");
    foreach ($keysToUpdate as $key) {
        $stmt->execute([$key]);
    }

    // ذخیره تغییرات
    copy($local_file, $modified_file);

    // ارسال فایل اصلاح‌شده به کاربر
    sendDocument($chat_id, $modified_file);
} catch (Exception $e) {
    sendMessage($chat_id, "خطا در پردازش دیتابیس: " . $e->getMessage());
}

// توابع کمکی
function sendMessage($chat_id, $text) {
    global $API_URL;
    file_get_contents($API_URL . "sendMessage?chat_id=$chat_id&text=" . urlencode($text));
}

function sendDocument($chat_id, $file_path) {
    global $API_URL;
    $post_fields = [
        'chat_id' => $chat_id,
        'document' => new CURLFile(realpath($file_path))
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_URL . "sendDocument");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

?>
