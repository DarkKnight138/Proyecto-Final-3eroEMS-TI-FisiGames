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
  <meta charset="UTF-8">
  <title>Juego de la Mosqueta - FisiGames</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <link rel="stylesheet" href="mosqueta.css">
  <link rel="stylesheet" href="../style.css">
</head>
<body>
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
  <main>
    <h1>Juego de la Mosqueta</h1>
    <p id="dineroTxt">Tienes $1000</p>
    <p>¿Cuánto quieres apostar?</p>
    <input type="text" id="apuesta" placeholder="Ingresa tu apuesta">
    <p>Elige un vaso:</p>
    <div id="vasos">
      <img src="imgs/vaso.png" class="vaso" id="vaso1" onclick="jugar(1)">
      <img src="imgs/vaso.png" class="vaso" id="vaso2" onclick="jugar(2)">
      <img src="imgs/vaso.png" class="vaso" id="vaso3" onclick="jugar(3)">
    </div>
    <p id="mensaje"></p>
    <button id="reiniciarBtn" onclick="reiniciarJuego()">Reiniciar Juego</button>
  </main>
  <script src="mosqueta.js"></script>
  <script>
    const menuToggle = document.getElementById('menu-toggle');
    const navbar = document.getElementById('navbar');
    menuToggle.addEventListener('click', () => {
      navbar.classList.toggle('expanded');
    });
  </script>
</body>
</html>
