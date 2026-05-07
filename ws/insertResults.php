<?php
require_once '../bbdd/conexion.php';

// Cambiar de $_GET a $_POST para recibir parámetros POST
$image = isset($_POST['image']) ? $_POST['image'] : null;
$llab = isset($_POST['llab']) ? (double)$_POST['llab'] : null;
$xyz = isset($_POST['xyz']) ? (double)$_POST['xyz'] : null;
$vhsv = isset($_POST['vhsv']) ? (double)$_POST['vhsv'] : null;
$red = isset($_POST['red']) ? (double)$_POST['red'] : null;
$porcentaje = isset($_POST['porcentaje']) ? (double)$_POST['porcentaje'] : null;
$saturacion = isset($_POST['saturacion']) ? (double)$_POST['saturacion'] : null;
$contraste = isset($_POST['contraste']) ? (double)$_POST['contraste'] : null;
$rms = isset($_POST['rms']) ? (double)$_POST['rms'] : null;
$kmeans = isset($_POST['kmeans']) ? (double)$_POST['kmeans'] : null;
$fecha = date('Y-m-d H:i:s');

header('Content-Type: application/json');

// Validación de parámetros
if ($image === null || $llab === null || $xyz === null || $vhsv === null ||
    $red === null || $porcentaje === null || $saturacion === null ||
    $contraste === null || $rms === null || $kmeans === null) {
    
    // Debug: mostrar qué parámetros faltan
    $missing = [];
    if ($image === null) $missing[] = 'image';
    if ($llab === null) $missing[] = 'llab';
    if ($xyz === null) $missing[] = 'xyz';
    if ($vhsv === null) $missing[] = 'vhsv';
    if ($red === null) $missing[] = 'red';
    if ($porcentaje === null) $missing[] = 'porcentaje';
    if ($saturacion === null) $missing[] = 'saturacion';
    if ($contraste === null) $missing[] = 'contraste';
    if ($rms === null) $missing[] = 'rms';
    if ($kmeans === null) $missing[] = 'kmeans';
    
    echo json_encode([
        'success' => 0, 
        'message' => 'Faltan parámetros: ' . implode(', ', $missing),
        'received_data' => $_POST // Para debug
    ]);
    exit;
}

try {
    $sql = "INSERT INTO results (image, llab, xyz, vhsv, red, porcentaje, saturacion, contraste, rms, kmeans, date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$image, $llab, $xyz, $vhsv, $red, $porcentaje, $saturacion, $contraste, $rms, $kmeans, $fecha]);
    
    if ($stmt->rowCount() > 0) {
        $lastInsertId = $conn->lastInsertId();
        echo json_encode(['success' => 1, 'message' => 'Resultado insertado', 'id' => $lastInsertId]);
    } else {
        echo json_encode(['success' => 0, 'message' => 'No se pudo insertar el resultado']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => 0, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
}
?>