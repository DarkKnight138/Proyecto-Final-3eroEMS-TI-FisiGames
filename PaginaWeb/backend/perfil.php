<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); 
    exit(); 
}

require 'controladores/conexion.php'; 

$msg = ""; // variable vacia para no guardar errores

function gi($v){ return intval($v ?? 0); } //get int para que post/get no rompa el codigo(solo lee datos numericos, sino guarda 0 o null)

// --- Cambiar nombre ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_name') {
    $nuevo = trim($_POST['nombre']); // Nuevo nombre ingresado
    if (strlen($nuevo) >= 2) { // Verifica longitud mínima
        $upd = $conexion->prepare("UPDATE cuentas SET nombre = ? WHERE id_cuenta = ?"); // Prepara actualización
        $upd->bind_param("si", $nuevo, $_SESSION['id_cuenta']); // Asocia valores
        if ($upd->execute()) { // Ejecuta y verifica
            $_SESSION['nombre'] = $nuevo; // Actualiza sesión
            $msg = "Nombre actualizado."; // Mensaje ok
        } else $msg = "Error al actualizar nombre."; // Error BD
    } else $msg = "Nombre inválido."; // Nombre corto
}

// --- Crear usuario (solo admin o superadmin) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'crear_usuario') {
    if ($_SESSION['permiso'] >= 2) { // Verifica permisos
        $nombre = trim($_POST['nombre']); // Nombre ingresado
        $email = trim($_POST['email']); // Email ingresado
        $pass = $_POST['password']; // Contraseña ingresada
        if (strlen($nombre) >= 2 && strlen($email) >= 5 && strlen($pass) >= 4) { // Valida datos
            $chk = $conexion->prepare("SELECT id_cuenta FROM cuentas WHERE email = ?"); // Comprueba duplicado
            $chk->bind_param("s", $email);
            $chk->execute();
            $rc = $chk->get_result();
            if ($rc->num_rows > 0) $msg = "El email ya está registrado."; // Ya existe
            else {
                $hash = password_hash($pass, PASSWORD_BCRYPT); // Hashea contraseña
                $perm = 1; // Nivel usuario
                $stmt = $conexion->prepare("INSERT INTO cuentas (nombre,email,password,permiso,puntuacion_total,habilitado) VALUES (?,?,?,?,0,1)");
                $stmt->bind_param("sssi", $nombre, $email, $hash, $perm);
                $msg = $stmt->execute() ? "Usuario creado correctamente." : "Error al crear usuario."; // Resultado
            }
        } else $msg = "Datos inválidos al crear usuario."; // Datos malos
    } else $msg = "No tenés permisos para crear usuarios."; // Sin permiso
}

// --- Crear admin (solo superadmin) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'crear_admin') {
    if ($_SESSION['permiso'] == 3) { // Solo superadmin
        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $pass = $_POST['password'];
        if (strlen($nombre) >= 2 && strlen($email) >= 5 && strlen($pass) >= 4) { // Valida
            $chk = $conexion->prepare("SELECT id_cuenta FROM cuentas WHERE email = ?");
            $chk->bind_param("s", $email);
            $chk->execute();
            $rc = $chk->get_result();
            if ($rc->num_rows > 0) $msg = "El email ya está registrado.";
            else {
                $hash = password_hash($pass, PASSWORD_BCRYPT); // Hashea pass
                $perm = 2; // Nivel admin
                $stmt = $conexion->prepare("INSERT INTO cuentas (nombre,email,password,permiso,puntuacion_total,habilitado) VALUES (?,?,?,?,0,1)");
                $stmt->bind_param("sssi", $nombre, $email, $hash, $perm);
                $msg = $stmt->execute() ? "Admin creado correctamente." : "Error al crear admin.";
            }
        } else $msg = "Datos inválidos al crear admin.";
    } else $msg = "No tenés permisos para crear admins.";
}

