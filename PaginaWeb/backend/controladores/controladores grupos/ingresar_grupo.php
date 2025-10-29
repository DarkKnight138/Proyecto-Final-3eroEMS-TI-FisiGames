<?php
header('Content-Type: application/json');
require '../conexion.php';
session_start();

$response = ["status" => "error", "message" => "Error desconocido"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_grupo = isset($_POST['id_grupo']) ? intval($_POST['id_grupo']) : 0;
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
    $rcheck = $check->get_result();
    if ($rcheck->num_rows > 0) {
        echo json_encode(["status"=>"error","message"=>"Ya pertenecés a un grupo. No podés unirte a otro."]);
        exit;
    }

    // obtener grupo por id o por nombre+pass
    if ($id_grupo > 0) {
        $stmt = $conexion->prepare("SELECT id_grupo, usuarios, `contraseña`, nombre FROM grupos WHERE id_grupo = ?");
        $stmt->bind_param("i",$id_grupo);
    } else {
        $stmt = $conexion->prepare("SELECT id_grupo, usuarios, `contraseña`, nombre FROM grupos WHERE LOWER(nombre) = LOWER(?)");
        $stmt->bind_param("s",$nombre_grupo);
    }
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $grupo = $resultado->fetch_assoc();
        $id_gr = $grupo['id_grupo'];
        $usuariosActuales = $grupo['usuarios'];
        $pwGrupo = $grupo['contraseña'];

        // si la contraseña no coincide (si se pasó password)
        if ($password !== $pwGrupo) {
            echo json_encode(["status"=>"error","message"=>"Password incorrecta."]);
            exit;
        }

        // verificar si ya pertenece (redundante por pertenece_a, pero de todos modos)
        $check2 = $conexion->prepare("SELECT * FROM pertenece_a WHERE id_cuenta = ? AND id_grupo = ?");
        $check2->bind_param("ii", $id_cuenta, $id_gr);
        $check2->execute();
        $res_check = $check2->get_result();
        if ($res_check->num_rows == 0) {
            $insert = $conexion->prepare("INSERT INTO pertenece_a (id_cuenta, id_grupo) VALUES (?, ?)");
            $insert->bind_param("ii", $id_cuenta, $id_gr);
            $insert->execute();

            // Actualizar la lista de usuarios en la tabla grupos
            if ($usuariosActuales && $usuariosActuales !== "") {
                $usuariosActualizados = $usuariosActuales . "," . $id_cuenta;
            } else {
                $usuariosActualizados = strval($id_cuenta);
            }

            $update = $conexion->prepare("UPDATE grupos SET usuarios = ? WHERE id_grupo = ?");
            $update->bind_param("si", $usuariosActualizados, $id_gr);
            $update->execute();
        }

        $response = ["status" => "ok", "message" => "Te uniste al grupo '".$grupo['nombre']."'."];
    } else {
        $response = ["status" => "error", "message" => "Grupo o password incorrectos."];
    }
    $stmt->close();
}

echo json_encode($response);
?>
