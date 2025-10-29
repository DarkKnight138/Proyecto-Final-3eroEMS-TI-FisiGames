<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
require '../conexion.php';

$sql = "SELECT 
            g.id_grupo,
            g.nombre AS nombre_grupo,
            creador.nombre AS nombre_creador,
            miembro.nombre AS nombre_usuario
        FROM grupos g
        LEFT JOIN cuentas creador ON g.creado_por = creador.id_cuenta
        LEFT JOIN pertenece_a pa ON g.id_grupo = pa.id_grupo
        LEFT JOIN cuentas miembro ON pa.id_cuenta = miembro.id_cuenta
        ORDER BY g.id_grupo";

$resultado = $conexion->query($sql);
if (!$resultado) {
    echo json_encode(['error' => 'Error en la consulta: ' . $conexion->error]);
    exit;
}
$grupos = [];
while ($fila = $resultado->fetch_assoc()) {
    $id = $fila['id_grupo'];
    if (!isset($grupos[$id])) {
        $grupos[$id] = [
            'id_grupo' => $id,
            'nombre_grupo' => $fila['nombre_grupo'],
            'creador' => $fila['nombre_creador'] ?? 'Desconocido',
            'usuarios' => []
        ];
    }
    if ($fila['nombre_usuario']) {
        $grupos[$id]['usuarios'][] = $fila['nombre_usuario'];
    }
}
echo json_encode(array_values($grupos));
?>
