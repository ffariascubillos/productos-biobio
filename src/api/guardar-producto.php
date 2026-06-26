<?php

require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');

function responderJson(bool $success, string $message): void
{
    echo json_encode([
        'success' => $success,
        'message' => $message,
    ]);
    exit;
}

function obtenerTextoPost(string $campo): string
{
    return trim($_POST[$campo] ?? '');
}

function obtenerEnteroPost(string $campo): ?int
{
    $valor = filter_var($_POST[$campo] ?? null, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);

    return $valor === false ? null : $valor;
}

function obtenerLongitud(string $texto): int
{
    if (function_exists('mb_strlen')) {
        return mb_strlen($texto, 'UTF-8');
    }

    return strlen($texto);
}

function validarDatosBasicos(
    string $codigo,
    string $nombre,
    ?int $idBodega,
    ?int $idSucursal,
    ?int $idMoneda,
    string $precio,
    array $materiales,
    string $descripcion
): void {
    if ($codigo === '') {
        responderJson(false, 'El código del producto no puede estar en blanco.');
    }

    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/', $codigo)) {
        responderJson(false, 'El código del producto debe contener letras y números');
    }

    if (obtenerLongitud($codigo) < 5 || obtenerLongitud($codigo) > 15) {
        responderJson(false, 'El código del producto debe tener entre 5 y 15 caracteres.');
    }

    if ($nombre === '') {
        responderJson(false, 'El nombre del producto no puede estar en blanco.');
    }

    if (obtenerLongitud($nombre) < 2 || obtenerLongitud($nombre) > 50) {
        responderJson(false, 'El nombre del producto debe tener entre 2 y 50 caracteres.');
    }

    if ($idBodega === null) {
        responderJson(false, 'Debe seleccionar una bodega.');
    }

    if ($idSucursal === null) {
        responderJson(false, 'Debe seleccionar una sucursal para la bodega seleccionada.');
    }

    if ($idMoneda === null) {
        responderJson(false, 'Debe seleccionar una moneda para el producto.');
    }

    if ($precio === '') {
        responderJson(false, 'El precio del producto no puede estar en blanco.');
    }

    if (!preg_match('/^(?!0+(?:\.0{1,2})?$)\d+(?:\.\d{1,2})?$/', $precio)) {
        responderJson(false, 'El precio del producto debe ser un número positivo con hasta dos decimales.');
    }

    if (count($materiales) < 2) {
        responderJson(false, 'Debe seleccionar al menos dos materiales para el producto.');
    }

    if ($descripcion === '') {
        responderJson(false, 'La descripción del producto no puede estar en blanco.');
    }

    if (obtenerLongitud($descripcion) < 10 || obtenerLongitud($descripcion) > 1000) {
        responderJson(false, 'La descripción del producto debe tener entre 10 y 1000 caracteres.');
    }
}

function existeRegistro(PDO $conexion, string $sql, array $parametros): bool
{
    $consulta = $conexion->prepare($sql);
    $consulta->execute($parametros);

    return (bool) $consulta->fetchColumn();
}

function validarDatosEnBase(
    PDO $conexion,
    string $codigo,
    int $idBodega,
    int $idSucursal,
    int $idMoneda,
    array $materiales
): void {
    if (existeRegistro(
        $conexion,
        'SELECT 1 FROM productos WHERE codigo_producto = :codigo',
        ['codigo' => $codigo]
    )) {
        responderJson(false, 'El código del producto ya está registrado.');
    }

    if (!existeRegistro(
        $conexion,
        'SELECT 1 FROM bodegas WHERE id_bodega = :id_bodega',
        ['id_bodega' => $idBodega]
    )) {
        responderJson(false, 'Debe seleccionar una bodega.');
    }

    if (!existeRegistro(
        $conexion,
        'SELECT 1 FROM sucursales WHERE id_sucursal = :id_sucursal AND id_bodega = :id_bodega',
        [
            'id_sucursal' => $idSucursal,
            'id_bodega' => $idBodega,
        ]
    )) {
        responderJson(false, 'Debe seleccionar una sucursal para la bodega seleccionada.');
    }

    if (!existeRegistro(
        $conexion,
        'SELECT 1 FROM monedas WHERE id_moneda = :id_moneda',
        ['id_moneda' => $idMoneda]
    )) {
        responderJson(false, 'Debe seleccionar una moneda para el producto.');
    }

    $placeholders = implode(',', array_fill(0, count($materiales), '?'));
    $consulta = $conexion->prepare(
        "SELECT COUNT(*) FROM materiales WHERE id_material IN ({$placeholders})"
    );
    $consulta->execute($materiales);

    if ((int) $consulta->fetchColumn() !== count($materiales)) {
        responderJson(false, 'Debe seleccionar al menos dos materiales para el producto.');
    }
}

function obtenerMaterialesPost(): array
{
    $materiales = $_POST['materiales'] ?? [];

    if (!is_array($materiales)) {
        return [];
    }

    $materialesValidos = [];

    foreach ($materiales as $material) {
        $idMaterial = filter_var($material, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($idMaterial !== false) {
            $materialesValidos[] = $idMaterial;
        }
    }

    return array_values(array_unique($materialesValidos));
}

$codigo = obtenerTextoPost('cod_pro');
$nombre = obtenerTextoPost('nombre_pro');
$idBodega = obtenerEnteroPost('bodega');
$idSucursal = obtenerEnteroPost('sucursal');
$idMoneda = obtenerEnteroPost('moneda');
$precio = obtenerTextoPost('precio');
$descripcion = obtenerTextoPost('descripcion');
$materiales = obtenerMaterialesPost();

validarDatosBasicos(
    $codigo,
    $nombre,
    $idBodega,
    $idSucursal,
    $idMoneda,
    $precio,
    $materiales,
    $descripcion
);

$conexion = null;

try {
    $conexion = obtenerConexion();

    validarDatosEnBase(
        $conexion,
        $codigo,
        $idBodega,
        $idSucursal,
        $idMoneda,
        $materiales
    );

    $conexion->beginTransaction();

    // Guarda el producto principal y obtiene su ID para relacionar materiales.
    $consultaProducto = $conexion->prepare(
        'INSERT INTO productos (
            codigo_producto,
            nombre_producto,
            descripcion,
            precio,
            id_bodega,
            id_sucursal,
            id_moneda
        ) VALUES (
            :codigo,
            :nombre,
            :descripcion,
            :precio,
            :id_bodega,
            :id_sucursal,
            :id_moneda
        ) RETURNING id_producto'
    );

    $consultaProducto->execute([
        'codigo' => $codigo,
        'nombre' => $nombre,
        'descripcion' => $descripcion,
        'precio' => $precio,
        'id_bodega' => $idBodega,
        'id_sucursal' => $idSucursal,
        'id_moneda' => $idMoneda,
    ]);

    $idProducto = (int) $consultaProducto->fetchColumn();

    // Registra la relacion muchos a muchos entre producto y materiales.
    $consultaMaterial = $conexion->prepare(
        'INSERT INTO producto_material (id_producto, id_material)
         VALUES (:id_producto, :id_material)'
    );

    foreach ($materiales as $idMaterial) {
        $consultaMaterial->execute([
            'id_producto' => $idProducto,
            'id_material' => $idMaterial,
        ]);
    }

    $conexion->commit();

    responderJson(true, 'Producto registrado correctamente.');
} catch (Throwable $error) {
    if ($conexion instanceof PDO && $conexion->inTransaction()) {
        $conexion->rollBack();
    }

    responderJson(false, 'No se pudo registrar el producto.');
}
