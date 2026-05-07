<?php
// insertarResultado.php

// Incluir el archivo de conexión
require_once '../bbdd/conexion.php';

// Recibir los parámetros de la petición GET
$colorSeleccionado = isset($_GET['colorSeleccionado']) ? $_GET['colorSeleccionado'] : null;
$colorDetectado = isset($_GET['colorDetectado']) ? $_GET['colorDetectado'] : null;
$colorHex = isset($_GET['colorHex']) ? $_GET['colorHex'] : null;
$colorRGB = isset($_GET['colorRGB']) ? $_GET['colorRGB'] : null;
$pixels = isset($_GET['pixels']) ? (int)$_GET['pixels'] : null;
$media = isset($_GET['media']) ? (float)$_GET['media'] : null;

$fecha = date('Y-m-d H:i:s');

// Preparar la respuesta JSON
header('Content-Type: application/json');

// Verificar que los parámetros necesarios están presentes
if ($colorSeleccionado === null || $colorDetectado === null || $colorHex === null || $colorRGB === null || $pixels === null || $media === null) {
    echo json_encode(['success' => 0, 'message' => 'Faltan parámetros']);
    exit;
}

try {
    // Preparar la consulta SQL para insertar el resultado
    $sql = "INSERT INTO resultados (colorSeleccionado, colorDetectado, colorHex, colorRGB, pixels, media, fecha) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Ejecutar la consulta
    $stmt->execute([$colorSeleccionado, $colorDetectado, $colorHex, $colorRGB, $pixels, $media, $fecha]);

    // Verificar si el insert fue exitoso
    if ($stmt->rowCount() > 0) {
        $lastInsertId = $conn->lastInsertId(); // Obtener el último ID insertado
        echo json_encode(['success' => 1, 'message' => 'prueba', 'id' => $lastInsertId]); // Devolver el ID en la respuesta
    } else {
        echo json_encode(['success' => 0, 'message' => 'No se pudo insertar el resultado']);
    }
} catch (PDOException $e) {
    // En caso de error en la base de datos, devolver éxito = 0
    echo json_encode(['success' => 0, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
}
?>
