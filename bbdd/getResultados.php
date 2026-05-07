<?php
// bbdd/getResults.php
// Incluye tu archivo de conexión a la base de datos
include 'conexion.php';

// Inicia la sesión
session_start();
header('Content-Type: application/json'); 

// Verifica si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    echo json_encode(array('error' => 'Usuario no autenticado'));
    exit;
}

try {
    // Query para obtener todos los datos de la tabla results, ordenados por fecha descendente
    $query = "SELECT id, image, llab, xyz, vhsv, red, porcentaje, saturacion, contraste, rms, kmeans, date 
              FROM results 
              ORDER BY date DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    $resultados = array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $resultados[] = $row;
    }
    
    echo json_encode($resultados);
    
} catch (Exception $e) {
    echo json_encode(array('error' => 'Error en la ejecución: ' . $e->getMessage()));
    exit;
}
?>