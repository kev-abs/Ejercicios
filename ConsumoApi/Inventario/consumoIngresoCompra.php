<?php
require_once "ConsumoApi/Confi.php";

echo "===== MENÚ INGRESOS =====\n";
echo "1. Listar ingresos\n";
echo "2. Agregar ingreso\n";
echo "3. Editar ingreso\n";
echo "4. Eliminar ingreso\n";
$opcionMenu = readline("Elige una opción: ");

// ===== POST =====
if ($opcionMenu == "2") {
    $idEmpleado  = readline("ID del empleado: ");
    $idProveedor = readline("ID del proveedor: ");
    $fecha       = date("Y-m-d");
    $total       = readline("Total del ingreso: ");

    $nuevoIngreso = array(
        "id_Empleado"   => (int)$idEmpleado,
        "id_Proveedor"  => (int)$idProveedor,
        "fecha_Ingreso" => $fecha,
        "total"         => number_format((float)$total, 2, '.', '')
    );

    $data_json = json_encode($nuevoIngreso);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Content-Length: " . strlen($data_json)
    ));

    $respuesta = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        die("Error en la petición: " . curl_error($ch) . "\n");
    }
    curl_close($ch);

    if ($http_code === 200 || $http_code === 201) {
        echo "Ingreso agregado exitosamente \n";
    } else {
        echo "Error al agregar ingreso. Código HTTP: $http_code\n";
    }
}

// ===== GET =====
if ($opcionMenu == "1") {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

    $respuesta = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        die("Error en la petición: " . curl_error($ch) . "\n");
    }
    curl_close($ch);

    if ($http_code !== 200) {
        die("Error al consumir el servicio. Código: $http_code\n");
    }

    $ingresos = json_decode($respuesta);

    echo "\n=== LISTA DE INGRESOS ===\n";

    foreach ($ingresos as $ingreso) {
        if (is_string($ingreso)) {
            echo $ingreso . "\n";
        } else {
            echo "{$ingreso->idIngreso} - {$ingreso->idEmpleado}/{$ingreso->idProveedor} - {$ingreso->fechaIngreso} - {$ingreso->total}\n";
        }
    }

}

// ===== PUT =====
if ($opcionMenu == "3") {
    
    $idIngreso  = readline("ID del ingreso a actualizar: ");
    $idEmpleado = readline("Nuevo ID del empleado: ");
    $idProveedor = readline("Nuevo ID del proveedor: ");
    $fecha = readline("Nueva fecha (YYYY-MM-DD): ");
    $total = readline("Nuevo total: ");

    $datosActualizados = array(
        "id_Empleado" => (int)$idEmpleado,
        "id_Proveedor" => (int)$idProveedor,
        "fecha_Ingreso" => $fecha,
        "total" => (float)$total
    );


    $data_json = json_encode($datosActualizados);

    $ch = curl_init($url . "/" . $idIngreso);    
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

    $respuesta = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200) {
        echo "Ingreso actualizado correctamente\n";
    } else {
        echo "Error al actualizar. Código HTTP: $http_code\n";
    }

}

// ===== DELETE =====
if ($opcionMenu == "4") {
    $idIngreso = readline("ID del ingreso a eliminar: ");

    $ch = curl_init($url . "/" . $idIngreso);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

    $respuesta = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200) {
        echo "Ingreso eliminado correctamente\n";
    } else {
        echo "Error al eliminar. Código HTTP: $http_code\n";
    }
}




?>
