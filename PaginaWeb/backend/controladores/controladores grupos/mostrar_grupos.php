<?php
ini_set('display_errors', 1); // Muestra errores en pantalla
ini_set('display_startup_errors', 1); // Muestra errores al iniciar PHP
error_reporting(E_ALL); // Reporta todos los tipos de errores
header('Content-Type: application/json'); // Respuesta en formato JSON
require '../conexion.php'; // Conecta con la base de datos

// Consulta que obtiene los grupos con su creador y miembros
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

$resultado = $conexion->query($sql); // Ejecuta la consulta
if (!$resultado) { // Si hay error en la consulta
    echo json_encode(['error' => 'Error en la consulta: ' . $conexion->error]);
    exit;
}

$grupos = []; // Array para guardar los grupos
while ($fila = $resultado->fetch_assoc()) { // Recorre los resultados
    $id = $fila['id_grupo']; // ID del grupo actual
    if (!isset($grupos[$id])) { // Si el grupo no fue agregado aún
        $grupos[$id] = [
            'id_grupo' => $id,
            'nombre_grupo' => $fila['nombre_grupo'],
            'creador' => $fila['nombre_creador'] ?? 'Desconocido', // Si no hay creador, pone 'Desconocido'
            'usuarios' => [] // Lista de miembros
        ];
    }
    if ($fila['nombre_usuario']) { // Si hay nombre de usuario
        $grupos[$id]['usuarios'][] = $fila['nombre_usuario']; // Lo agrega al grupo
    }
}

echo json_encode(array_values($grupos)); // Convierte el array a JSON y lo imprime
?>
