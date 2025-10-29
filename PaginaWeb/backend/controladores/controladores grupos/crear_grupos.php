<?php
header('Content-Type: application/json');
require '../conexion.php';
session_start();

$response = ["status" => "error", "message" => "Error desconocido"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_grupo = trim($_POST['nombre_grupo'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!isset($_SESSION['id_cuenta'])) {
        echo json_encode(["status" => "error", "message" => "No hay usuario logueado."]);
        exit;
    }

    $id_cuenta = intval($_SESSION['id_cuenta']);

    // chequear si ya pertenece a un grupo
    $check = $conexion->prepare("SELECT id_grupo FROM pertenece_a WHERE id_cuenta = ?");
    $check->bind_param("i",$id_cuenta);
    $check->execute();
    $rc = $check->get_result();
    if ($rc->num_rows > 0) {
        echo json_encode(["status"=>"error","message"=>"Ya pertenecés a un grupo. No podés crear otro."]);
        exit;
    }

    if (strlen($nombre_grupo) >= 3 && strlen($password) >= 4) {
        // Evitar duplicados por nombre (case-insensitive)
        $dup = $conexion->prepare("SELECT id_grupo FROM grupos WHERE LOWER(nombre) = LOWER(?)");
        $dup->bind_param("s", $nombre_grupo);
        $dup->execute();
        $rd = $dup->get_result();
        if ($rd->num_rows > 0) {
            echo json_encode(["status"=>"error","message"=>"Ya existe un grupo con ese nombre."]);
            exit;
        }

        $stmt = $conexion->prepare("INSERT INTO grupos (`nombre`, `contraseña`, `creado_por`, `usuarios`) VALUES (?, ?, ?, ?)");
        $usuariosInicial = strval($id_cuenta); // el creador arranca como primer usuario
        $stmt->bind_param("ssis", $nombre_grupo, $password, $id_cuenta, $usuariosInicial);

        if ($stmt->execute()) {
            $id_grupo = $stmt->insert_id;
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
