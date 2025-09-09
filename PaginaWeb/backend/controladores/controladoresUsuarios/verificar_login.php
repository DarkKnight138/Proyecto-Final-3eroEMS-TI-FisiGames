<?php
session_start(); // mantiene la sesión activa
require '../conexion.php'; // conecta a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $pass  = $_POST['password'] ?? '';

    // Usar consultas preparadas
    $stmt = $conexion->prepare("SELECT id_cuenta, nombre, permiso, password FROM cuentas WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Verificar el hash de la contraseña
        if (password_verify($pass, $usuario['password'])) {
            // Guardar datos en la sesión
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $usuario['id_cuenta'];
            $_SESSION['id_cuenta'] = $usuario['id_cuenta'];
            $_SESSION['nombre']    = $usuario['nombre'];
            $_SESSION['permiso']   = $usuario['permiso'];

            echo "ok"; // respuesta al JS
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

// Si entran por GET u otro método, redirige al login
header('Location: ../login.php');
exit();
?>
