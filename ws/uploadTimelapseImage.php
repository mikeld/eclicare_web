<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Directorio para imágenes de time-lapse
$targetDir = "../img/timelapse/";

// Crear directorio si no existe
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['image'])) {
        echo json_encode([
            'success' => 0,
            'message' => 'No se recibió imagen'
        ]);
        exit;
    }

    $file = $_FILES['image'];
    $timelapseId = $_POST['timelapse_id'] ?? 'unknown';
    $frameIndex = $_POST['frame_index'] ?? '0';
    
    // Nombre del archivo
    $filename = "timelapse_{$timelapseId}_frame_{$frameIndex}.jpg";
    $targetPath = $targetDir . $filename;
    
    // Mover archivo
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        echo json_encode([
            'success' => 1,
            'message' => 'Imagen subida correctamente',
            'image_path' => "timelapse/" . $filename // Ruta relativa
        ]);
    } else {
        echo json_encode([
            'success' => 0,
            'message' => 'Error al guardar imagen'
        ]);
    }
} else {
    echo json_encode([
        'success' => 0,
        'message' => 'Método no permitido'
    ]);
}
?>