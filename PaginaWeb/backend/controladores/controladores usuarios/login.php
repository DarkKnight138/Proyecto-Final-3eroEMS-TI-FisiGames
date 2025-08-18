<?php
session_start(); // recuerda al usuario mientras navegá para que cada vez que se cambie de página no se cierre sesión.

require 'conexion.php'; // conecta a la base de datos.

// se activa solo si alguien mandó el formulario.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email']; // guarda el email 
    $pass = $_POST['contraseña']; // guarda la contraseña 

    // corrobora los datos en la BD
    $sql = "SELECT * FROM cuentas WHERE email = '$email'";
    $query = mysqli_query($conexion, $sql); // hace la búsqueda

    // si encontró 1 usuario con ese email
    if (mysqli_num_rows($query) == 1) {
        $usuario = mysqli_fetch_assoc($query); // toma los datos de ese usuario

        // compara la contraseña ingresada con la que está guardada
        if (password_verify($pass, $usuario['contraseña'])) {
            // si la contraseña es correcta guarda los datos del usuario en la sesión

            $_SESSION['nombre'] = $usuario['nombre']; // guarda el nombre
            $_SESSION['permiso'] = $usuario['permiso']; // guarda el permiso
            echo "ok"; // responde "ok" al JS
            exit(); // corta el resto del código
        } else {
            echo "Contraseña incorrecta"; // si la contraseña está mal
            exit();
        }
    } else {
        echo "Usuario no encontrado"; // si el email no está en la bd
        exit();
    }
}

// si alguien intenta acceder por GET, se redirige
header('Location: ../login.html');
exit();
?>
