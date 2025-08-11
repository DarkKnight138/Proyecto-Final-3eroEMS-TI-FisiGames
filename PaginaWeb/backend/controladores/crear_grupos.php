<?php
header('Content-Type: application/json');
require 'conexion.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$response = ["status" => "error", "message" => "Error desconocido"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_grupo = trim($_POST['nombre_grupo']);
    $contraseña = trim($_POST['contraseña']);

    if (strlen($nombre_grupo) >= 3 && strlen($contraseña) >= 4) {
        $stmt = $conexion->prepare("INSERT INTO grupos (nombre, contraseña) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre_grupo, $contraseña);
        if ($stmt->execute()) {
            $response = ["status" => "ok", "message" => "Grupo '$nombre_grupo' creado correctamente."];
        } else {
            $response = ["status" => "error", "message" => "Error al crear el grupo (posiblemente ya exista)."];
        }
        $stmt->close();
    } else {
        $response = ["status" => "error", "message" => "El nombre debe tener al menos 3 letras y la contraseña 4 caracteres."];
    }
}

//echo json_encode($response);
?>
