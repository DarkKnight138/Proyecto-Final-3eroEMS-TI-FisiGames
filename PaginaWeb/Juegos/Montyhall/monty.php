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
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Monty Hall - FisiGames</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
  <link rel="stylesheet" href="monty.css" />
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
      <div></div><div></div><div></div>
    </div>
  </nav>

  <main>
    <h2 class="title">Monty Hall</h2>

    <div class="doors" id="doors">
      <div class="door" data-index="0" id="door-0" role="button" aria-label="Puerta 1">🚪</div>
      <div class="door" data-index="1" id="door-1" role="button" aria-label="Puerta 2">🚪</div>
      <div class="door" data-index="2" id="door-2" role="button" aria-label="Puerta 3">🚪</div>
    </div>

    <div class="controls" id="controls" aria-live="polite">
      <button class="btn" id="btn-mantener" style="display:none">Mantener</button>
      <button class="btn" id="btn-cambiar" style="display:none">Cambiar</button>
      <button class="btn-restart" id="btn-reiniciar" style="display:none">Reiniciar</button>
    </div>

    <div class="message" id="message">Elige una puerta para empezar.</div>
  </main>

  <script src="monty.js"></script>
</body>
</html>
