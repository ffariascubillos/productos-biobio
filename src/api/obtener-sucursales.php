<?php

require_once __DIR__ . '/config.php';

// Endpoint JSON para cargar sucursales segun la bodega seleccionada.
header('Content-Type: application/json; charset=utf-8');

// Valida el parametro externo antes de consultar la base de datos.
$idBodega = filter_input(INPUT_GET, 'id_bodega', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1],
]);

if ($idBodega === false || $idBodega === null) {
    echo json_encode([
        'success' => false,
        'message' => 'Debe enviar una bodega valida.',
        'data' => [],
    ]);
    exit;
}

try {
    $conexion = obtenerConexion();

    // Consulta solo las sucursales asociadas a la bodega indicada.
    $consulta = $conexion->prepare(
        'SELECT id_sucursal, nombre_sucursal
         FROM sucursales
         WHERE id_bodega = :id_bodega
         ORDER BY nombre_sucursal'
    );
    $consulta->execute(['id_bodega' => $idBodega]);

    echo json_encode([
        'success' => true,
        'message' => 'Sucursales obtenidas correctamente.',
        'data' => $consulta->fetchAll(),
    ]);
} catch (Throwable $error) {
    echo json_encode([
        'success' => false,
        'message' => 'No se pudieron obtener los datos.',
        'data' => [],
    ]);
}
