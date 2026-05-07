<?php
// insertTimelapseResults.php
require_once '../bbdd/conexion.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Habilitar error reporting para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Leer JSON del body
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Log para debug
error_log("=== INSERTING TIMELAPSE ===");
error_log("Received data: " . print_r($data, true));

if (!$data) {
    echo json_encode([
        'success' => 0, 
        'message' => 'No se recibieron datos JSON válidos'
    ]);
    exit;
}

try {
    $id = $data['id'] ?? null;
    $num_frames = $data['num_frames'] ?? 0;
    $date = $data['date'] ?? date('Y-m-d H:i:s');
    
    // Rutas de imágenes
    $image_paths = isset($data['image_paths']) ? json_encode($data['image_paths']) : '[]';
    
    // Log para verificar image_paths
    error_log("image_paths JSON: " . $image_paths);
    error_log("image_paths count: " . count(json_decode($image_paths, true)));
    
    // Arrays de valores numéricos
    $lab_values = isset($data['lab_values']) ? json_encode($data['lab_values']) : '[]';
    $xyz_values = isset($data['xyz_values']) ? json_encode($data['xyz_values']) : '[]';
    $v_values = isset($data['v_values']) ? json_encode($data['v_values']) : '[]';
    $red_avg_values = isset($data['red_avg_values']) ? json_encode($data['red_avg_values']) : '[]';
    $red_pixels_values = isset($data['red_pixels_values']) ? json_encode($data['red_pixels_values']) : '[]';
    $saturation_values = isset($data['saturation_values']) ? json_encode($data['saturation_values']) : '[]';
    $contrast_values = isset($data['contrast_values']) ? json_encode($data['contrast_values']) : '[]';
    $rms_values = isset($data['rms_values']) ? json_encode($data['rms_values']) : '[]';
    $kmeans_values = isset($data['kmeans_values']) ? json_encode($data['kmeans_values']) : '[]';
    
    if (!$id || $num_frames == 0) {
        echo json_encode([
            'success' => 0,
            'message' => 'Faltan datos obligatorios (id o num_frames)'
        ]);
        exit;
    }
    
    // ⚠️ CRÍTICO: Verifica la estructura de tu tabla
    // Si usas PDO (como en getTimelapseResults), usa esto:
    if ($conn instanceof PDO) {
        $sql = "INSERT INTO timelapse_results (
            id, num_frames, date, image_paths,
            lab_values, xyz_values, v_values, 
            red_avg_values, red_pixels_values,
            saturation_values, contrast_values, 
            rms_values, kmeans_values
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Error preparando statement: " . print_r($conn->errorInfo(), true));
        }
        
        $success = $stmt->execute([
            $id, $num_frames, $date, $image_paths,
            $lab_values, $xyz_values, $v_values,
            $red_avg_values, $red_pixels_values,
            $saturation_values, $contrast_values,
            $rms_values, $kmeans_values
        ]);
        
        if (!$success) {
            throw new Exception("Error ejecutando: " . print_r($stmt->errorInfo(), true));
        }
        
    } else {
        // Si usas mysqli
        $sql = "INSERT INTO timelapse_results (
            id, num_frames, date, image_paths,
            lab_values, xyz_values, v_values, 
            red_avg_values, red_pixels_values,
            saturation_values, contrast_values, 
            rms_values, kmeans_values
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Error preparando statement: " . $conn->error);
        }
        
        $stmt->bind_param(
            "sissssssssss",
            $id, $num_frames, $date, $image_paths,
            $lab_values, $xyz_values, $v_values,
            $red_avg_values, $red_pixels_values,
            $saturation_values, $contrast_values,
            $rms_values, $kmeans_values
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Error ejecutando: " . $stmt->error);
        }
        
        $stmt->close();
    }
    
    echo json_encode([
        'success' => 1,
        'message' => 'Time-lapse guardado correctamente',
        'id' => $id,
        'num_frames' => $num_frames,
        'images_count' => count(json_decode($image_paths, true))
    ]);
    
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
    
} catch (Exception $e) {
    error_log("ERROR EN INSERT TIMELAPSE: " . $e->getMessage());
    echo json_encode([
        'success' => 0,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>