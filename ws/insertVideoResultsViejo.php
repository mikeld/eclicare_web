<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Incluir el archivo de conexión (igual que en tus otros archivos)
require_once '../bbdd/conexion.php';

// Logging para debugging
error_log("insertVideoResults.php iniciado");
error_log("POST data: " . print_r($_POST, true));

// Verificar datos requeridos
$required_fields = [
    'video', 'analysis_mode', 'total_frames',
    'llab_avg', 'llab_min', 'llab_max',
    'xyz_avg', 'xyz_min', 'xyz_max',
    'vhsv_avg', 'vhsv_min', 'vhsv_max',
    'red_avg', 'red_min', 'red_max'
];

$missing_fields = [];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field])) {
        $missing_fields[] = $field;
    }
}

if (!empty($missing_fields)) {
    error_log("Campos faltantes: " . implode(', ', $missing_fields));
    echo json_encode([
        'success' => 0,
        'message' => 'Campos faltantes: ' . implode(', ', $missing_fields),
        'received_data' => $_POST // Para debug
    ]);
    exit;
}

try {
    // Verificar si la tabla existe
    $stmt = $conn->query("SHOW TABLES LIKE 'video_results'");
    if ($stmt->rowCount() == 0) {
        error_log("La tabla video_results no existe");
        echo json_encode([
            'success' => 0,
            'message' => 'La tabla video_results no existe en la base de datos'
        ]);
        exit;
    }
    
    error_log("Tabla video_results encontrada, preparando inserción");
    
    $sql = "INSERT INTO video_results (
        video, analysis_mode, total_frames, 
        llab_avg, llab_min, llab_max,
        xyz_avg, xyz_min, xyz_max,
        vhsv_avg, vhsv_min, vhsv_max,
        red_avg, red_min, red_max
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([
        $_POST['video'],
        $_POST['analysis_mode'],
        intval($_POST['total_frames']),
        floatval($_POST['llab_avg']),
        floatval($_POST['llab_min']),
        floatval($_POST['llab_max']),
        floatval($_POST['xyz_avg']),
        floatval($_POST['xyz_min']),
        floatval($_POST['xyz_max']),
        floatval($_POST['vhsv_avg']),
        floatval($_POST['vhsv_min']),
        floatval($_POST['vhsv_max']),
        floatval($_POST['red_avg']),
        floatval($_POST['red_min']),
        floatval($_POST['red_max'])
    ]);
    
    if ($stmt->rowCount() > 0) {
        $lastInsertId = $conn->lastInsertId();
        error_log("Resultados insertados exitosamente. ID: " . $lastInsertId);
        echo json_encode([
            'success' => 1,
            'message' => 'Resultados de video insertados correctamente',
            'id' => $lastInsertId
        ]);
    } else {
        error_log("No se pudo insertar el resultado");
        echo json_encode([
            'success' => 0,
            'message' => 'No se pudo insertar el resultado'
        ]);
    }
    
} catch (PDOException $e) {
    error_log("Error de base de datos: " . $e->getMessage());
    echo json_encode([
        'success' => 0,
        'message' => 'Error en la base de datos: ' . $e->getMessage()
    ]);
}
?>