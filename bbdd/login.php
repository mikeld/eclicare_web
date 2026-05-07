<?php
session_start();
header('Content-Type: application/json');

// Incluye la conexión de la base de datos
require 'conexion.php';

// Recupera datos del formulario
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$passwordMd5 = md5($password);

try {
    // Consulta a la base de datos
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email AND pass = :password");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $passwordMd5);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Inicio de sesión exitoso
        $_SESSION['usuario'] = $email; 
        echo json_encode(1);
    } else {
        // Fallo en el inicio de sesión
        echo json_encode(0);
    }
} catch(PDOException $e) {
    echo json_encode("Error: " . $e->getMessage());
}
?>
