<?php
require_once __DIR__ . '/../Confi.php';

$consumo = file_get_contents($urlPedido);

if ($consumo === FALSE) {
    die("Error al consumir el servicio.");
}

$pedido = json_decode($consumo);

// Menú
echo "Elige una opción:\n";
echo "1. Mostrar todos los pedidos\n";
echo "2. Mostrar solo pedidos con estado 'Pendiente'\n";
echo "3. Mostrar pedidos con total mayor a 50000\n";
echo "4. Agregar un nuevo pedido\n";
echo "5. Actualizar un pedido\n";
echo "6. Eliminar un pedido\n";

$opcion = (int) readline("Opción: ");

echo "=== RESULTADO ===\n";

// GET
foreach ($pedido as $p) {
    $linea = $p->id_Pedido . " " . $p->id_Cliente . " " . $p->fecha_Pedido . " " . $p->estado . " " . $p->total;
    $partes = explode(" ", $linea);

    $id = $partes[0];
    $id_cliente = $partes[1];
    $fecha = $partes[2];
    $estado = $partes[3];
    $total = $partes[4];

    if ($opcion == 1) {
        echo "ID: $id | Cliente: $id_cliente | Fecha: $fecha | Estado: $estado | Total: $total\n";
    } elseif ($opcion == 2 && $estado === "Pendiente") {
        echo "ID: $id | Cliente: $id_cliente | Fecha: $fecha | Estado: $estado | Total: $total\n";
    } elseif ($opcion == 3 && $total > 50000) {
        echo "ID: $id | Cliente: $id_cliente | Fecha: $fecha | Estado: $estado | Total: $total\n";
    }
}

// POST
if ($opcion == 4) {
    $id_cliente = readline("Ingrese el ID del cliente: ");
    $fecha = readline("Ingrese la fecha del pedido (YYYY-MM-DD): ");
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

// PUT
if ($opcion == 5) {
    $id = readline("Ingrese el ID del pedido que desea actualizar: ");
    $id_cliente = readline("Ingrese el nuevo ID del cliente: ");
    $fecha = readline("Ingrese la nueva fecha del pedido (YYYY-MM-DD): ");
    $estado = readline("Ingrese el nuevo estado del pedido: ");
    $total = readline("Ingrese el nuevo total: ");

    $datos = array(
        "id_Cliente" => $id_cliente,
        "fecha_Pedido" => $fecha,
        "estado" => $estado,
        "total" => (float)$total
    );

    $data_json = json_encode($datos);

    $proceso = curl_init("$urlPedido/$id");
    curl_setopt($proceso, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($proceso, CURLOPT_POSTFIELDS, $data_json);
    curl_setopt($proceso, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($proceso, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_json)
    ));

    $respuestapet = curl_exec($proceso);
    $http_code = curl_getinfo($proceso, CURLINFO_HTTP_CODE);

    if (curl_errno($proceso)) {
        die("Error en la petición PUT: " . curl_error($proceso) . "\n");
    }
    curl_close($proceso);

    if ($http_code === 200) {
        echo "Pedido actualizado correctamente. Respuesta (200)\n";
    } else {
        echo "Error en el servidor. Respuesta $http_code\n";
        echo "Respuesta del servidor: $respuestapet\n";
    }
}

// DELETE
if ($opcion == 6) {
    $id = readline("Ingrese ID del pedido a eliminar: ");

    $proceso = curl_init("$urlPedido/$id");
    curl_setopt($proceso, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($proceso, CURLOPT_RETURNTRANSFER, true);

    $respuestapet = curl_exec($proceso);
    $http_code = curl_getinfo($proceso, CURLINFO_HTTP_CODE);
    curl_close($proceso);

    if ($http_code === 200 || $http_code === 204) {
        echo "Pedido eliminado correctamente.\n";
    } else {
        echo "Error ($http_code): $respuestapet\n";
    }
}
?>
