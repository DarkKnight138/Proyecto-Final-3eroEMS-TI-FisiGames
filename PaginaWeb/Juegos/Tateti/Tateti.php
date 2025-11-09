<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../backend/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tateti - FisiGames</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <link rel="stylesheet" href="Tateti.css">
  <link rel="stylesheet" href="../style.css">
  <script src="tateti.js" defer></script>
</head>
<body>
  <nav id="navbar">
    <div class="left-section">
      <div class="logo" onclick="window.location.href='../../backend/inicio.php'">FisiGames</div>
      <div class="nav-links left-links">
        <a href="../../backend/inicio.php"><i class="fa-solid fa-house"></i> Inicio</a>
        <a href="../../backend/puntuaciones.php"><i class="fa-solid fa-trophy"></i> Puntuaciones</a>
        <a href="../../backend/grupos.php"><i class="fa-solid fa-users"></i> Grupos</a>
      </div>
    </div>
    <div class="nav-links right-links">
      <a href="../../backend/perfil.php"><i class="fa-solid fa-user"></i> Perfil</a>
    </div>
    <div class="menu-toggle" id="menu-toggle" aria-label="Menú de navegación">
      <div></div>
      <div></div>
      <div></div>
    </div>
  </nav>
  <main>
    <h1>Tateti</h1>
    <div class="tablero">
      <button id="0"></button>
      <button id="1"></button>
      <button id="2"></button>
      <button id="3"></button>
      <button id="4"></button>
      <button id="5"></button>
      <button id="6"></button>
      <button id="7"></button>
      <button id="8"></button>
    </div>
    <button id="reiniciar" class="btnReinicio">Reiniciar</button>
  </main>
  <script>
    const menuToggle = document.getElementById('menu-toggle');
    const navbar = document.getElementById('navbar');
    menuToggle.addEventListener('click', () => {
      navbar.classList.toggle('expanded');
    });
  </script>
</body>
</html>
