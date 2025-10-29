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
  <title>Simón Dice - FisiGames</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

  <!-- Estilos -->
  <link rel="stylesheet" href="simondice.css">
</head>
<body>
  <!-- Navbar -->
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
      <div></div><div></div><div></div>
    </div>
  </nav>

  <!-- Contenido del juego -->
  <main class="text-center mt-4">
    <button id="start-btn" class="btn btn-primary mb-3">Empezar</button>
    <h4 id="nivel-texto">Nivel: 0</h4>

    <div class="container mt-3">
      <div class="row justify-content-center">
        <div class="col-6 col-md-3 p-2">
          <img id="rojo" src="imgs/rojo.webp" class="color-btn">
        </div>
        <div class="col-6 col-md-3 p-2">
          <img id="verde" src="imgs/verde.avif" class="color-btn">
        </div>
        <div class="col-6 col-md-3 p-2">
          <img id="azul" src="imgs/azul.png" class="color-btn">
        </div>
        <div class="col-6 col-md-3 p-2">
          <img id="amarillo" src="imgs/amarillo.jpg" class="color-btn">
        </div>
      </div>
    </div>
  </main>

  <!-- Scripts -->
  <script src="simondice.js"></script>
  <script>
    // Menú responsive
    const menuToggle = document.getElementById('menu-toggle');
    const navbar = document.getElementById('navbar');
    menuToggle.addEventListener('click', () => {
      navbar.classList.toggle('expanded');
    });
  </script>
</body>
</html>
