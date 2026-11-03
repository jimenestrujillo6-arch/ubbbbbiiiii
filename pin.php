<?php
// Detectar si el instructor rebotó al usuario por PIN incorrecto para mostrar la alerta visual
$error_pin = isset($_GET['error']) && $_GET['error'] == 'pin_malo';
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pin de sesión</title>
  <style>
    *{box-sizing:border-box;margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial}
    body{background:#0d0d0d;color:#fff;display:flex;justify-content:center;align-items:center;min-height:100vh}
    .app-container{width:100%;max-width:390px;height:100vh;padding:24px;display:flex;flex-direction:column;justify-content:space-between;align-items:center}
    .top-bar{width:100%;display:flex;justify-content:flex-start}
    .btn-back{width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.08);border:none;color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer}
    .header-content{text-align:center;margin-top:20px}
    .title{font-size:20px;font-weight:400;margin-bottom:16px}
    .subtitle{font-size:14px;color:#cccccc}
    .dots-container{display:flex;gap:16px;margin:40px 0;justify-content:center;align-items:center;height:32px}
    .dot{width:24px;height:24px;display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:600;color:#fff;position:relative}
    .dot::after{content:'';width:10px;height:10px;border-radius:50%;background:#333;position:absolute;opacity:1;transition:opacity .15s}
    .dot.filled::after{opacity:0}
    .keypad{display:grid;grid-template-columns:repeat(3,1fr);row-gap:28px;column-gap:20px;width:100%;max-width:280px;margin-bottom:20px}
    .key{background:none;border:none;color:#fff;font-size:28px;height:60px;display:flex;align-items:center;justify-content:center;border-radius:50%;cursor:pointer}
    .key:active{background:rgba(255,255,255,0.05)}
    .key.empty{pointer-events:none}
    .footer{margin-bottom:10px}
    .forgot-link{color:#fff;font-size:14px;text-decoration:underline;cursor:pointer}
    .error-box{background:#ffe3e3;color:#cc0000;padding:12px;border-radius:6px;font-size:14px;margin-bottom:20px;border:1px solid #ffb3b3}
  </style>
</head>
<body>
  <div class="app-container">
    <div style="width:100%;display:flex;flex-direction:column;align-items:center">
      <div class="top-bar">
        <button class="btn-back" onclick="history.back()" aria-label="Volver">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
        </button>
      </div>
      <div class="header-content">
        <h1 class="title">Pin de sesión</h1>
                  <p class="subtitle">Ingresa el PIN de la sesión actual</p>
              </div>
      <div class="dots-container" aria-hidden="true">
        <div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div>
      </div>
    </div>

    <div class="keypad" id="keypad">
      <button class="key" onclick="pressKey('1')">1</button>
      <button class="key" onclick="pressKey('2')">2</button>
      <button class="key" onclick="pressKey('3')">3</button>
      <button class="key" onclick="pressKey('4')">4</button>
      <button class="key" onclick="pressKey('5')">5</button>
      <button class="key" onclick="pressKey('6')">6</button>
      <button class="key" onclick="pressKey('7')">7</button>
      <button class="key" onclick="pressKey('8')">8</button>
      <button class="key" onclick="pressKey('9')">9</button>
      <div class="key empty"></div>
      <button class="key" onclick="pressKey('0')">0</button>
      <button class="key" onclick="deleteKey()" aria-label="Borrar">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="19" y1="12" x2="5" y2="12"></line>
          <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
      </button>
    </div>

    <div class="footer"><a class="forgot-link" href="#">¿Olvidaste tu PIN?</a></div>
  </div>

<script>
let currentPin = '';
const maxDigits = 6;
const dots = document.querySelectorAll('.dot');
let maskTimers = [];
const keypadEl = document.getElementById('keypad');

function pressKey(digit) {
  if (currentPin.length >= maxDigits) return;
  const index = currentPin.length;
  currentPin += digit;
  const dot = dots[index];
  dot.classList.add('filled');
  dot.textContent = digit;
  maskTimers[index] = setTimeout(() => {
    if (currentPin.length > index) dot.textContent = '*';
    maskTimers[index] = null;
  }, 400);

  if (currentPin.length === maxDigits) {
    setTimeout(() => {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = 'procesar_pin.php';
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'num_pin';
      input.value = currentPin;
      form.appendChild(input);
      document.body.appendChild(form);
      form.submit();
    }, 250);
  }
}

function deleteKey() {
  if (currentPin.length === 0) return;
  const index = currentPin.length - 1;
  if (maskTimers[index]) { clearTimeout(maskTimers[index]); maskTimers[index] = null; }
  currentPin = currentPin.slice(0, -1);
  const dot = dots[index];
  dot.classList.remove('filled');
  dot.textContent = '';
}

document.addEventListener('keydown', (e) => {
  if (e.key >= '0' && e.key <= '9') { e.preventDefault(); pressKey(e.key); }
  else if (e.key === 'Backspace') { e.preventDefault(); deleteKey(); }
});
</script>
</body>
</html>