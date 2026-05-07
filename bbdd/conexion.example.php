<?php
// Copia este archivo como bbdd/conexion.php y rellena tus credenciales locales.

$host = "localhost";
$dbname = "database_name";
$username = "database_user";
$password = "database_password";

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
