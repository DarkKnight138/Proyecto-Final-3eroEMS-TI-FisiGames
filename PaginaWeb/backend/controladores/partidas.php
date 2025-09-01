<?php
    require '../conexion.php';

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $idJuego = trim($_POST['idJuego']);
        $id_usuario = trim($_POST['idUsuario']);
        $puntuacion = trim($_POST['puntuacion']);

        $stmt = $conexion->prepare("INSERT INTO partidas_jugadas (id_cuenta, id_juego, puntaje) VALUES (?,?,?)");
        $stmt->bind_param("i", $id_usuario, $idJuego, $puntuacion);
        $stmt->execute();
        $stmt->close();
    }
?>