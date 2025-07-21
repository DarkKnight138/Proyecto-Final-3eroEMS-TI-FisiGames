<?php
require 'conexion.php'; // conecta con la base de datos

// cerifica si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre']; // toma el valor del campo "nombre"
    $email = $_POST['email']; // toma el valor del campo "email"
    
    // toma la contraseña y la encripta para guardarla segura
    $pass = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);

    $permiso = 1; // por defecto le asigna permiso 1

    // prepara una consulta SQL con (?) para evitar inyecciones SQL
    $sql = "INSERT INTO cuentas (nombre, email, contraseña, permiso) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql); // prepara la consulta

    // asocia los valores a los marcadores
    // s = string, i = integer. 3 strings y 1 número.
    $stmt->bind_param("sssi", $nombre, $email, $pass, $permiso);

    // ejecuta la consulta
    if ($stmt->execute()) {
        echo "estado:ok"; // muestra mensaje si se registró con éxito
    } else {
        echo "estado:error"; // muestra mensaje si hubo error al registrar
    }

    $stmt->close(); // cierra la consulta preparada
}
?>
