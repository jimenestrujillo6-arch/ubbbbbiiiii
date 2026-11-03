<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>DISPOSITIVO NO AUTORIZADO</title>
  <style>
    *{box-sizing:border-box;margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial}
    body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#000;color:#fff}
    .card{max-width:420px;padding:32px;border-radius:12px;background:#0b0d10;text-align:center;box-shadow:0 8px 30px rgba(0,0,0,.6);border:1px solid #1f2327}
    h1{font-size:20px;margin-bottom:12px;color:#ff6b6b;font-weight:700}
    p{color:#d9d9d9;margin-bottom:22px;line-height:1.5;font-size:15px}
    .actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
    .btn{padding:12px 20px;border-radius:10px;background:#0099ff;color:#fff;text-decoration:none;font-weight:600}
    .btn:hover{background:#0088e6}
    .btn-secondary{background:#333}
    .btn-secondary:hover{background:#2a2a2a}
  </style>
</head>
<body>
  <div class="card" role="main">
    <h1>DISPOSITIVO NO AUTORIZADO AÚN</h1>
    <p>El sistema ha rechazado la autorización. Si cree que hubo un error, puede volver a solicitarla.</p>
    <div class="actions">
      <a class="btn" href="correo.php">VOLVER A AUTORIZAR</a>
      <a class="btn btn-secondary" href="index.php">VOLVER</a>
    </div>
  </div>
</body>
</html>