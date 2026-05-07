<?php
require_once '../bbdd/conexion.php';

echo "Testing timelapse insert...\n\n";

// Test 1: Verificar conexión
echo "1. Conexión: ";
echo ($conn ? "✅ OK\n" : "❌ FAIL\n");

// Test 2: Verificar estructura de tabla
echo "\n2. Estructura de tabla:\n";
$result = $conn->query("DESCRIBE timelapse_results");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo "   - " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
} else {
    echo "   ❌ Error: " . $conn->error . "\n";
}

// Test 3: Insertar dato de prueba
echo "\n3. Insertar dato de prueba:\n";
$test_data = [
    'id' => 'test_' . time(),
    'num_frames' => 3,
    'date' => date('Y-m-d H:i:s'),
    'image_paths' => json_encode(['img1.jpg', 'img2.jpg', 'img3.jpg']),
    'lab_values' => json_encode([1.0, 2.0, 3.0]),
    'xyz_values' => json_encode([4.0, 5.0, 6.0]),
    'v_values' => json_encode([7.0, 8.0, 9.0]),
    'red_avg_values' => json_encode([10.0, 11.0, 12.0]),
    'red_pixels_values' => json_encode([13.0, 14.0, 15.0]),
    'saturation_values' => json_encode([16.0, 17.0, 18.0]),
    'contrast_values' => json_encode([19.0, 20.0, 21.0]),
    'rms_values' => json_encode([22.0, 23.0, 24.0]),
    'kmeans_values' => json_encode([25.0, 26.0, 27.0])
];

$sql = "INSERT INTO timelapse_results (
    id, num_frames, date, image_paths,
    lab_values, xyz_values, v_values,
    red_avg_values, red_pixels_values,
    saturation_values, contrast_values,
    rms_values, kmeans_values
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "   ❌ Error preparando: " . $conn->error . "\n";
    exit;
}

$stmt->bind_param(
    "sissssssssss",
    $test_data['id'],
    $test_data['num_frames'],
    $test_data['date'],
    $test_data['image_paths'],
    $test_data['lab_values'],
    $test_data['xyz_values'],
    $test_data['v_values'],
    $test_data['red_avg_values'],
    $test_data['red_pixels_values'],
    $test_data['saturation_values'],
    $test_data['contrast_values'],
    $test_data['rms_values'],
    $test_data['kmeans_values']
);

if ($stmt->execute()) {
    echo "   ✅ Insert exitoso!\n";
    echo "   ID: " . $test_data['id'] . "\n";
} else {
    echo "   ❌ Error: " . $stmt->error . "\n";
}

$stmt->close();
$conn->close();
?>