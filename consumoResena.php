<?php

$url = "http://localhost:8080/resena";

$consumo = file_get_contents($url);

if ($consumo === false) {
    die("Error al consumir el servicio .");
}

$resenas = json_decode($consumo);

foreach ($resenas as $resena) {
    echo $resena;

    $partes = explode(" | ", $resena);
    $calificacion = (int)$partes[3];

    if ($calificacion >= 5) {
        echo " → BUENA RESEÑA\n";
    } else {
        echo " → MALA RESEÑA\n";
    }


}

?>