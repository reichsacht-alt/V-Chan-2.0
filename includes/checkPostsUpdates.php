<?php
require_once "config.php"; // Asegúrate de que esto conecte correctamente a tu DB

header('Content-Type: application/json');

// Consulta la última fecha de modificación (basado en columna 'up_date')
$sql = "SELECT uid, up_date as last_update FROM posts ORDER BY up_date DESC LIMIT 1";
$result = mysqli_query($link, $sql);

if ($result && $row = mysqli_fetch_assoc($result)) {
    echo json_encode([
        'lastUpdate' => $row['last_update'],
        'uid' => $row['uid']
    ]);
} else {
    echo json_encode([
        'error' => true,
        'message' => 'No se pudo consultar la base de datos'
    ]);
}
