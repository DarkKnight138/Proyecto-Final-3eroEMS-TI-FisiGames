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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>2048</title>
  <link rel="stylesheet" href="2048.css">
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>

<body>
  <nav id="navbar">
    <div class="left-section">
      <div class="logo">FisiGames</div>
      <div class="nav-links left-links">
        <a href="../../backend/inicio.php"><i class="fa-solid fa-house"></i> Inicio</a>
        <a href="../../backend/puntuaciones.php"><i class="fas fa-trophy"></i> Puntuaciones</a>
        <a href="../../backend/grupos.php"><i class="fas fa-users"></i> Grupos</a>
      </div>
    </div>
    <div class="nav-links right-links">
      <a href="../../backend/perfil.php"><i class="fas fa-user"></i> Perfil</a>
    </div>
    <div class="menu-toggle" id="menu-toggle" aria-label="Menú de navegación">
      <div></div>
      <div></div>
      <div></div>
    </div>
  </nav>

  <br>
  <h1>Bienvenido al 2048</h1>
  <button id="botonReglas" onclick="abrir()">Reglas</button>
  <div id="cajadeReglas" class="modal">
    <div class="modal-contenido">
      <span class="cerrarBoton" onclick="cerrar()">&times;</span>
      <h1>Reglas</h1>
      <p>
        El objetivo del juego es crear una ficha con el número 2048 combinando fichas del mismo valor.
        El tablero es de 5x5 y las fichas se deslizan en cuatro direcciones: arriba, abajo, izquierda o derecha.
        Al deslizar las fichas, las que tienen el mismo número se combinan y se suman en una ficha con el valor
        resultante.
        Después de cada movimiento, aparece una nueva ficha con el número 2 o 4 en una casilla vacía aleatoria.
        El juego termina cuando el tablero está lleno y no hay más movimientos posibles.
        Ganas al crear una ficha con el número 2048.
      </p>
    </div>
  </div>

  <button id="AJugar" onclick="iniciarJuego()">Empezar</button>
  <button id="reiniciarJuego" onclick="reiniciarJuego()">Reiniciar Juego</button>

  <div id="tabla" class="modal">
    <div class="modal-contenido">
      <span class="cerrarBoton" onclick="cerrar2()">&times;</span>
      <table>
        <tr>
          <td id="a1"></td>
          <td id="a2"></td>
          <td id="a3"></td>
          <td id="a4"></td>
          <td id="a5"></td>
        </tr>
        <tr>
          <td id="b1"></td>
          <td id="b2"></td>
          <td id="b3"></td>
          <td id="b4"></td>
          <td id="b5"></td>
        </tr>
        <tr>
          <td id="c1"></td>
          <td id="c2"></td>
          <td id="c3"></td>
          <td id="c4"></td>
          <td id="c5"></td>
        </tr>
        <tr>
          <td id="d1"></td>
          <td id="d2"></td>
          <td id="d3"></td>
          <td id="d4"></td>
          <td id="d5"></td>
        </tr>
        <tr>
          <td id="e1"></td>
          <td id="e2"></td>
          <td id="e3"></td>
          <td id="e4"></td>
          <td id="e5"></td>
        </tr>
      </table>
    </div>
  </div>

  <script src="2048.js"></script>
</body>

</html>