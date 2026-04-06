<?php
// 8666429151:AAHHt7FMidrzlti6YuHMQ0o7vDUdwdQdQ2U
$token = "8666429151:AAHHt7FMidrzlti6YuHMQ0o7vDUdwdQdQ2U"; 
$api_url = "https://api.telegram.org/bot" . $token . "/";

echo "Bot encendido... Presiona Ctrl+C para apagarlo.\n";

$offset = 0;
while (true) {
    $updates = json_decode(file_get_contents($api_url . "getUpdates?offset=" . $offset), true);
    
    if (isset($updates["result"])) {
        foreach ($updates["result"] as $update) {
            $offset = $update["update_id"] + 1;
            $chat_id = $update["message"]["chat"]["id"];
            $text = $update["message"]["text"];

            if ($text == "/start") {
                $mensaje = "¡Hola! Soy tu bot de tarea. Recibí tu comando Start.";
                file_get_contents($api_url . "sendMessage?chat_id=$chat_id&text=" . urlencode($mensaje));
            } else {
                file_get_contents($api_url . "sendMessage?chat_id=$chat_id&text=" . urlencode("Me dijiste: " . $text));
            }
        }
    }
    sleep(1); // Espera 1 segundo para no saturar
}