// --- Cambiar estado habilitado ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_habilitado') {
    $target = gi($_POST['id']); // ID del usuario a modificar
    if ($target && $target !== $_SESSION['id_cuenta']) { // No se puede modificar a sí mismo
        $s = $conexion->prepare("SELECT permiso, habilitado FROM cuentas WHERE id_cuenta = ?");
        $s->bind_param("i", $target);
        $s->execute();
        $r = $s->get_result();
        if ($r->num_rows === 1) { // Usuario existe
            $row = $r->fetch_assoc();
            $tperm = intval($row['permiso']);
            $thab = intval($row['habilitado']);
            if ($tperm === 3) $msg = "No se puede modificar superadmin."; // Protege superadmin
            elseif ($tperm === 2 && $_SESSION['permiso'] < 3) $msg = "Solo superadmin puede tocar admins."; // Solo superadmin
            elseif ($tperm === 1 && $_SESSION['permiso'] < 2) $msg = "Sin permisos para modificar usuarios."; // Sin permisos
            else {
                $nuevo = $thab === 1 ? 0 : 1; // Alterna estado
                $u = $conexion->prepare("UPDATE cuentas SET habilitado = ? WHERE id_cuenta = ?");
                $u->bind_param("ii", $nuevo, $target);
                $msg = $u->execute() ? ($nuevo===1?"Cuenta habilitada.":"Cuenta deshabilitada.") : "Error al actualizar estado."; // Resultado
            }
        } else $msg = "Usuario no encontrado.";
    } else $msg = "ID inválido o intento de modificarse a sí mismo.";
}

// --- Cambiar permisos (solo superadmin) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_permiso') {
    if ($_SESSION['permiso'] == 3) { // Solo superadmin
        $target = gi($_POST['id']); // Usuario objetivo
        $newPerm = gi($_POST['permiso']); // Nuevo permiso
        if ($target && $newPerm && $target !== $_SESSION['id_cuenta']) { // Validación
            $stmt = $conexion->prepare("SELECT permiso FROM cuentas WHERE id_cuenta = ?"); //stmt consulta preparada
            $stmt->bind_param("i", $target);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows === 1) {
                $row = $res->fetch_assoc();
                if (intval($row['permiso']) === 3) $msg = "No se puede modificar superadmin.";
                else {
                    $upd = $conexion->prepare("UPDATE cuentas SET permiso = ? WHERE id_cuenta = ?");
                    $upd->bind_param("ii", $newPerm, $target);
                    $msg = $upd->execute() ? "Permiso actualizado." : "Error al actualizar permiso.";
                }
            } else $msg = "Usuario no encontrado.";
        } else $msg = "Datos inválidos para cambiar permiso.";
    } else $msg = "Solo superadmin puede cambiar permisos.";
}

// --- Carga datos del usuario logueado ---
$stmt = $conexion->prepare("SELECT nombre, email, puntuacion_total, permiso, habilitado FROM cuentas WHERE id_cuenta = ?");
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc(); // Guarda datos en array

