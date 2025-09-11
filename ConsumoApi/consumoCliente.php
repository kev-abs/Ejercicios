<?php
$url = "http://localhost:8080/clientes";

$consumo = file_get_contents($url);

if ($consumo === false) {
    die("Error al consumir el servicio");
}

$clientes = json_decode($consumo);

foreach ($clientes as $cliente) {
    $datos = explode(" | ", $cliente);
    $id = (int) trim($datos[0]);

    if ($id >= 3) {
        echo $cliente . "\n";
    }
}
?>