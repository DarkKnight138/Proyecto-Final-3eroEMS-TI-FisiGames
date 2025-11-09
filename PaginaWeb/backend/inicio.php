<!DOCTYPE html>
<html lang="es">
<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Fisi</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
  
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="inicio.css" />
</head>

<body>
  <nav id="navbar">
    <div class="left-section">
      <div class="logo">FisiGames</div>
      <div class="nav-links left-links">
        <a href="inicio.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="puntuaciones.php"><i class="fas fa-trophy"></i> Puntuaciones</a>
        <a href="grupos.php"><i class="fas fa-users"></i> Grupos</a>
      </div>
    </div>
    <div class="nav-links right-links">
      <a href="perfil.php"><i class="fas fa-user"></i> Perfil</a>
    </div>
    <div class="menu-toggle" id="menu-toggle" aria-label="Menú de navegación">
      <div></div>
      <div></div>
      <div></div>
    </div>
  </nav>

  <main>
    <h1>Bienvenido a FisiGames</h1>
    <section class="games-section">
      <h2>Juegos Disponibles</h2>
      <div class="game-grid">
        <div class="game-card">
          <a href="../Juegos/2048/2048.php">
            <img src="imgs/2048.jpg" alt="2048" />
            <h3>2048</h3>
          </a>
        </div>
        <div class="game-card">
          <a href="../Juegos/JuegoMemoria/Juego-memoria.php">
            <img src="imgs/memoria.png" alt="Juego de Memoria" />
            <h3>Juego de la memoria</h3>
          </a>
        </div>
        <div class="game-card">
          <a href="../Juegos/JuegoMosqueta/mosqueta.php">
            <img src="imgs/mosqueta.jpg" alt="Juego de la Mosqueta" />
            <h3>Juego de la mosqueta</h3>
          </a>
        </div>
        <div class="game-card">
          <a href="../Juegos/SimonDice/simondice.php">
            <img src="imgs/simon.jpg" alt="Simon Dice" />
            <h3>Simon dice</h3>
          </a>
        </div>
        <div class="game-card">
          <a href="../Juegos/Tateti/tateti.php">
            <img src="imgs/tateti.jpg" alt="Tateti" />
            <h3>Tateti</h3>
          </a>
        </div>
        <div class="game-card">
          <a href="../Juegos/Montyhall/monty.php">
            <img src="imgs/monty.jpg" alt="Monty Hall" />
            <h3>Monty Hall</h3>
          </a>
        </div>
      </div>
    </section>
  </main>

  <script src="inicio.js"></script>
</body>
</html>
