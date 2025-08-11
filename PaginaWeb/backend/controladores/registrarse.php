<?php
require 'conexion.php'; //conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] === "POST") { // Verifica si la solicitud es de tipo POST

    if (!isset($_POST['nombre'], $_POST['email'], $_POST['contraseña'])) { // Verifica si todos los datos fueron enviados
        echo "error"; // Si falta alguno, muestra "error"
        exit; // Termina la ejecución del script
    }

    $nombre = trim($_POST['nombre']); // Obtiene y limpia el campo "nombre"
    $email = trim($_POST['email']); // Obtiene y limpia el campo "email"
    $contraseña = password_hash(trim($_POST['contraseña']), PASSWORD_DEFAULT); // Limpia y encripta la contraseña con hash
    $permiso = 1; // Asigna el permiso por defecto

    $sql_check = "SELECT id_cuenta FROM cuentas WHERE email = ?"; // Consulta si el email está registrado
    $stmt_check = $conexion->prepare($sql_check); // Prepara la consulta
    $stmt_check->bind_param("s", $email); // Enlaza el email a la consulta
    $stmt_check->execute(); // Ejecuta la consulta
    $result = $stmt_check->get_result(); // Obtiene el resultado de la consulta

    if ($result->num_rows > 0) { // Si ya existe una cuenta con ese email
        echo "email_ya_registrado"; // Devuelve que el email está en uso
    } else {
        $sql = "INSERT INTO cuentas (nombre, email, contraseña, permiso) VALUES (?, ?, ?, ?)"; // Consulta para insertar una nueva cuenta
        $stmt = $conexion->prepare($sql); // Prepara la consulta
        $stmt->bind_param("sssi", $nombre, $email, $contraseña, $permiso); // Enlaza los valores a insertar

        if ($stmt->execute()) { // Si la inserción fue exitosa
            echo "ok"; // Devuelve "ok" al frontend
        } else {
            echo "error: " . $stmt->error; // Devuelve mensaje de error con detalle
        }

        $stmt->close(); // Cierra la consulta
    }

    $stmt_check->close(); // Cierra la consulta de verificación
    $conexion->close(); // Cierra la conexión a la base de datos
}
?>
