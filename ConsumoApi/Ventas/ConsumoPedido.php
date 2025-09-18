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
    $partes = explode(" ", $p); 
    $id = $partes[0];
    $id_cliente = $partes[1];
    $fecha = $partes[2];
    $estado = $partes[3];
    $total = $partes[4];

    if ($opcion == 1) {
        echo "ID: $id | ID_Cliente: $id_cliente | Fecha_Pedido: $fecha | Estado: $estado | Total: $total\n";
    }
}

//POST

if ($opcion == 4) {
    $id_cliente = readline("Ingrese el ID del Cliente: ");
    $fecha = readline("Ingrese la fecha de pedido (YYYY-MM-DD): ");
    $estado = readline("Ingrese el estado del pedido: ");
    $total = readline("Ingrese el total del pedido: ");

    $datos = array(
        "id_Cliente" => $id_cliente,
        "fecha_Pedido" => $fecha,
        "estado" => $estado,
        "total" => (float)$total
    );

    $data_json = json_encode($datos);

    $proceso = curl_init($urlPedido);

    curl_setopt($proceso, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($proceso, CURLOPT_POSTFIELDS, $data_json);
    curl_setopt($proceso, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($proceso, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_json)
    ));
    

    $respuestapet = curl_exec($proceso);

    $http_code = curl_getinfo($proceso, CURLINFO_HTTP_CODE);

    if (curl_errno($proceso)) {
        die("Error en la petición POST: " . curl_error($proceso) . "\n");
    }
    curl_close($proceso);

    if ($http_code === 200) {
        echo "Pedido guardado correctamente. Respuesta (200)\n";
    } else {
        echo "Error en el servidor. Respuesta $http_code\n";
        echo "Respuesta del servidor: $respuestapet\n";
    }
}

?>