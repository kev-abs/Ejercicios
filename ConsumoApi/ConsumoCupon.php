<?php
$url = "http://localhost:8080/cupon";


$consumo = file_get_contents($url);

if ($consumo === FALSE) {
    die("Error al consumir el servicio.");
}


$cupon = json_decode($consumo);


// Pedir la opción al usuario
echo "Elige una opción:\n";
echo "1. Mostrar todos los cupones\n";
echo "2. Mostrar solo los cupones con 20% de descuento\n";
echo "3. Mostrar cupones con más de 30% de descuento\n";

$opcion = (int) readline("Opción: ");

echo "=== RESULTADO ===\n";

foreach ($cupon as $c) {
    $partes = explode(" ", $c); 
    $id = $partes[0];
    $codigo = $partes[1];
    $descuento = $partes[2];
    $fecha = $partes[3];

    if ($opcion == 1) {
        echo "ID: $id | Código: $codigo | Descuento: $descuento% | Fecha: $fecha\n";
    } elseif ($opcion == 2 && $descuento == 20.00) {
        echo "ID: $id | Código: $codigo | Descuento: $descuento% | Fecha: $fecha\n";
    } elseif ($opcion == 3 && $descuento == 30.00) {
        echo "ID: $id | Código: $codigo | Descuento: $descuento% | Fecha: $fecha\n";
    }
}
?>
