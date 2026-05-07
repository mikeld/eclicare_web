<?php
// conexion.php

$localConfigPath = __DIR__ . '/conexion.local.php';
$localConfig = file_exists($localConfigPath) ? require $localConfigPath : [];

$host = getenv('ECLICARE_DB_HOST') ?: ($localConfig['host'] ?? 'localhost');
$dbname = getenv('ECLICARE_DB_NAME') ?: ($localConfig['dbname'] ?? '');
$username = getenv('ECLICARE_DB_USER') ?: ($localConfig['username'] ?? '');
$password = getenv('ECLICARE_DB_PASSWORD') ?: ($localConfig['password'] ?? '');

if ($dbname === '' || $username === '') {
    http_response_code(500);
    echo "Error de configuracion: faltan credenciales de base de datos.";
    exit;
}

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch(PDOException $e) {
    error_log("Error de conexion a base de datos: " . $e->getMessage());
    http_response_code(500);
    echo "Error de conexion a base de datos.";
    exit;
}
?>
