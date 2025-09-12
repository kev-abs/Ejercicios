<?php

$url = "http://localhost:8080/resena";


$consumo = file_get_contents($url);

if ($consumo === false) {
    die("Error al consumir el servicio.\n");
}

$resenas = json_decode($consumo);

$opcion = readline("Ingresa alguna opción para ver reseñas: v (todas), b (buenas), m (malas): ");

foreach ($resenas as $resena) {
    $datos = explode(" | ", $resena);

    $calificacion = (int)$datos[3];

    $tipo = ($calificacion >= 5) ? "b" : "m";

    if ($opcion === "v" || $opcion === $tipo) {
        echo $resena . " → " . (($tipo === "b") ? "BUENA" : "MALA") . " RESEÑA\n";
    }


}

?>
