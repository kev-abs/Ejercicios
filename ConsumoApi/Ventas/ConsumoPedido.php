<?php
require_once __DIR__ . '/../Confi.php';


$consumo = file_get_contents($urlPedido);

if ($consumo === FALSE) {
    die("Error al consumir el servicio.");
}


$pedido = json_decode($consumo);

// Pedir la opción al usuario
echo "Elige una opción:\n";
echo "1. Mostrar todos los Pedidos\n";
echo "4. Agregar un nuevo pedido\n";
echo "5. Actualizar un pedido\n";
echo "6. Eliminar un pedido\n";

$opcion = (int) readline("Opción: ");

echo "=== RESULTADO ===\n";

foreach ($pedido as $p) {
    $partes = explode(" ", $c); 
    $id = $partes[0];
    $id_cliente = $partes[1];
    $fecha = $partes[2];
    $estado = $partes[3];
    $total = $partes[4];

    if ($opcion == 1) {
        echo "ID: $id | ID_Cliente: $id_cliente | Fecha_Pedido: $fecha | Estado: $estado | Total: $total\n";
    }
}


?>