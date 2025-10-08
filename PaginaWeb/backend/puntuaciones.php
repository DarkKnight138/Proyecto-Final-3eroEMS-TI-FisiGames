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
<title>Puntuaciones - FisiGames</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
<style>
  @import 
url('https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap');


  body {
    margin: 0;
    font-family: 'Orbitron', sans-serif;
    background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
    color: #eee;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }
nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #1a1a2e;
  padding: 0 2rem;
  height: 60px;
  box-shadow: 0 0 15px #00ffe7;
  position: sticky;
  top: 0;
  z-index: 1000;
}

.logo {
  font-size: 1.8rem;
  font-weight: 700;
  color: #00ffe7;
  letter-spacing: 2px;
  user-select: none;
  cursor: default;
  white-space: nowrap;
  padding-left: 20px;
  padding-right: 20px;
}

.nav-links {
  display: flex;
  justify-content: space-between;
  width: 100%;
}

.nav-left,
.nav-right {
  display: flex;
  gap: 10px;
}

.nav-links a {
  text-decoration: none;
  color: #eee;
  font-weight: 600;
  font-size: 1rem;
  padding: 8px 12px;
  border-radius: 6px;
  transition: background-color 0.3s ease, color 0.3s ease;
  display: flex;
  align-items: center;
  gap: 6px;
}

.nav-links a:hover,
.nav-links a.active {
  background-color: #00ffe7;
  color: #1a1a2e;
  box-shadow: 0 0 8px #00ffe7;
}

  main {
 flex-grow: 1;
 padding: 3rem 2rem;
 text-align: center;
 margin-bottom: 3rem;
 padding-bottom: 40px;
}
  h1 {
    color: #00ffe7;
    font-size: 2.5rem;
    margin-bottom: 2rem;
    text-shadow: 0 0 10px #00ffe7;
  }
  .switch-button {
    background-color: #00ffe7;
    color: #1a1a2e;
    font-weight: bold;
    padding: 10px 20px;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 0 10px #00ffe7;
    transition: background-color 0.3s ease, transform 0.3s ease;
    margin-bottom: 2rem;
  }
  .switch-button:hover {
    background-color: #02e0d0;
    transform: scale(1.05);
  }
  .ranking-container {
    max-width: 600px;
    margin: 0 auto;
    position: relative;
  }
  .ranking-table {
    background-color: #1a1a2e;
    border: 2px solid #00ffe7;
    border-radius: 15px;
    padding: 1rem;
    box-shadow: 0 0 15px #00ffe733;
    transition: opacity 0.5s ease;
    position: absolute;
    width: 100%;
    left: 0;
    top: 0;
  }
  .ranking-table.hidden {
    opacity: 0;
    pointer-events: none;
  }
  /* Contenedor con scroll */
  .scrollable-table {
    max-height: 350px; /* altura para aprox. 10 filas */
    overflow-y: auto;
    border-radius: 10px;
  }
  table {
    width: 100%;
    border-collapse: collapse;
  }
  th, td {
    padding: 12px;
    border-bottom: 1px solid #00ffe722;
  }
  th {
    color: #00ffe7;
    font-size: 1.1rem;
    position: sticky;
    top: 0;
    background-color: #1a1a2e;
    z-index: 2;
  }
  td {
    color: #eee;
    font-size: 1rem;
  }
  .nav-links {
    display: flex;
    justify-content: space-between;
    width: 100%;
  }
  .nav-left, .nav-right {
    display: flex;
    gap: 10px;
  }
</style>
</head>
<body>
<nav>
  <div class="logo">FisiGames</div>
  <div class="nav-links">
    <div class="nav-left">
      <a href="inicio.php"><i class="fas fa-home"></i> Inicio</a>
      <a href="puntuaciones.php"><i class="fas fa-trophy"></i> Puntuaciones</a>
      <a href="grupos.php"><i class="fas fa-users"></i> Grupos</a>
    </div>
    <div class="nav-right">
      <a href="perfil.php"><i class="fas fa-user"></i> Perfil</a>
    </div>
  </div>
</nav>

<main>
  <h1 style="margin-top: -20px;">Ranking de Puntuaciones</h1>
  <div class="switch-buttons">
    <button class="switch-button" onclick="toggleRankingUsu()">Vista de Usuarios</button>
    <button class="switch-button" onclick="toggleRankingGru()">Vista de Grupos</button>
  </div>
  <div class="ranking-container">
    <div id="ranking-jugadores" class="ranking-table">
      <div class="scrollable-table">
        <table>
          <thead>
            <tr><th>Posición</th><th>Jugador</th><th>Puntos</th></tr>
          </thead>
          <tbody>
            <tr><td>1</td><td>Santi</td><td>1200</td></tr>
            <tr><td>2</td><td>cago</td><td>950</td></tr>
            <tr><td>3</td><td>MasterFoxx</td><td>870</td></tr>
            <tr><td>4</td><td>Nachopro</td><td>750</td></tr>
            <tr><td>5</td><td>Player5</td><td>730</td></tr>
            <tr><td>6</td><td>Player6</td><td>710</td></tr>
            <tr><td>7</td><td>Player7</td><td>690</td></tr>
            <tr><td>8</td><td>Player8</td><td>670</td></tr>
            <tr><td>9</td><td>Player9</td><td>650</td></tr>
            <tr><td>10</td><td>Player10</td><td>640</td></tr>
            <tr><td>11</td><td>Player11</td><td>630</td></tr>
            <tr><td>12</td><td>Player12</td><td>620</td></tr>
          </tbody>
        </table>
      </div>
    </div>
    <div id="ranking-grupos" class="ranking-table hidden">
      <div class="scrollable-table">
        <table>
          <thead>
            <tr><th>Posición</th><th>Grupo</th><th>Puntos</th></tr>
          </thead>
          <tbody>
            <tr><td>1</td><td>Los Físicos</td><td>3100</td></tr>
            <tr><td>2</td><td>GameMasters</td><td>2890</td></tr>
            <tr><td>3</td><td>Quantum Team</td><td>2670</td></tr>
            <tr><td>4</td><td>Pixeles</td><td>2500</td></tr>
            <tr><td>5</td><td>Grupo5</td><td>2400</td></tr>
            <tr><td>6</td><td>Grupo6</td><td>2300</td></tr>
            <tr><td>7</td><td>Grupo7</td><td>2200</td></tr>
            <tr><td>8</td><td>Grupo8</td><td>2100</td></tr>
            <tr><td>9</td><td>Grupo9</td><td>2000</td></tr>
            <tr><td>10</td><td>Grupo10</td><td>1900</td></tr>
            <tr><td>11</td><td>Grupo11</td><td>1800</td></tr>
            <tr><td>12</td><td>Grupo12</td><td>1700</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <br><br><br><br>
</main>
<script>
  const rankingJugadores = document.getElementById("ranking-jugadores");
  const rankingGrupos = document.getElementById("ranking-grupos");
  const switchBtn = document.querySelector(".switch-button");
  function toggleRankingUsu() {
      rankingJugadores.classList.remove("hidden");
      rankingGrupos.classList.add("hidden");
  }
  function toggleRankingGru() {
      rankingJugadores.classList.add("hidden");
      rankingGrupos.classList.remove("hidden");
  }
</script>
</body>
</html>
