<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - FisiGames</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap');


    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }


    body {
      font-family: 'Orbitron', sans-serif;
      background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
      color: #eee;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 60px;
      /* espacio para navbar */
    }


    nav {
      width: 100%;
      height: 60px;
      background-color: #1a1a2e;
      box-shadow: 0 0 15px #00ffe7;
      display: flex;
      align-items: center;
      padding: 0 2rem;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 1000;
    }


    .logo {
      font-size: 1.8rem;
      font-weight: 700;
      color: #00ffe7;
      letter-spacing: 2px;
      user-select: none;
    }


    .login-container {
      background-color: rgba(26, 26, 46, 0.9);
      padding: 2rem 2.5rem;
      border-radius: 10px;
      box-shadow: 0 0 20px #00ffe7;
      max-width: 400px;
      width: 100%;
      text-align: center;
      margin-top: 80px;
      /* 🔹 Separación del navbar */
    }


    h2 {
      color: #00ffe7;
      margin-bottom: 1.5rem;
      text-shadow: 0 0 8px #00ffe7;
    }


    .input-group {
      margin-bottom: 1.2rem;
      text-align: left;
    }


    label {
      display: block;
      margin-bottom: 0.3rem;
      color: #ccc;
    }


    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 6px;
      outline: none;
      font-size: 1rem;
      background-color: #1a1a2e;
      color: #eee;
      box-shadow: inset 0 0 5px #00ffe7;
    }


    .btn-login {
      margin-top: 1rem;
      background-color: transparent;
      border: 2px solid #00ffe7;
      color: #00ffe7;
      padding: 10px 18px;
      border-radius: 20px;
      font-size: 1rem;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
    }


    .btn-login:hover {
      background-color: #00ffe7;
      color: #1a1a2e;
      box-shadow: 0 0 10px #00ffe7;
    }


    .no-account {
      margin-top: 1.5rem;
      color: #ccc;
      font-size: 0.9rem;
    }


    .no-account a {
      color: #00ffe7;
      text-decoration: none;
      font-weight: bold;
    }


    .no-account a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>


  <!-- NAVBAR SOLO LOGO -->
  <nav>
    <div class="logo">FisiGames</div>
  </nav>


  <!-- FORMULARIO LOGIN -->
  <div class="login-container">
    <h2>Iniciar Sesión</h2>
    <form id="login-form">
      <div class="input-group">
        <label for="email">Email</label>
        <input type="text" id="email" name="email" required />
      </div>
      <div class="input-group">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required />
      </div>
      <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt"></i> Entrar</button>
    </form>


    <div class="no-account">
      ¿No tenés cuenta? <a href="register.php">Registrate</a>
    </div>
    <div class="no-account">
      ¿Olvidaste tu contrasenia? <a href="restablecer-contrasenia">Toca aqui</a>
    </div>
    
  </div>


  <script>
  const form = document.getElementById('login-form');
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    const email = form.email.value;
    const password = form.password.value;

    // Crea el FormData para enviar los datos
    const formData = new FormData();
    formData.append("email", email);
    formData.append("password", password);

    // Crea la conexión con el servidor
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "controladores/controladoresUsuarios/verificar_login.php", true);

    // Cuando el servidor responde
    xhr.onload = function () {
      const respuesta = xhr.responseText.trim();
      if (respuesta === "ok") {
        alert("Bienvenido, " + email);
        window.location.href = "inicio.php";
      } else {
        alert(respuesta); // muestra el mensaje exacto del servidor
      }
    };

    xhr.send(formData); //  No olvides enviar los datos
  });
</script>



</body>

</html>