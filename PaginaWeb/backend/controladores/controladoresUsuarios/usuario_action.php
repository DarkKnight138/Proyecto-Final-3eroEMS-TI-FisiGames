<?php
// controladores/controladoresUsuarios/usuarios_action.php
header('Content-Type: application/json; charset=utf-8');
require '../conexion.php';
session_start();

if (!isset($_SESSION['id_cuenta'])) {
    echo json_encode(['status'=>'error','message'=>'No autorizado']);
    exit;
}

$miPermiso = intval($_SESSION['permiso']);
$miId = intval($_SESSION['id_cuenta']);

function resp($arr){ echo json_encode($arr); exit; }

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'list') {
    if ($miPermiso < 2) resp(['status'=>'error','message'=>'Sin permisos']);
    $res = $conexion->query("SELECT id_cuenta, nombre, email, permiso, puntuacion_total, habilitado FROM cuentas ORDER BY id_cuenta");
    $out = [];
    while ($r = $res->fetch_assoc()) $out[] = $r;
    resp(['status'=>'ok','usuarios'=>$out]);
}

if ($action === 'create_user') {
    if ($miPermiso < 2) resp(['status'=>'error','message'=>'Sin permisos']);
    $nombre = trim($_POST['nombre'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $pass   = $_POST['password'] ?? '';
    if (strlen($nombre) < 2 || strlen($email) < 5 || strlen($pass) < 4) resp(['status'=>'error','message'=>'Datos inválidos']);

    // evitar email duplicado
    $chk = $conexion->prepare("SELECT id_cuenta FROM cuentas WHERE email = ?");
    $chk->bind_param("s",$email);
    $chk->execute();
    if ($chk->get_result()->num_rows > 0) resp(['status'=>'error','message'=>'Email ya registrado']);

    $hash = password_hash($pass, PASSWORD_BCRYPT);
    $perm = 1;
    $stmt = $conexion->prepare("INSERT INTO cuentas (nombre,email,password,permiso,puntuacion_total,habilitado) VALUES (?,?,?,?,0,1)");
    $stmt->bind_param("sssi",$nombre,$email,$hash,$perm);
    if ($stmt->execute()) resp(['status'=>'ok','message'=>'Usuario creado']);
    else resp(['status'=>'error','message'=>'Error al crear usuario: '.$conexion->error]);
}

if ($action === 'create_admin') {
    if ($miPermiso < 3) resp(['status'=>'error','message'=>'Solo superadmin']);
    $nombre = trim($_POST['nombre'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $pass   = $_POST['password'] ?? '';
    if (strlen($nombre) < 2 || strlen($email) < 5 || strlen($pass) < 4) resp(['status'=>'error','message'=>'Datos inválidos']);

    $chk = $conexion->prepare("SELECT id_cuenta FROM cuentas WHERE email = ?");
    $chk->bind_param("s",$email);
    $chk->execute();
    if ($chk->get_result()->num_rows > 0) resp(['status'=>'error','message'=>'Email ya registrado']);

    $hash = password_hash($pass, PASSWORD_BCRYPT);
    $perm = 2;
    $stmt = $conexion->prepare("INSERT INTO cuentas (nombre,email,password,permiso,puntuacion_total,habilitado) VALUES (?,?,?,?,0,1)");
    $stmt->bind_param("sssi",$nombre,$email,$hash,$perm);
    if ($stmt->execute()) resp(['status'=>'ok','message'=>'Admin creado']);
    else resp(['status'=>'error','message'=>'Error al crear admin: '.$conexion->error]);
}

if ($action === 'toggle_habilitado') {
    $targetId = intval($_POST['id'] ?? 0);
    if (!$targetId) resp(['status'=>'error','message'=>'ID inválido']);
    if ($targetId === $miId) resp(['status'=>'error','message'=>'No podés cambiar el estado de tu propia cuenta']);

    $stmt = $conexion->prepare("SELECT permiso, habilitado FROM cuentas WHERE id_cuenta = ?");
    $stmt->bind_param("i",$targetId);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) resp(['status'=>'error','message'=>'Usuario no encontrado']);
    $t = $res->fetch_assoc();
    $tperm = intval($t['permiso']);
    $thab = intval($t['habilitado']);

    if ($tperm === 3) resp(['status'=>'error','message'=>'No se puede modificar superadmin']);
    if ($tperm === 2 && $miPermiso < 3) resp(['status'=>'error','message'=>'Solo superadmin puede habilitar/deshabilitar administradores']);
    if ($tperm === 1 && $miPermiso < 2) resp(['status'=>'error','message'=>'Sin permisos para modificar usuarios']);

    $nuevo = $thab===1?0:1;
    $upd = $conexion->prepare("UPDATE cuentas SET habilitado = ? WHERE id_cuenta = ?");
    $upd->bind_param("ii",$nuevo,$targetId);
    if ($upd->execute()) resp(['status'=>'ok','message'=>'Estado cambiado','habilitado'=>$nuevo]);
    else resp(['status'=>'error','message'=>'Error al actualizar']);
}

if ($action === 'change_permiso') {
    if ($miPermiso < 3) resp(['status'=>'error','message'=>'Solo superadmin']);
    $targetId = intval($_POST['id'] ?? 0);
    $newPerm = intval($_POST['permiso'] ?? -1);
    if (!$targetId || ($newPerm !== 1 && $newPerm !== 2)) resp(['status'=>'error','message'=>'Datos inválidos']);
    if ($targetId === $miId) resp(['status'=>'error','message'=>'No podés cambiar tu propio rol']);

    $stmt = $conexion->prepare("SELECT permiso FROM cuentas WHERE id_cuenta = ?");
    $stmt->bind_param("i",$targetId);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) resp(['status'=>'error','message'=>'Usuario no encontrado']);
    $row = $res->fetch_assoc();
    if (intval($row['permiso']) === 3) resp(['status'=>'error','message'=>'No se puede modificar superadmin']);

    $upd = $conexion->prepare("UPDATE cuentas SET permiso = ? WHERE id_cuenta = ?");
    $upd->bind_param("ii",$newPerm,$targetId);
    if ($upd->execute()) resp(['status'=>'ok','message'=>'Permiso actualizado']);
    else resp(['status'=>'error','message'=>'Error al actualizar permiso']);
}

if ($action === 'delete') {
    if ($miPermiso < 2) resp(['status'=>'error','message'=>'Sin permisos']);
    $targetId = intval($_POST['id'] ?? 0);
    if (!$targetId) resp(['status'=>'error','message'=>'ID inválido']);
    if ($targetId === $miId) resp(['status'=>'error','message'=>'No podés eliminar tu propia cuenta']);

    $stmt = $conexion->prepare("SELECT permiso FROM cuentas WHERE id_cuenta = ?");
    $stmt->bind_param("i",$targetId);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) resp(['status'=>'error','message'=>'Usuario no encontrado']);
    $row = $res->fetch_assoc();
    $tperm = intval($row['permiso']);
    if ($tperm === 3) resp(['status'=>'error','message'=>'No se puede eliminar superadmin']);
    if ($tperm === 2 && $miPermiso < 3) resp(['status'=>'error','message'=>'Solo superadmin puede eliminar admins']);

    $del = $conexion->prepare("DELETE FROM cuentas WHERE id_cuenta = ?");
    $del->bind_param("i",$targetId);
    if ($del->execute()) resp(['status'=>'ok','message'=>'Usuario eliminado']);
    else resp(['status'=>'error','message'=>'Error al eliminar']);
}

if ($action === 'change_name') {
    $nuevo = trim($_POST['nombre'] ?? '');
    if (strlen($nuevo) < 2) resp(['status'=>'error','message'=>'Nombre inválido']);
    $upd = $conexion->prepare("UPDATE cuentas SET nombre = ? WHERE id_cuenta = ?");
    $upd->bind_param("si",$nuevo,$miId);
    if ($upd->execute()) {
        $_SESSION['nombre'] = $nuevo;
        resp(['status'=>'ok','message'=>'Nombre actualizado']);
    } else resp(['status'=>'error','message'=>'Error al actualizar']);
}

resp(['status'=>'error','message'=>'Acción no válida']);