// --- Carga lista de usuarios si es admin/superadmin ---
$usuarios = [];
if ($_SESSION['permiso'] >= 2) {
    $res = $conexion->query("SELECT id_cuenta, nombre, email, permiso, puntuacion_total, habilitado FROM cuentas ORDER BY id_cuenta");
    if ($res) while ($row = $res->fetch_assoc()) $usuarios[] = $row; // Agrega usuarios al array
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
  .container { display: flex; justify-content: center; align-items: flex-start; padding: 2rem; gap:2rem; flex-wrap:wrap; }
  .form-box { background-color: rgba(26,26,46,0.95); padding: 2rem 2.5rem; border-radius: 12px; box-shadow: 0 0 20px #00ffe7; max-width:900px; width: 100%; text-align: center; }
  h2 { color: #00ffe7; margin-bottom: 1.5rem; text-shadow: 0 0 8px #00ffe7; }
  p { margin: 0.5rem 0; }
  input[type="text"], input[type="email"], input[type="password"], input[type="number"] { width: 100%; padding: 8px; border-radius: 6px; border: none; margin-bottom: 1rem; background-color: #1a1a2e; color: #eee; box-shadow: inset 0 0 5px #00ffe7; }
  button { margin-top: 0.5rem; background-color: transparent; border: 2px solid #00ffe7; color: #00ffe7; padding: 8px 14px; border-radius: 20px; font-size: 0.9rem; font-weight: bold; cursor: pointer; transition: all 0.3s ease; }
  button:hover { background-color: #00ffe7; color: #1a1a2e; box-shadow: 0 0 10px #00ffe7; }
  .msg { margin: 1rem 0; padding: 0.5rem; border-radius: 6px; background: rgba(0,255,231,0.06); color: #0ff; text-align: center; }

  table { width: 100%; border-collapse: collapse; margin-top: 1rem; color: #eee; }
  th, td { padding: 10px; border-bottom: 1px solid rgba(0,255,231,0.06); text-align: left; font-size: 0.95rem; }
  th { color: #00ffe7; }
  .btn-small { padding:6px 10px; border-radius:8px; font-size:0.85rem; margin-right:6px; }

  .modal-back { position:fixed; inset:0; background: rgba(0,0,0,0.6); display:none; align-items:center; justify-content:center; z-index:2000; }
  .modal { background: rgba(20,20,40,0.98); padding:1.4rem; border-radius:10px; width:90%; max-width:480px; box-shadow:0 0 20px #00ffe7; }
  .modal h3 { margin-bottom:0.6rem; color:#00ffe7; }
  .modal .actions { display:flex; gap:10px; justify-content:flex-end; margin-top:10px; }
</style>

</head>
<body>
  <!-- Barra de navegación -->
  <nav>
    <div class="logo">FisiGames</div>
    <div class="nav-left">
      <a href="inicio.php"><i class="fas fa-home"></i> Inicio</a>
      <a href="puntuaciones.php"><i class="fas fa-trophy"></i> Puntuaciones</a>
      <a href="grupos.php"><i class="fas fa-users-line"></i> Grupos</a>
    </div>
    <div class="nav-right">
      <a href="controladores/controladoresUsuarios/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
    </div>
  </nav>

 <div class="container">
   <div class="form-box" id="perfil-box">
     <h2>Perfil</h2>
     <?php if (!empty($msg)): ?><div class="msg"><?= htmlspecialchars($msg) ?></div><?php endif; ?> <!-- Muestra mensaje -->
     <p><strong>ID:</strong> <?= intval($_SESSION['id_cuenta']) ?></p> <!-- ID usuario -->
     <p><strong>Nombre:</strong> <span id="mi-nombre"><?= htmlspecialchars($usuario['nombre']) ?></span></p> <!-- Nombre -->

     <!-- Form para cambiar nombre -->
     <div style="text-align:center; margin:10px 0;">
       <form method="POST" style="display:flex; gap:8px; justify-content:center; align-items:center;">
         <input type="hidden" name="action" value="change_name">
         <input type="text" name="nombre" placeholder="Nuevo nombre" style="max-width:260px;">
         <button type="submit"><i class="fas fa-edit"></i> Cambiar</button>
       </form>
     </div>

     <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p> <!-- Email -->
     <p><strong>Puntos:</strong> <?= intval($usuario['puntuacion_total']) ?></p> <!-- Puntaje -->
     <p><strong>Rol:</strong> <?= $usuario['permiso']==1?"Usuario":($usuario['permiso']==2?"Admin":"Superadmin") ?></p> <!-- Rol -->

     <?php if ($_SESSION['permiso'] >= 2): ?> <!-- Panel de gestión -->
     <div class="tablero">
       <h3>Gestión de Usuarios</h3>
       <div style="display:flex; gap:10px; flex-wrap:wrap; justify-content:center;">
         <button onclick="document.getElementById('modal-crear-usuario').style.display='flex'"><i class="fas fa-user-plus"></i> Crear Usuario</button>
         <?php if ($_SESSION['permiso'] == 3): ?>
           <button onclick="document.getElementById('modal-crear-admin').style.display='flex'"><i class="fas fa-user-shield"></i> Crear Admin</button>
         <?php endif; ?>
       </div>

       <!-- Tabla de usuarios -->
       <div style="overflow:auto; margin-top:12px;">
         <table>
           <thead>
             <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Puntos</th><th>Habilitado</th><th>Acciones</th></tr>
           </thead>
           <tbody>
           <?php foreach($usuarios as $u): 
              $rolTxt = $u['permiso']==1?'Usuario':($u['permiso']==2?'Admin':'Superadmin'); // Texto rol
           ?>
             <tr>
               <td><?= $u['id_cuenta'] ?></td>
               <td><?= htmlspecialchars($u['nombre']) ?></td>
               <td><?= htmlspecialchars($u['email']) ?></td>
               <td><?= $rolTxt ?></td>
               <td><?= intval($u['puntuacion_total']) ?></td>
               <td><?= $u['habilitado']==1?'Sí':'No' ?></td>
               <td>
                 <?php
                   $myPerm = $_SESSION['permiso']; // Permiso actual
                   $myId = $_SESSION['id_cuenta']; // ID actual
                   if ($u['id_cuenta'] != $myId && $u['permiso'] != 3) { // No sobre sí ni superadmin
                       // Toggle habilitado
                       if ($u['permiso'] == 1 && $myPerm >= 2) {
                           echo '<form style="display:inline" method="POST"><input type="hidden" name="action" value="toggle_habilitado"><input type="hidden" name="id" value="'.$u['id_cuenta'].'"><button type="submit" class="btn-small">'.($u['habilitado']==1?'Deshabilitar':'Habilitar').'</button></form> ';
                       } elseif ($u['permiso'] == 2 && $myPerm >= 3) {
                           echo '<form style="display:inline" method="POST"><input type="hidden" name="action" value="toggle_habilitado"><input type="hidden" name="id" value="'.$u['id_cuenta'].'"><button type="submit" class="btn-small">'.($u['habilitado']==1?'Deshabilitar':'Habilitar').'</button></form> ';
                       }

                       // Cambiar permisos (solo superadmin)
                       if ($myPerm >= 3) {
                           if ($u['permiso'] == 1) {
                             echo '<form style="display:inline" method="POST"><input type="hidden" name="action" value="change_permiso"><input type="hidden" name="id" value="'.$u['id_cuenta'].'"><input type="hidden" name="permiso" value="2"><button type="submit" class="btn-small">Asignar Admin</button></form> ';
                           } elseif ($u['permiso'] == 2) {
                             echo '<form style="display:inline" method="POST"><input type="hidden" name="action" value="change_permiso"><input type="hidden" name="id" value="'.$u['id_cuenta'].'"><input type="hidden" name="permiso" value="1"><button type="submit" class="btn-small">Quitar Admin</button></form> ';
                           }
                       }
                   } else echo '—'; // Sin acciones
                 ?>
               </td>
             </tr>
           <?php endforeach; ?>
           </tbody>
         </table>
       </div>
     </div>
     <?php endif; ?>
   </div>
 </div>

<!-- Modal crear usuario -->
<div id="modal-crear-usuario" class="modal-back" style="display:none;">
  <div class="modal">
    <h3>Crear Usuario</h3>
    <form method="POST">
      <input type="hidden" name="action" value="crear_usuario">
      <input type="text" name="nombre" placeholder="Nombre" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Contraseña" required>
      <div class="actions">
        <button type="button" onclick="document.getElementById('modal-crear-usuario').style.display='none'">Cancelar</button>
        <button type="submit">Crear</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal crear admin -->
<div id="modal-crear-admin" class="modal-back" style="display:none;">
  <div class="modal">
    <h3>Crear Admin</h3>
    <form method="POST">
      <input type="hidden" name="action" value="crear_admin">
      <input type="text" name="nombre" placeholder="Nombre" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Contraseña" required>
      <div class="actions">
        <button type="button" onclick="document.getElementById('modal-crear-admin').style.display='none'">Cancelar</button>
        <button type="submit">Crear Admin</button>
      </div>
    </form>
  </div>
</div>

<script>
// Cierra modales al hacer click fuera
document.querySelectorAll('.modal-back').forEach(mb=>{
  mb.addEventListener('click', (e)=>{
    if (e.target === mb) mb.style.display = 'none';
  });
});
</script>

</body>
</html>
