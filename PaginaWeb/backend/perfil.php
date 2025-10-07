<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require 'controladores/conexion.php';

// Buscar datos del usuario logueado
$stmt = $conexion->prepare("SELECT nombre, email, puntuacion_total, permiso FROM cuentas WHERE id_cuenta = ?");
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// --- Mensaje de feedback ---
$msg = "";

// --- Eliminar usuario ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_usuario'])) {
    if ($_SESSION['permiso'] == 2 || $_SESSION['permiso'] == 3) {
        $id = intval($_POST['id_usuario']);
        if ($id !== $_SESSION['usuario_id']) {
            $stmt = $conexion->prepare("DELETE FROM cuentas WHERE id_cuenta = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $msg = "Usuario eliminado correctamente.";
            } else {
                $msg = "Error al eliminar usuario.";
            }
        } else {
            $msg = "No podés eliminar tu propia cuenta.";
        }
    } else {
        $msg = "No tenés permisos para eliminar usuarios.";
    }
}

// --- Crear nuevo admin ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_admin'])) {
    if ($_SESSION['permiso'] == 3) {
        $nombre = $_POST['nombre_admin'];
        $email = $_POST['email_admin'];
        $pass = password_hash($_POST['password_admin'], PASSWORD_BCRYPT);

        $stmt = $conexion->prepare("INSERT INTO cuentas (nombre, email, password, permiso, puntuacion_total) VALUES (?, ?, ?, 2, 0)");
        $stmt->bind_param("sss", $nombre, $email, $pass);
        if ($stmt->execute()) {
            $msg = "Admin creado correctamente.";
        } else {
            $msg = "Error al crear admin: " . $conexion->error;
        }
    } else {
        $msg = "No tenés permisos para crear admins.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1" />
 <title>Perfil - FisiGames</title>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
 <style>
   @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap');
   * { box-sizing: border-box; margin: 0; padding: 0; }
   body { font-family: 'Orbitron', sans-serif; background: linear-gradient(135deg,#0f0c29,#302b63,#24243e); color: #eee; min-height: 100vh; padding-top: 70px; }
   nav { width: 100%; height: 60px; background-color: #1a1a2e; box-shadow: 0 0 15px #00ffe7; display: flex; align-items: center; padding: 0 2rem; position: fixed; top: 0; left: 0; z-index: 1000; gap: 2rem; }
   .logo { font-size: 1.8rem; font-weight: 700; color: #00ffe7; letter-spacing: 2px; }
   .nav-left { display: flex; gap: 1.5rem; flex-grow: 1; }
   .nav-right { display: flex; gap: 1rem; align-items: center; }
   .nav-left a, .nav-right a, .logout-btn { text-decoration: none; color: #eee; font-weight: 600; font-size: 1rem; padding: 8px 12px; border-radius: 6px; transition: 0.3s; display: inline-flex; align-items: center; gap: 6px; }
   .nav-left a:hover, .nav-right a:hover, .logout-btn:hover { background-color: #00ffe7; color: #1a1a2e; box-shadow: 0 0 8px #00ffe7; }
   .logout-btn { border: 2px solid #00ffe7; border-radius: 20px; cursor: pointer; }
   .container { display: flex; justify-content: center; align-items: flex-start; padding: 2rem; }
   .form-box { background-color: rgba(26,26,46,0.95); padding: 2rem 2.5rem; border-radius: 12px; box-shadow: 0 0 20px #00ffe7; max-width: 700px; width: 100%; text-align: center; transition: transform 0.3s ease; }
   .form-box:hover { transform: scale(1.02); }
   h2 { color: #00ffe7; margin-bottom: 1.5rem; text-shadow: 0 0 8px #00ffe7; }
   p { margin: 0.5rem 0; }
   .tablero { margin-top: 2rem; padding: 1rem; background: rgba(20,20,40,0.9); border-radius: 8px; text-align: left; box-shadow: 0 0 10px #00ffe7; }
   .tablero h3 { margin-bottom: 1rem; color: #00ffe7; }
   input[type="text"], input[type="email"], input[type="password"], input[type="number"] { width: 100%; padding: 8px; border-radius: 6px; border: none; margin-bottom: 1rem; background-color: #1a1a2e; color: #eee; box-shadow: inset 0 0 5px #00ffe7; }
   button { margin-top: 0.5rem; background-color: transparent; border: 2px solid #00ffe7; color: #00ffe7; padding: 8px 14px; border-radius: 20px; font-size: 0.9rem; font-weight: bold; cursor: pointer; transition: all 0.3s ease; }
   button:hover { background-color: #00ffe7; color: #1a1a2e; box-shadow: 0 0 10px #00ffe7; }
   .msg { margin: 1rem 0; padding: 0.5rem; border-radius: 6px; background: rgba(0,255,231,0.1); color: #0ff; text-align: center; }
 </style>
</head>
<body>
  <nav>
    <div class="logo">FisiGames</div>
    <div class="nav-left">
      <a href="inicio.php"><i class="fas fa-home"></i> Inicio</a>
      <a href="puntuaciones.php"><i class="fas fa-trophy"></i>Puntuaciones</a>
      <a href="grupos.php"><i class="fas fa-users"></i> Grupos</a>
    </div>
    <div class="nav-right">
      <a href="perfil.php"><i class="fas fa-users"></i> Perfil</a>
    </div>
  </nav>
  

 <div class="container">
   <div class="form-box" id="perfil-box">
     <h2>Perfil</h2>
     <?php if (!empty($msg)) echo "<div class='msg'>$msg</div>"; ?>
     <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre']) ?></p>
     <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
     <p><strong>Puntos:</strong> <?= intval($usuario['puntuacion_total']) ?></p>
     <p><strong>Rol:</strong> <?= $usuario['permiso']==1?"Usuario":($usuario['permiso']==2?"Admin":"Superadmin") ?></p>

     <?php if ($_SESSION['permiso'] == 2 || $_SESSION['permiso'] == 3): ?>
     <div class="tablero">
       <h3>Gestión de Usuarios</h3>
       <form method="POST">
         <input type="number" name="id_usuario" placeholder="ID de usuario a eliminar" required>
         <button type="submit" name="eliminar_usuario"><i class="fas fa-user-minus"></i> Eliminar Usuario</button>
       </form>
     </div>
     <?php endif; ?>

     <?php if ($_SESSION['permiso'] == 3): ?>
     <div class="tablero">
       <h3>Gestión de Administradores</h3>
       <form method="POST">
         <input type="text" name="nombre_admin" placeholder="Nombre del admin" required>
         <input type="email" name="email_admin" placeholder="Email del admin" required>
         <input type="password" name="password_admin" placeholder="Contraseña" required>
         <button type="submit" name="crear_admin"><i class="fas fa-user-plus"></i> Crear Admin</button>
       </form>
     </div>
     <?php endif; ?>
   </div>
 </div>
</body>
</html>
