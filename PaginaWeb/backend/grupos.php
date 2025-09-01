<!DOCTYPE html>
<html lang="es">
<?php
session_start();
?>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Grupos - FisiGames</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap');

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Orbitron', sans-serif;
      background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
      color: #eee;
      min-height: 100vh;
      padding-top: 70px;
    }

    nav {
      width: 100%;
      height: 60px;
      background-color: #1a1a2e;
      box-shadow: 0 0 15px #00ffe7;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 2rem;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 1000;
    }

    .logo {
      font-size: 1.8rem;
      font-weight: 700;
      color: #00ffe7;
      letter-spacing: 2px;
    }

    .nav-links a {
      text-decoration: none;
      color: #eee;
      font-weight: 600;
      font-size: 1rem;
      padding: 8px 12px;
      border-radius: 6px;
      transition: background-color 0.3s ease, color 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .nav-links a:hover,
    .nav-links a.active {
      background-color: #00ffe7;
      color: #1a1a2e;
      box-shadow: 0 0 8px #00ffe7;
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      gap: 2rem;
      padding: 2rem;
      flex-wrap: wrap;
    }

    .form-box {
      background-color: rgba(26, 26, 46, 0.95);
      padding: 2rem 2.5rem;
      border-radius: 12px;
      box-shadow: 0 0 20px #00ffe7;
      max-width: 400px;
      width: 100%;
      text-align: center;
      transition: transform 0.3s ease;
    }

    .form-box:hover {
      transform: scale(1.02);
    }

    h2 {
      color: #00ffe7;
      margin-bottom: 1.5rem;
      text-shadow: 0 0 8px #00ffe7;
    }

    .input-group {
      margin-bottom: 1.2rem;
      text-align: left;
    }

    label {
      display: block;
      margin-bottom: 0.3rem;
      color: #ccc;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 6px;
      outline: none;
      font-size: 1rem;
      background-color: #1a1a2e;
      color: #eee;
      box-shadow: inset 0 0 5px #00ffe7;
    }

    button {
      margin-top: 1rem;
      background-color: transparent;
      border: 2px solid #00ffe7;
      color: #00ffe7;
      padding: 10px 18px;
      border-radius: 20px;
      font-size: 1rem;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    button:hover {
      background-color: #00ffe7;
      color: #1a1a2e;
      box-shadow: 0 0 10px #00ffe7;
    }

    #grupos-container div {
      background: rgba(20, 20, 40, 0.9);
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1rem;
      box-shadow: 0 0 10px #00ffe7;
    }

    #grupos-container h3 {
      margin-bottom: 0.5rem;
      color: #00ffe7;
    }

    hr {
      border: none;
      border-top: 1px solid #00ffe7;
      margin-top: 0.5rem;
    }
  </style>
</head>

<body>

  <nav>
    <div class="logo">FisiGames</div>
    <div class="nav-links">
      <a href="inicio.html"><i class="fas fa-home"></i> Inicio</a>
      <a href="grupos.php" class="active"><i class="fas fa-users"></i> Grupos</a>
      <a href="perfil.html"><i class="fas fa-user"></i> Perfil</a>
    </div>
  </nav>

  <div class="container">
    <!-- Crear grupo -->
    <div class="form-box">
      <h2>Crear Grupo</h2>
      <form id="crear-grupo-form">
        <div class="input-group">
          <label for="nuevo-grupo">Nombre del Grupo</label>
          <input type="text" id="nuevo-grupo" name="nombre_grupo" required />
        </div>
        <div class="input-group">
          <label for="clave-grupo">Contraseña</label>
          <input type="password" id="clave-grupo" name="contraseña" required />
        </div>
        <button type="submit"><i class="fas fa-plus-circle"></i> Crear</button>
      </form>
    </div>

    <!-- Ingresar a grupo -->
    <div class="form-box">
      <h2>Ingresar a un Grupo</h2>
      <form id="ingresar-grupo-form">
        <div class="input-group">
          <label for="grupo">Nombre del Grupo</label>
          <input type="text" id="grupo" required />
        </div>
        <div class="input-group">
          <label for="contraseña">Contraseña</label>
          <input type="password" id="contraseña" required />
        </div>
        <button type="submit"><i class="fas fa-door-open"></i> Ingresar</button>
      </form>
    </div>
  </div>

  <!-- Mostrar grupos existentes -->
  <div class="container">
    <div class="form-box" style="width: 100%;">
      <h2>Grupos existentes</h2>
      <div id="grupos-container">Cargando...</div>
    </div>
  </div>

 <script>
    // Obtener ID del usuario desde PHP (sesión)
    const idUsuario = <?php echo json_encode($_SESSION['id_cuenta'] ?? null); ?>;

    // Crear grupo
    document.getElementById('crear-grupo-form').addEventListener('submit', function (e) {
      e.preventDefault();
      const nuevoGrupo = document.getElementById('nuevo-grupo').value.trim();
      const claveGrupo = document.getElementById('clave-grupo').value;

      if (nuevoGrupo.length >= 3 && claveGrupo.length >= 4) {
        fetch("controladores/controladores grupos/crear_grupos.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `nombre_grupo=${encodeURIComponent(nuevoGrupo)}&contraseña=${encodeURIComponent(claveGrupo)}`
        })
        .then(async res => {
          const text = await res.text();
          try {
            return JSON.parse(text);
          } catch (e) {
            console.error("Respuesta no es JSON válida:", text);
            throw new Error("Respuesta no válida del servidor.");
          }
        })
        .then(data => {
          alert(data.message);
          if (data.status === "ok") {
            document.getElementById('crear-grupo-form').reset();
            cargarGrupos();
          }
        })
        .catch(err => {
          alert("Error al conectar con el servidor.");
          console.error(err);
        });
      } else {
        alert("Por favor, completá los campos correctamente.");
      }
    });

    // Ingresar a grupo
    document.getElementById('ingresar-grupo-form').addEventListener('submit', function (e) {
      e.preventDefault();
      const grupo = document.getElementById('grupo').value.trim();
      const contraseña = document.getElementById('contraseña').value;

      fetch("controladores/controladores grupos/ingresar_grupo.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `nombre_grupo=${encodeURIComponent(grupo)}&contraseña=${encodeURIComponent(contraseña)}&id_cuenta=${idUsuario}`
      })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        if (data.status === "ok") {
          document.getElementById('ingresar-grupo-form').reset();
          cargarGrupos();
        }
      })
      .catch(() => alert("Error al conectar con el servidor."));
    });

    // Mostrar grupos
    function cargarGrupos() {
      fetch("controladores/controladores grupos/mostrar_grupos.php")
      .then(response => response.json())
      .then(data => {
        const container = document.getElementById("grupos-container");
        container.innerHTML = "";
        data.forEach(grupos => {
          const grupoBox = document.createElement("div");
          grupoBox.innerHTML = `
            <h3>${grupos.nombre_grupo}</h3>
            <p><strong>Creador:</strong> ${grupos.creador}</p>
            <p><strong>Miembros:</strong> ${grupos.usuarios.length ? grupos.usuarios.join(", ") : "Sin miembros"}</p>
            <hr>
          `;
          container.appendChild(grupoBox);
        });
      })
      .catch(error => {
        document.getElementById("grupos-container").innerText = "Error al cargar los grupos.";
        console.error(error);
      });
    }

    // Cargar grupos al inicio
    cargarGrupos();
</script>


</body>

</html>