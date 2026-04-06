<?php
$token = "8666429151:AAHHt7FMidrzlti6YuHMQ0o7vDUdwdQdQ2U";

$input = file_get_contents("php://input");
file_put_contents("log.txt", "Fecha: " . date("Y-m-d H:i:s") . " - Input: " . $input . PHP_EOL, FILE_APPEND);

$update = json_decode($input, true);
$chatId = $update["message"]["chat"]["id"] ?? null;
$message = $update["message"]["text"] ?? "";

if (!$chatId) {
    exit;
}

// Limpiamos y pasamos a minúsculas para facilitar la comparación
$mensajeLimpio = strtolower(trim($message));
$response = "Lo siento, el producto '$message' no se encuentra en nuestro inventario."; 

// --- Lógica de Respuestas Generales ---
if ($mensajeLimpio == "/start") {
    $response = "Bienvenido al asistente del Supermercado. ¿Qué producto buscas?";
} elseif ($mensajeLimpio == "hola") {
    $response = "¡Hola! Soy tu bot de pasillos. Dime un producto y te diré dónde está.";
} 

// --- Lógica de Pasillos del Supermercado ---
// Pasillo 1
elseif (in_array($mensajeLimpio, ["carne", "queso", "jamon", "jamón"])) {
    $response = "El producto " . ucfirst($mensajeLimpio) . " se encuentra en el Pasillo 1.";
} 
// Pasillo 2
elseif (in_array($mensajeLimpio, ["leche", "yogurth", "cereal"])) {
    $response = "El producto " . ucfirst($mensajeLimpio) . " se encuentra en el Pasillo 2.";
} 
// Pasillo 3
elseif (in_array($mensajeLimpio, ["bebidas", "jugos"])) {
    $response = "El producto " . ucfirst($mensajeLimpio) . " se encuentra en el Pasillo 3.";
} 
// Pasillo 4
elseif (in_array($mensajeLimpio, ["pan", "pasteles", "tortas"])) {
    $response = "El producto " . ucfirst($mensajeLimpio) . " se encuentra en el Pasillo 4.";
} 
// Pasillo 5
elseif (in_array($mensajeLimpio, ["detergente", "lavaloza"])) {
    $response = "El producto " . ucfirst($mensajeLimpio) . " se encuentra en el Pasillo 5.";
}

// --- Envío de la respuesta ---
enviarMensaje($chatId, $response, $token);

function enviarMensaje($chatId, $text, $token) {
    $url = "https://api.telegram.org/bot" . $token . "/sendMessage";
    
    $data = json_encode([
        'chat_id' => $chatId,
        'text' => $text
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $result = curl_exec($ch);
    file_put_contents("log.txt", "Resultado envio: " . $result . PHP_EOL, FILE_APPEND);
    curl_close($ch);
}
