<?php

$directorio = '../img/';
$imagenes = scandir($directorio);
$listaImagenes = array();

foreach ($imagenes as $img) {
    if ($img == '.' || $img == '..') continue;
    if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $img)) {
        list($tipo, $id) = explode('-', trim($img, '.jpg .jpeg .png .gif'), 2);
        $listaImagenes[] = array('nombre' => $img, 'tipo' => strtoupper($tipo), 'id' => $id);
    }
}

echo json_encode($listaImagenes);

?>