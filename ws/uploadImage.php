<?php
// uploadImage.php

require_once '../bbdd/conexion.php';

// Asegurarse de que el método de solicitud es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; 
    $tipo = $_POST['tipo'];
    $filePath = "../img/" .$tipo."-". $id . ".jpg"; 

    // Mover el archivo subido a la ruta deseada
    if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
        echo json_encode(['success' => 1, 'message' => 'Imagen subida con éxito']);
    } else {
        echo json_encode(['success' => 0, 'message' => 'Error al subir la imagen']);
    }
} else {
    echo json_encode(['success' => 0, 'message' => 'Método de solicitud no válido']);
}
?>
