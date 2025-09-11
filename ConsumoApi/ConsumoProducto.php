<?php
$url = "http://localhost:8080/productos";

$consumo = file_get_contents($url);

if ($consumo === FALSE) {
    die("Error al consumir el servicio.");
}

$productos = json_decode($consumo, true); //array

echo "Elige una opción:\n";
echo "1. Mostrar todos los productos\n";
echo "2. Mostrar solo productos con stock disponible\n";
echo "3. Mostrar productos con precio mayor a 100000\n";

$opcion = (int) readline("Opción: ");

echo "=== RESULTADO ===\n";

foreach ($productos as $p) {
    // se separan por |
    $partes = explode("|", $p);

    $id     = trim($partes[0] ?? "");
    $nombre = trim($partes[1] ?? "");
    $precio = (float) trim($partes[3] ?? 0);
    $stock  = (int) filter_var($partes[4] ?? "0", FILTER_SANITIZE_NUMBER_INT);

    if ($opcion == 1) {
        echo "ID: $id | Nombre: $nombre | Precio: $precio | Stock: $stock\n";
    } elseif ($opcion == 2 && $stock > 0) {
        echo "ID: $id | Nombre: $nombre | Precio: $precio | Stock: $stock\n";
    } elseif ($opcion == 3 && $precio > 100000) {
        echo "ID: $id | Nombre: $nombre | Precio: $precio | Stock: $stock\n";
    }
}
?>


