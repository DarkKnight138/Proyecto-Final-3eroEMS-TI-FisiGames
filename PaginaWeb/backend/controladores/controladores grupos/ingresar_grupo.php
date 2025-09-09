<?php
header('Content-Type: application/json');
require '../conexion.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = ["status" => "error", "message" => "Error desconocido"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre_grupo = trim($_POST['nombre_grupo']);
    $password = trim($_POST['password']);

    // Usar ID de la sesión, no fijo
    if (!isset($_SESSION['id_cuenta'])) {
        echo json_encode(["status" => "error", "message" => "No hay usuario logueado."]);
        exit;
    }

    $id_cuenta = intval($_SESSION['id_cuenta']);

    // Verificar grupo
    $stmt = $conexion->prepare("SELECT id_grupo FROM grupos WHERE nombre = ? AND password = ?");
    $stmt->bind_param("ss", $nombre_grupo, $password);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $grupo = $resultado->fetch_assoc();
        $id_grupo = $grupo['id_grupo'];

        // Verificar si ya pertenece
        $check = $conexion->prepare("SELECT * FROM pertenece_a WHERE id_cuenta = ? AND id_grupo = ?");
        $check->bind_param("ii", $id_cuenta, $id_grupo);
        $check->execute();
        $res_check = $check->get_result();

        if ($res_check->num_rows == 0) {
            // Insertar relación
            $insert = $conexion->prepare("INSERT INTO pertenece_a (id_cuenta, id_grupo) VALUES (?, ?)");
            $insert->bind_param("ii", $id_cuenta, $id_grupo);
            $insert->execute();
        }

        $response = ["status" => "ok", "message" => "Te uniste al grupo '$nombre_grupo'."];
    } else {
        $response = ["status" => "error", "message" => "Grupo o password incorrectos."];
    }
    $stmt->close();
}

echo json_encode($response);
?>
