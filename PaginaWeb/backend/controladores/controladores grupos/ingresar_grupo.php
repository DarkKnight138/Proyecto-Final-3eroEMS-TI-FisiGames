<?php
header('Content-Type: application/json'); // Devuelve la respuesta en formato JSON
require '../conexion.php'; // Incluye el archivo de conexión a la base de datos
session_start(); // Inicia o reanuda la sesión

$response = ["status" => "error", "message" => "Error desconocido"]; // Respuesta por defecto

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Verifica que la solicitud sea POST
    $nombre_grupo = trim($_POST['nombre_grupo'] ?? ''); // Obtiene el nombre del grupo sin espacios
    $password = trim($_POST['password'] ?? ''); // Obtiene la contraseña sin espacios

    if (!isset($_SESSION['id_cuenta'])) { // Verifica si hay un usuario logueado
        echo json_encode(["status" => "error", "message" => "No hay usuario logueado."]);
        exit;
    }

    $id_cuenta = intval($_SESSION['id_cuenta']); // Convierte el id de cuenta a número entero

    // Chequea si el usuario ya pertenece a un grupo
    $check = $conexion->prepare("SELECT id_grupo FROM pertenece_a WHERE id_cuenta = ?");
    $check->bind_param("i",$id_cuenta); // Vincula el parámetro id_cuenta
    $check->execute(); // Ejecuta la consulta
    $rc = $check->get_result(); // Obtiene el resultado
    if ($rc->num_rows > 0) { // Si ya pertenece a un grupo
        echo json_encode(["status"=>"error","message"=>"Ya pertenecés a un grupo. No podés crear otro."]);
        exit;
    }

    if (strlen($nombre_grupo) >= 3 && strlen($password) >= 4) { // Valida longitudes mínimas
        // Evita duplicados por nombre (sin importar mayúsculas/minúsculas)
        $dup = $conexion->prepare("SELECT id_grupo FROM grupos WHERE LOWER(nombre) = LOWER(?)");
        $dup->bind_param("s", $nombre_grupo); // Vincula el nombre del grupo
        $dup->execute(); // Ejecuta la consulta
        $rd = $dup->get_result(); // Obtiene el resultado
        if ($rd->num_rows > 0) { // Si ya existe un grupo con ese nombre
            echo json_encode(["status"=>"error","message"=>"Ya existe un grupo con ese nombre."]);
            exit;
        }

        // Inserta el nuevo grupo en la tabla grupos
        $stmt = $conexion->prepare("INSERT INTO grupos (`nombre`, `contraseña`, `creado_por`, `usuarios`) VALUES (?, ?, ?, ?)");
        $usuariosInicial = strval($id_cuenta); // El creador es el primer usuario
        $stmt->bind_param("ssis", $nombre_grupo, $password, $id_cuenta, $usuariosInicial);

        if ($stmt->execute()) { // Si la inserción fue exitosa
            $id_grupo = $stmt->insert_id; // Obtiene el id del nuevo grupo
            // Registra al usuario como miembro del grupo creado
            $insert = $conexion->prepare("INSERT INTO pertenece_a (id_cuenta, id_grupo) VALUES (?, ?)");
            $insert->bind_param("ii", $id_cuenta, $id_grupo);
            $insert->execute();

            $response = ["status" => "ok", "message" => "Grupo '$nombre_grupo' creado correctamente."]; // Éxito
        } else {
            $response = ["status" => "error", "message" => "Error al crear el grupo (posiblemente ya exista)."]; // Fallo al crear
        }
        $stmt->close(); // Cierra la consulta
    } else {
        $response = ["status" => "error", "message" => "El nombre debe tener al menos 3 letras y la password 4 caracteres."]; // Error de validación
    }
}

echo json_encode($response); // Devuelve la respuesta final en JSON
