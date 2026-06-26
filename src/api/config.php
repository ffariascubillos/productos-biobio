<?php

function obtenerConexion(): PDO
{
    $host = 'localhost';
    $port = '5432';
    $dbname = 'productos_biobio';
    $user = 'postgres';
    $password = 'admin';

    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";

    return new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}
