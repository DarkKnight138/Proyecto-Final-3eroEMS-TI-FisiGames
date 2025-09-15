<?php
header('Content-Type: application/json');
require '../conexion.php';
session_start(); // para obtener el usuario logueado

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = ["status" => "error", "message" => "Error desconocido"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_grupo = trim($_POST['nombre_grupo']);
    $password = trim($_POST['password']); // sigue viniendo como "password" del form

    // Verificar sesión activa
    if (!isset($_SESSION['id_cuenta'])) {
        echo json_encode(["status" => "error", "message" => "No hay usuario logueado."]);
        exit;
    }

    $id_cuenta = intval($_SESSION['id_cuenta']);

    if (strlen($nombre_grupo) >= 3 && strlen($password) >= 4) {
        $stmt = $conexion->prepare("INSERT INTO grupos (nombre, contraseña, creado_por, usuarios) VALUES (?, ?, ?, ?)");
        $usuariosInicial = strval($id_cuenta); // el creador arranca como primer usuario
        $stmt->bind_param("ssis", $nombre_grupo, $password, $id_cuenta, $usuariosInicial);

        if ($stmt->execute()) {
            $id_grupo = $stmt->insert_id;

            // insertar al creador como miembro también
            $insert = $conexion->prepare("INSERT INTO pertenece_a (id_cuenta, id_grupo) VALUES (?, ?)");
            $insert->bind_param("ii", $id_cuenta, $id_grupo);
            $insert->execute();

            $response = ["status" => "ok", "message" => "Grupo '$nombre_grupo' creado correctamente."];
        } else {
            $response = ["status" => "error", "message" => "Error al crear el grupo (posiblemente ya exista)."];
        }
        $stmt->close();
    } else {
        $response = ["status" => "error", "message" => "El nombre debe tener al menos 3 letras y la password 4 caracteres."];
    }
}

echo json_encode($response);
?>
