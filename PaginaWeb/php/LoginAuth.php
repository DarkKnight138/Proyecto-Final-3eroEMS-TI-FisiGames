<?php
session_start(); // recuerda al usuario mientras navegás para que cada vez que se cambie de página no se cierre sesión.

require 'conexion.php'; // se conecta a la base de datos para verificar si el usuario existe.


// si hay una sesión iniciada lo manda al dashboard sin mostrar este formulario.
if (isset($_SESSION['nombre'])) {
    header('Location: dashboard.php'); // manda a la página principal del sistema.
    exit(); // corta el resto del código para que no se ejecute nada mas.
}

// se activa solo si alguien mandó el formulario.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email']; // guarda el email que puso el usuario
    $pass = $_POST['contraseña']; // guarda la contraseña que puso el usuario

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
            header('Location: dashboard.php'); // le muestra el estado
            exit(); // Y corta todo el resto del código
        } else {
            echo "Contraseña incorrecta"; // si la contraseña está mal muestra ese mensaje
        }
    } else {
        echo "Usuario no encontrado"; // si el email no está en la base muestra eso
    }
}
?>
