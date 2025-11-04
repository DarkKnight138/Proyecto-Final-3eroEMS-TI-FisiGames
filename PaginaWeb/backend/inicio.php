!DOCTYPE html>
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
 <style>
   @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap');


   * {
     box-sizing: border-box;
   }


   body {
     margin: 0;
     font-family: 'Orbitron', sans-serif;
     background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
     color: #eee;
     min-height: 100vh;
     display: flex;
     flex-direction: column;
   }


   /* Navbar */
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


   .left-section {
     display: flex;
     align-items: center;
     gap: 2rem;
   }


   .logo {
     font-size: 1.8rem;
     font-weight: 700;
     color: #00ffe7;
     letter-spacing: 2px;
     user-select: none;
     cursor: default;
     white-space: nowrap;
   }


   .nav-links {
     display: flex;
     gap: 2rem;
     align-items: center;
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


   .menu-toggle {
     display: none;
     flex-direction: column;
     cursor: pointer;
     gap: 5px;
   }


   .menu-toggle div {
     width: 25px;
     height: 3px;
     background-color: #00ffe7;
     border-radius: 2px;
     transition: 0.3s;
   }


   @media (max-width: 768px) {
     .nav-links.left-links {
       display: none;
     }


     .menu-toggle {
       display: flex;
     }


     nav.expanded .nav-links.left-links {
       display: flex;
       flex-direction: column;
       background-color: #1a1a2e;
       position: absolute;
       top: 60px;
       left: 0;
       width: 200px;
       padding: 1rem 1.5rem;
       border-radius: 0 0 10px 0;
       box-shadow: 0 0 15px #00ffe7;
     }


     nav.expanded .nav-links.left-links a {
       padding: 10px 0;
       border-radius: 0;
     }
   }


   main {
  flex-grow: 1;
  padding: 1rem 1.5rem 1.5rem;
  text-align: center;
}

main {
  flex-grow: 1;
  padding: 2rem 1rem 1.5rem;
  text-align: center;
}

main h1 {
  font-size: 2.5rem;
  margin-bottom: 0.3rem; /* antes era mucho mayor */
  color: #00ffe7;
  text-shadow: 0 0 8px #00ffe7;
}

.games-section {
  padding: 1rem 0;
}

.games-section h2 {
  font-size: 1.8rem;
  color: #00ffe7;
  margin-top: 0; /* sin separación arriba */
  margin-bottom: 1rem;
  text-shadow: 0 0 8px #00ffe7;
}


   .game-grid {
     display: grid;
     grid-template-columns: repeat(3, 1fr);
     grid-gap: 1rem;
     justify-items: center;
   }


   .game-card {
     background-color: #1a1a2e;
     border: 2px solid #00ffe7;
     border-radius: 15px;
     padding: 1rem;
     width: 100%;
     max-width: 200px;
     transition: transform 0.3s ease, box-shadow 0.3s ease;
     box-shadow: 0 0 10px #00ffe74a;
   }


   .game-card:hover {
     transform: translateY(-5px);
     box-shadow: 0 0 15px #00ffe7;
   }


   .game-card img {
     width: 100%;
     height: 120px;
     object-fit: cover;
     border-radius: 10px;
     margin-bottom: 0.8rem;
   }


   .game-card h3 {
     color: #eee;
     font-size: 1rem;
   }
 </style>
</head>
<body>


 <nav id="navbar">
   <div class="left-section">
     <div class="logo">FisiGames</div>
     <div class="nav-links left-links">
      <a href="inicio.html"><i class="fas fa-home"></i> Inicio</a>
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
  <h1 >Bienvenido a FisiGames</h1>
  <section class="games-section">
    <h2>Juegos Disponibles</h2>
    <div class="game-grid">
      <div class="game-card">
        <a href="../Juegos/2048/2048.php">
          <img src="../imgs/2048.jpg" />
          <h3>2048</h3>
        </a>
      </div>
      <div class="game-card">
        <a href="../Juegos/JuegoMemoria/Juego-memoria.php">
          <img src="../imgs/memoria.png"  />
          <h3>Juego de la memoria</h3>
        </a>
      </div>
      <div class="game-card">
        <a href="../Juegos/JuegoMosqueta/mosqueta.php">
          <img src="../imgs/mosqueta.jpg" />
          <h3>Juego de la mosqueta</h3>
        </a>
      </div>
      <div class="game-card">
        <a href="../Juegos/SimonDice/simondice.php">
          <img src="../imgs/simon.jpg" />
          <h3>Simon dice</h3>
        </a>
      </div>
      <div class="game-card">
        <a href="../Juegos/Tateti/tateti.php">
          <img src="../imgs/tateti.jpg" />
          <h3>Tateti</h3>
        </a>
      </div>
      <div class="game-card">
        <a href="../Juegos/Montyhall/monty.php">
          <img src="../imgs/monty.jpg"  />
          <h3>Monty Hall</h3>
        </a>
      </div>
    </div>
  </section>
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
