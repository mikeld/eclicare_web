<?php
// getTimelapseResults.php
require_once '../bbdd/conexion.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $sql = "SELECT * FROM timelapse_results ORDER BY date DESC LIMIT 50";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Decodificar los arrays JSON y calcular promedios
    foreach ($results as &$result) {
        // Decodificar arrays
        $result['image_paths'] = json_decode($result['image_paths'] ?? '[]', true) ?: []; // ✅ AÑADIDO
        $result['lab_values'] = json_decode($result['lab_values'] ?? '[]', true) ?: [];
        $result['xyz_values'] = json_decode($result['xyz_values'] ?? '[]', true) ?: [];
        $result['v_values'] = json_decode($result['v_values'] ?? '[]', true) ?: [];
        $result['red_avg_values'] = json_decode($result['red_avg_values'] ?? '[]', true) ?: [];
        $result['red_pixels_values'] = json_decode($result['red_pixels_values'] ?? '[]', true) ?: [];
        $result['saturation_values'] = json_decode($result['saturation_values'] ?? '[]', true) ?: [];
        $result['contrast_values'] = json_decode($result['contrast_values'] ?? '[]', true) ?: [];
        $result['rms_values'] = json_decode($result['rms_values'] ?? '[]', true) ?: [];
        $result['kmeans_values'] = json_decode($result['kmeans_values'] ?? '[]', true) ?: [];
        
        // ✅ AÑADIDO: Calcular promedios para mostrar en cards
        $result['lab_avg'] = !empty($result['lab_values']) 
            ? array_sum($result['lab_values']) / count($result['lab_values']) 
            : 0;
        
        $result['xyz_avg'] = !empty($result['xyz_values']) 
            ? array_sum($result['xyz_values']) / count($result['xyz_values']) 
            : 0;
        
        $result['v_avg'] = !empty($result['v_values']) 
            ? array_sum($result['v_values']) / count($result['v_values']) 
            : 0;
        
        $result['red_avg'] = !empty($result['red_avg_values']) 
            ? array_sum($result['red_avg_values']) / count($result['red_avg_values']) 
            : 0;
    }
    
    if (count($results) > 0) {
        echo json_encode([
            'success' => 1,
            'message' => 'Registros encontrados',
            'count' => count($results),
            'data' => $results
        ]);
    } else {
        echo json_encode([
            'success' => 0,
            'message' => 'No se encontraron registros',
            'data' => []
        ]);
    }
} catch (PDOException $e) {
    error_log("Error en getTimelapseResults: " . $e->getMessage());
    echo json_encode([
        'success' => 0,
        'message' => 'Error de base de datos: ' . $e->getMessage(),
        'data' => []
    ]);
}
?>