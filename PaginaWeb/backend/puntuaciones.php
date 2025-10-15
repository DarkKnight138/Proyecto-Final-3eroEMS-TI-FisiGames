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
  @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap');
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
  .logo { font-size: 1.8rem; font-weight: 700; color: #00ffe7; letter-spacing: 2px; user-select: none; cursor: default; white-space: nowrap; }
  .nav-links a { text-decoration: none; color: #eee; font-weight: 600; font-size: 1rem; padding: 8px 12px; border-radius: 6px; transition: background-color 0.3s ease, color 0.3s ease; display: flex; align-items: center; gap: 6px; }
  .nav-links a:hover, .nav-links a.active { background-color: #00ffe7; color: #1a1a2e; box-shadow: 0 0 8px #00ffe7; }
  main { flex-grow: 1; padding: 3rem 2rem; text-align: center; margin-bottom: 3rem; padding-bottom: 40px; }
  h1 { color: #00ffe7; font-size: 2.5rem; margin-bottom: 2rem; text-shadow: 0 0 10px #00ffe7; }
  .switch-button { background-color: #00ffe7; color: #1a1a2e; font-weight: bold; padding: 10px 20px; border: none; border-radius: 12px; cursor: pointer; box-shadow: 0 0 10px #00ffe7; transition: background-color 0.3s ease, transform 0.3s ease; margin-bottom: 2rem; }
  .switch-button:hover { background-color: #02e0d0; transform: scale(1.05); }
  .ranking-container { max-width: 800px; margin: 0 auto; position: relative; min-height: 380px; }
  .ranking-table { background-color: #1a1a2e; border: 2px solid #00ffe7; border-radius: 15px; padding: 1rem; box-shadow: 0 0 15px #00ffe733; transition: opacity 0.3s ease, transform 0.2s ease; position: absolute; width: 100%; left: 0; top: 0; z-index: 1; }
  .ranking-table.hidden { opacity: 0; pointer-events: none; transform: translateY(6px); }
  .scrollable-table { max-height: 350px; overflow-y: auto; border-radius: 10px; }
  table { width: 100%; border-collapse: collapse; }
  th, td { padding: 12px; border-bottom: 1px solid #00ffe722; }
  th { color: #00ffe7; font-size: 1.1rem; position: sticky; top: 0; background-color: #1a1a2e; z-index: 2; }
  td { color: #eee; font-size: 1rem; }
  .nav-links { display: flex; justify-content: space-between; width: 100%; }
  .nav-left, .nav-right { display: flex; gap: 10px; }
  @media (max-width: 820px) {
    .ranking-table { position: static; margin-bottom: 1rem; width: 100%; z-index: auto; }
    .ranking-container { min-height: auto; }
  }
</style>
</head>
<body>
<nav>
  <div class="logo">FisiGames</div>
  <div class="nav-links">
    <div class="nav-left">
      <a href="inicio.php"><i class="fas fa-home"></i> Inicio</a>
      <a href="grupos.php"><i class="fas fa-users"></i> Grupos</a>
    </div>
    <div class="nav-right">
      <a href="perfil.php"><i class="fas fa-user"></i> Perfil</a>
    </div>
  </div>
</nav>

<main>
  <h1>Ranking de Puntuaciones</h1>
  <button id="switchBtn" class="switch-button" type="button">Cambiar a vista de Grupos</button>

  <div class="ranking-container">
    <!-- Tabla de jugadores -->
    <div id="ranking-jugadores" class="ranking-table" aria-hidden="false">
      <div class="scrollable-table">
        <table>
          <thead>
            <tr><th>Posición</th><th>Jugador</th><th>Puntos</th></tr>
          </thead>
          <tbody>
<?php
require 'controladores/conexion.php';

if (!isset($conexion)) {
    echo '<tr><td colspan="3">Error en la conexión.</td></tr>';
} else {
    $sql_jug = "SELECT nombre, puntuacion_total FROM cuentas ORDER BY puntuacion_total DESC LIMIT 10";
    $res_jug = $conexion->query($sql_jug);
    if ($res_jug && $res_jug->num_rows > 0) {
        $pos = 1;
        while ($row = $res_jug->fetch_assoc()) {
            $nombre = htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8');
            $puntos = (int)$row['puntuacion_total'];
            echo "<tr><td>{$pos}</td><td>{$nombre}</td><td>{$puntos}</td></tr>";
            $pos++;
        }
        $res_jug->free();
    } else {
        echo '<tr><td colspan="3">No hay puntuaciones para mostrar.</td></tr>';
    }
}
?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Tabla de grupos -->
    <div id="ranking-grupos" class="ranking-table hidden" aria-hidden="true">
      <div class="scrollable-table">
        <table>
          <thead>
            <tr><th>Posición</th><th>Grupo</th><th>Puntos</th></tr>
          </thead>
          <tbody>
<?php
if (!isset($conexion)) {
    echo '<tr><td colspan="3">Error en la conexión.</td></tr>';
} else {
    $sqlGrupos = "
        SELECT g.id_grupo, g.nombre AS grupo_nombre,
               SUM(c.puntuacion_total) AS puntos
        FROM grupos g
        JOIN pertenece_a pa ON pa.id_grupo = g.id_grupo
        JOIN cuentas c ON c.id_cuenta = pa.id_cuenta
        GROUP BY g.id_grupo, g.nombre
        ORDER BY puntos DESC
        LIMIT 10
    ";
    $resGrupos = $conexion->query($sqlGrupos);
    if ($resGrupos && $resGrupos->num_rows > 0) {
        $pos = 1;
        while ($row = $resGrupos->fetch_assoc()) {
            $nombre = htmlspecialchars($row['grupo_nombre'], ENT_QUOTES, 'UTF-8');
            $puntos = (int)$row['puntos'];
            echo "<tr><td>{$pos}</td><td>{$nombre}</td><td>{$puntos}</td></tr>";
            $pos++;
        }
        $resGrupos->free();
    } else {
        echo '<tr><td colspan="3">No hay grupos con puntuaciones para mostrar.</td></tr>';
    }
}
?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <br><br><br><br>
</main>

<script>
(function(){
  const btn = document.getElementById('switchBtn');
  const jugadores = document.getElementById('ranking-jugadores');
  const grupos = document.getElementById('ranking-grupos');

  function showJugadores() {
    jugadores.classList.remove('hidden');
    jugadores.setAttribute('aria-hidden','false');
    grupos.classList.add('hidden');
    grupos.setAttribute('aria-hidden','true');
    btn.textContent = 'Cambiar a vista de Grupos';
  }

  function showGrupos() {
    grupos.classList.remove('hidden');
    grupos.setAttribute('aria-hidden','false');
    jugadores.classList.add('hidden');
    jugadores.setAttribute('aria-hidden','true');
    btn.textContent = 'Cambiar a vista de Jugadores';
  }

  // estado inicial
  showJugadores();

  btn.addEventListener('click', function(){
    if (jugadores.classList.contains('hidden')) {
      showJugadores();
    } else {
      showGrupos();
    }
  });
})();
</script>
</body>
</html>
