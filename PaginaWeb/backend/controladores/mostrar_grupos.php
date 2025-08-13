<?php
// Mostrar todos los errores (útil para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Encabezado JSON antes de cualquier salida
header('Content-Type: application/json');

// Conexión a la base de datos
require 'conexion.php';

// Consulta SQL para obtener los grupos y sus miembros
$sql = "SELECT 
            grupos.id_grupo,
            grupos.nombre AS nombre_grupo,
            cuentas.nombre AS nombre_usuario
        FROM pertenece_a
        JOIN grupos ON pertenece_a.id_grupo = grupos.id_grupo
        JOIN cuentas ON pertenece_a.id_cuenta = cuentas.id_cuenta
        ORDER BY grupos.id_grupo";

// Ejecutar la consulta
$resultado = $conexion->query($sql);

// Verificar si hubo error en la consulta
if (!$resultado) {
    echo json_encode(['error' => 'Error en la consulta: ' . $conexion->error]);
    exit;
}

// Procesar los resultados
$grupos = [];

while ($fila = $resultado->fetch_assoc()) {
    $id = $fila['id_grupo'];
    if (!isset($grupos[$id])) {
        $grupos[$id] = [
            'nombre_grupo' => $fila['nombre_grupo'],
            'usuarios' => []
        ];
    }
    $grupos[$id]['usuarios'][] = $fila['nombre_usuario'];
}

// Enviar los datos como JSON
echo json_encode(array_values($grupos));
?>
