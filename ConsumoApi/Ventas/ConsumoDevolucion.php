<?php
$url = "http://localhost:8080/cupon";


$consumo = file_get_contents($url);

if ($consumo === FALSE) {
    die("Error al consumir el servicio.");
}


$cupon = json_decode($consumo);