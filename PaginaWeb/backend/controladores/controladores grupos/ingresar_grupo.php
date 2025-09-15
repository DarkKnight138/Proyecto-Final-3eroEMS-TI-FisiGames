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
<<<<<<< Updated upstream
    $password = trim($_POST['password']);

    // Usar ID de la sesión, no fijo
=======
    $password = trim($_POST['password']); // sigue viniendo del form como "password"

    // Usar ID de la sesión
>>>>>>> Stashed changes
    if (!isset($_SESSION['id_cuenta'])) {
        echo json_encode(["status" => "error", "message" => "No hay usuario logueado."]);
        exit;
    }

    $id_cuenta = intval($_SESSION['id_cuenta']);

    // Verificar grupo
<<<<<<< Updated upstream
    $stmt = $conexion->prepare("SELECT id_grupo FROM grupos WHERE nombre = ? AND password = ?");
=======
    $stmt = $conexion->prepare("SELECT id_grupo, usuarios FROM grupos WHERE nombre = ? AND contraseña = ?");
>>>>>>> Stashed changes
    $stmt->bind_param("ss", $nombre_grupo, $password);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $grupo = $resultado->fetch_assoc();
        $id_grupo = $grupo['id_grupo'];
        $usuariosActuales = $grupo['usuarios'];

        // Verificar si ya pertenece
        $check = $conexion->prepare("SELECT * FROM pertenece_a WHERE id_cuenta = ? AND id_grupo = ?");
        $check->bind_param("ii", $id_cuenta, $id_grupo);
        $check->execute();
        $res_check = $check->get_result();

        if ($res_check->num_rows == 0) {
<<<<<<< Updated upstream
            // Insertar relación
=======
            // Insertar relación en pertenece_a
>>>>>>> Stashed changes
            $insert = $conexion->prepare("INSERT INTO pertenece_a (id_cuenta, id_grupo) VALUES (?, ?)");
            $insert->bind_param("ii", $id_cuenta, $id_grupo);
            $insert->execute();

            // Actualizar la lista de usuarios en la tabla grupos
            if ($usuariosActuales && $usuariosActuales !== "") {
                $usuariosActualizados = $usuariosActuales . "," . $id_cuenta;
            } else {
                $usuariosActualizados = strval($id_cuenta);
            }

            $update = $conexion->prepare("UPDATE grupos SET usuarios = ? WHERE id_grupo = ?");
            $update->bind_param("si", $usuariosActualizados, $id_grupo);
            $update->execute();
        }

        $response = ["status" => "ok", "message" => "Te uniste al grupo '$nombre_grupo'."];
    } else {
        $response = ["status" => "error", "message" => "Grupo o password incorrectos."];
    }
    $stmt->close();
}

echo json_encode($response);
?>
