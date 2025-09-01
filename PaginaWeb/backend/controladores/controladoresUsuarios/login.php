<?php
session_start(); // mantiene la sesión activa

require '../conexion.php'; // conecta a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['contraseña'];

    // Usar consultas preparadas para mayor seguridad
    $stmt = $conexion->prepare("SELECT * FROM cuentas WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {
        $usuario = $resultado->fetch_assoc();

        // Verificar la contraseña
        if (password_verify($pass, $usuario['contraseña'])) {
            // Guardar datos en la sesión
            $_SESSION['id_cuenta'] = $usuario['id_cuenta'];  // <-- NECESARIO
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['permiso'] = $usuario['permiso'];

            echo "ok"; // respuesta al JS
            exit();
        } else {
            echo "Contraseña incorrecta";
            exit();
        }
    } else {
        echo "Usuario no encontrado";
        exit();
    }
}

// Si intentan acceder sin POST, redirige al login
header('Location: ../login.html');
exit();
?>
