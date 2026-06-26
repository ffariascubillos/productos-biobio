<?php

require_once __DIR__ . '/config.php';

// Endpoint JSON para cargar el select de bodegas.
header('Content-Type: application/json; charset=utf-8');

try {
    $conexion = obtenerConexion();

    // Consulta las bodegas disponibles para el formulario.
    $consulta = $conexion->query(
        'SELECT id_bodega, nombre_bodega FROM bodegas ORDER BY nombre_bodega'
    );

    echo json_encode([
        'success' => true,
        'message' => 'Bodegas obtenidas correctamente.',
        'data' => $consulta->fetchAll(),
    ]);
} catch (Throwable $error) {
    echo json_encode([
        'success' => false,
        'message' => 'No se pudieron obtener los datos.',
        'data' => [],
    ]);
}
