<?php
session_start();

// Si no está logueado, redirige a login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../backend/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Juego de Memoria - FisiGames</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

  <!-- Estilos -->
  <link rel="stylesheet" href="Juego-memoria.css">
</head>
<body>
  <!-- Navbar igual que en inicio.php -->
  <nav id="navbar">
    <div class="left-section">
      <div class="logo">FisiGames</div>
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

  <!-- Contenido principal -->
  <main>
    <h1>Juego de Memoria</h1>
    <div class="botones-juego">
      <button onclick="iniciarJuego()">Iniciar Juego</button>
      <button onclick="reiniciarJuego()">Reiniciar Juego</button>
    </div>
    <table id="tablero"></table>
    <div id="mensaje"></div>
  </main>

  <!-- Scripts -->
  <script src="Juego-memoria.js"></script>
  <script>
    // Script para el menú responsive
    const menuToggle = document.getElementById('menu-toggle');
    const navbar = document.getElementById('navbar');
    menuToggle.addEventListener('click', () => {
      navbar.classList.toggle('expanded');
    });
  </script>
</body>
</html>
