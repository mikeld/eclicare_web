<?php
// Configurar headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

error_log("uploadVideo.php iniciado");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log("Método no permitido: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode(['success' => 0, 'message' => 'Método no permitido']);
    exit;
}

error_log("POST data: " . print_r($_POST, true));
error_log("FILES data: " . print_r($_FILES, true));

if (!isset($_FILES['video']) || !isset($_POST['id']) || !isset($_POST['tipo'])) {
    $missing = [];
    if (!isset($_FILES['video'])) $missing[] = 'video';
    if (!isset($_POST['id'])) $missing[] = 'id';
    if (!isset($_POST['tipo'])) $missing[] = 'tipo';
    
    error_log("Datos incompletos. Faltan: " . implode(', ', $missing));
    echo json_encode(['success' => 0, 'message' => 'Datos incompletos. Faltan: ' . implode(', ', $missing)]);
    exit;
}

if ($_FILES['video']['error'] !== UPLOAD_ERR_OK) {
    $error_messages = array(
        UPLOAD_ERR_INI_SIZE => 'El archivo es demasiado grande (ini_size)',
        UPLOAD_ERR_FORM_SIZE => 'El archivo es demasiado grande (form_size)',
        UPLOAD_ERR_PARTIAL => 'El archivo se subió parcialmente',
        UPLOAD_ERR_NO_FILE => 'No se subió ningún archivo',
        UPLOAD_ERR_NO_TMP_DIR => 'Falta la carpeta temporal',
        UPLOAD_ERR_CANT_WRITE => 'Error al escribir el archivo',
        UPLOAD_ERR_EXTENSION => 'Extensión PHP detuvo la subida'
    );
    
    $error_msg = $error_messages[$_FILES['video']['error']] ?? 'Error desconocido';
    error_log("Error de upload: " . $error_msg);
    echo json_encode(['success' => 0, 'message' => 'Error de upload: ' . $error_msg]);
    exit;
}

// CAMBIO 1: Usar ruta relativa correcta
$uploadDir = __DIR__ . '/uploads/videos/';

if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        error_log("No se pudo crear el directorio: " . $uploadDir);
        echo json_encode(['success' => 0, 'message' => 'No se pudo crear el directorio de uploads']);
        exit;
    }
}

$id = $_POST['id'];
$tipo = $_POST['tipo'];
$originalName = $_FILES['video']['name'];
$extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION)); // CAMBIO 2: Convertir a minúsculas

$allowedExtensions = ['mp4', 'avi', 'mov', 'mkv', 'webm', '3gp'];
if (!in_array($extension, $allowedExtensions)) {
    error_log("Extensión no permitida: " . $extension);
    echo json_encode(['success' => 0, 'message' => 'Extensión de archivo no permitida: ' . $extension]);
    exit;
}

$filename = $tipo . '-' . $id . '.' . $extension;
$uploadPath = $uploadDir . $filename;

error_log("Intentando mover archivo a: " . $uploadPath);
error_log("Archivo temporal: " . $_FILES['video']['tmp_name']);
error_log("Tamaño del archivo: " . $_FILES['video']['size']);

if (move_uploaded_file($_FILES['video']['tmp_name'], $uploadPath)) {
    // CAMBIO 3: Verificar que el archivo se guardó correctamente
    clearstatcache(); // Limpiar caché del sistema de archivos
    
    if (!file_exists($uploadPath)) {
        error_log("ERROR: El archivo no existe después de moverlo");
        echo json_encode(['success' => 0, 'message' => 'Error al verificar el archivo subido']);
        exit;
    }
    
    $fileSize = filesize($uploadPath);
    if ($fileSize === false || $fileSize === 0) {
        error_log("ERROR: El archivo está vacío o no se puede leer");
        unlink($uploadPath); // Eliminar archivo corrupto
        echo json_encode(['success' => 0, 'message' => 'El archivo subido está vacío']);
        exit;
    }
    
    // CAMBIO 4: Establecer permisos correctos
    chmod($uploadPath, 0644);
    
    // CAMBIO 5: Construir URL correcta (ajusta según tu estructura)
    $baseUrl = 'https://mikeld19.sg-host.com/ws/uploads/videos/';
    $videoUrl = $baseUrl . $filename;
    
    error_log("✅ Archivo subido exitosamente: " . $filename);
    error_log("✅ Tamaño verificado: " . $fileSize . " bytes");
    error_log("✅ URL del video: " . $videoUrl);
    
    echo json_encode([
        'success' => 1,
        'message' => 'Video subido correctamente',
        'filename' => $filename,
        'size' => $fileSize,
        'url' => $videoUrl // NUEVO: Incluir URL completa
    ]);
} else {
    $error = error_get_last();
    error_log("Error al mover archivo: " . print_r($error, true));
    error_log("Verificar permisos de directorio: " . $uploadDir);
    echo json_encode(['success' => 0, 'message' => 'Error al subir el video. Verificar permisos del servidor']);
}
?>