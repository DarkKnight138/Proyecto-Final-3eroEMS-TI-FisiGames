<?php
session_start();
require '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $pass  = $_POST['password'] ?? '';

    $stmt = $conexion->prepare("SELECT id_cuenta, nombre, permiso, password, habilitado FROM cuentas WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Si la cuenta está deshabilitada
        if (isset($usuario['habilitado']) && intval($usuario['habilitado']) === 0) {
            echo "Cuenta inhabilitada";
            exit();
        }

        if (password_verify($pass, $usuario['password'])) {
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $usuario['id_cuenta'];
            $_SESSION['id_cuenta']  = $usuario['id_cuenta'];
            $_SESSION['nombre']     = $usuario['nombre'];
            $_SESSION['permiso']    = $usuario['permiso'];
            echo "ok";
            exit();
        } else {
            echo "contraseña incorrecta";
            exit();
        }
    } else {
        echo "Usuario no encontrado";
        exit();
    }
}

header('Location: ../login.php');
exit();
?>
