<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $nueva_contraseña = password_hash($_POST['nueva_contraseña'], PASSWORD_DEFAULT);

    $stmt = $conexion->prepare("UPDATE cuentas SET contraseña = ? WHERE email = ?");
    $stmt->bind_param("ss", $nueva_contraseña, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["estado" => "ok"]);
    } else {
        echo json_encode([
            "estado"  => "error", 
            "detalle" => "Email no encontrado"
        ]);
    }
    $stmt->close();
}
?>
