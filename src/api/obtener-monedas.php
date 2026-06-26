<?php

require_once __DIR__ . '/config.php';

// Endpoint JSON para cargar el select de monedas.
header('Content-Type: application/json; charset=utf-8');

try {
    $conexion = obtenerConexion();

    // Consulta las monedas disponibles para el formulario.
    $consulta = $conexion->query(
        'SELECT id_moneda, nombre_moneda FROM monedas ORDER BY nombre_moneda'
    );

    echo json_encode([
        'success' => true,
        'message' => 'Monedas obtenidas correctamente.',
        'data' => $consulta->fetchAll(),
    ]);
} catch (Throwable $error) {
    echo json_encode([
        'success' => false,
        'message' => 'No se pudieron obtener los datos.',
        'data' => [],
    ]);
}
