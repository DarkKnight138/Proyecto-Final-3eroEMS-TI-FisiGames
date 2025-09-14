<?php
// Mostrar todos los errores (útil para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Encabezado JSON antes de cualquier salida
header('Content-Type: application/json');

// Conexión a la base de datos
require '../conexion.php';

// Consulta SQL para obtener los grupos, su creador y sus miembros
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
            'creador' => $fila['nombre_creador'] ?? 'Desconocido',
            'usuarios' => []
        ];
    }
    if ($fila['nombre_usuario']) {
        $grupos[$id]['usuarios'][] = $fila['nombre_usuario'];
    }
}

// Enviar los datos como JSON
echo json_encode(array_values($grupos));
?>
