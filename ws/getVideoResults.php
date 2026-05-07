<?php
require_once '../bbdd/conexion.php';

// 👇 NUEVO: Headers anti-caché
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

try {
    // Usar created_at si existe, sino usar date
    $sql = "SELECT * FROM video_results 
            ORDER BY COALESCE(created_at, date) DESC 
            LIMIT 50";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($results) > 0) {
        echo json_encode([
            'success' => 1, 
            'message' => 'Registros de videos encontrados',
            'count' => count($results),
            'timestamp' => time(), // 👈 NUEVO: timestamp para debugging
            'data' => $results
        ]);
    } else {
        $countSql = "SELECT COUNT(*) as total FROM video_results";
        $countStmt = $conn->prepare($countSql);
        $countStmt->execute();
        $count = $countStmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => 0, 
            'message' => 'No se encontraron registros de videos',
            'total_records_in_db' => $count['total'],
            'timestamp' => time(),
            'data' => []
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => 0, 
        'message' => 'Error de base de datos: ' . $e->getMessage(),
        'timestamp' => time()
    ]);
}
?>