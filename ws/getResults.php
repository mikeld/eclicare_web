<?php
require_once '../bbdd/conexion.php';

header('Content-Type: application/json');

try {
    // Consulta para obtener los últimos registros (ordenados por fecha descendente)
    $sql = "SELECT * FROM results ORDER BY date DESC LIMIT 50";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($results) > 0) {
        echo json_encode([
            'success' => 1, 
            'message' => 'Registros encontrados',
            'count' => count($results),
            'data' => $results
        ]);
    } else {
        // Debug: verificar si la tabla existe y tiene datos
        $countSql = "SELECT COUNT(*) as total FROM results";
        $countStmt = $conn->prepare($countSql);
        $countStmt->execute();
        $count = $countStmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => 0, 
            'message' => 'No se encontraron registros',
            'total_records_in_db' => $count['total'],
            'data' => []
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => 0, 
        'message' => 'Error de base de datos: ' . $e->getMessage()
    ]);
}
?>