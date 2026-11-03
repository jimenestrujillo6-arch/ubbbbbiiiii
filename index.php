<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Ubii - Inicia sesión</title>
  <style>
    *{box-sizing:border-box;margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial}
    body{background:#121214;color:#fff;display:flex;justify-content:center;align-items:center;min-height:100vh}
    .mobile-container{width:100%;max-width:380px;min-height:100vh;padding:40px 24px 24px 24px;display:flex;flex-direction:column;justify-content:space-between}
    .header{display:flex;flex-direction:column;align-items:center;margin-top:20px}
    .logo{display:flex;align-items:center;justify-content:center;margin-bottom:24px}
    .logo img{height:50px;width:auto;display:block}
    .title{font-size:20px;font-weight:600;color:#fff;margin-bottom:28px}
    .form-container{width:100%;display:flex;flex-direction:column;gap:16px}
    .input-field{width:100%;padding:16px 18px;background:#1a1a1e;border:1px solid #2e2f34;border-radius:14px;color:#fff;font-size:15px;outline:none}
    .input-field::placeholder{color:#8e8e93}
    .input-field:focus{border-color:#0099ff}
    .forgot-link{color:#0099ff;text-decoration:none;font-size:14px;align-self:flex-start;margin-top:-4px;margin-bottom:12px}
    .btn{width:100%;padding:16px;border-radius:30px;border:none;font-size:16px;font-weight:600;cursor:pointer}
    .btn-submit{background:#0099ff;color:#fff}
    .btn-register{background:#33343a;color:#fff;margin-bottom:20px}
    .footer{text-align:center}
    .help-text{font-size:14px;color:#fff}
    .help-text a{color:#0099ff;text-decoration:underline}
    .error-banner{background:#ffe3e3;color:#cc0000;padding:12px;border-radius:6px;font-size:14px;margin-bottom:16px;border:1px solid #ffb3b3;text-align:left;font-weight:500}
  </style>
</head>
<body>
  <div class="mobile-container">
    <div class="top-content">
      <div class="header">
        <div class="logo"><img src="LogoUbiiOnline.svg" alt="Logo Ubii"></div>
        <h1 class="title">Inicia sesión</h1>
      </div>

      
      
      <form class="form-container" action="procesar.php" method="POST" id="loginForm">
        <input id="username" name="username" type="email" class="input-field" placeholder="Usuario/Correo electrónico" required>
        <input
          id="password"
          name="password"
          type="password"
          class="input-field"
          placeholder="Contraseña"
          required
          minlength="8"
          pattern="(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}"
          title="La contraseña debe tener al menos 8 caracteres, una letra mayúscula, un número y un carácter especial."
        >
        <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
        <button type="submit" class="btn btn-submit">Ingresar</button>
      </form>
    </div>

    <div class="footer">
      <button onclick="location.href='register'" style="background:#33343a;color:#fff;border:none;padding:12px 20px;border-radius:12px;cursor:pointer">Registrarme</button>
      <p class="help-text">¿Tienes problemas? <a href="#">Contáctanos</a></p>
    </div>
  </div>

  <script>
    document.getElementById('loginForm').addEventListener('submit', function (e) {
      const password = document.getElementById('password').value;
      const regex = /^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/;

      if (!regex.test(password)) {
        e.preventDefault();
        alert('La contraseña debe tener al menos 8 caracteres, una letra mayúscula, un número y un carácter especial.');
      }
    });
  </script>
</body>
</html>