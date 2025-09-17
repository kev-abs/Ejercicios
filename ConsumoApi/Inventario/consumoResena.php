<?php

$url = "http://localhost:8080/resena";

echo "¿Qué deseas hacer?\n";
echo "1. Listar reseñas\n";
echo "2. Agregar reseña nueva\n";
echo "3. Editar reseña\n";
echo "4. Eliminar reseña\n";
$opcionMenu = readline("Elige una opcion: ");

// ===== POST =====
if ($opcionMenu == "2") {
    $idCliente   = readline("ID del cliente: ");
    $idProducto  = readline("ID del producto: ");
    $calificacion= (int) readline("Calificación (1-10): ");
    $comentario  = readline("Comentario: ");
    $fecha       = date("Y-m-d");

    $nuevaResena = array(
        "idCliente"   => (int)$idCliente,
        "idProducto"  => (int)$idProducto,
        "calificacion"=> (int)$calificacion,
        "comentario"  => $comentario,
        "fecha"       => $fecha
    );
    $data_json = json_encode($nuevaResena);

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
        echo "Reseña agregada exitosamente\n";
    } else {
        echo "Error al agregar reseña. Código HTTP: $http_code\n";
    }

    
}

// ===== GET =====
if ($opcionMenu == "1") {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); // Método GET
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

    $resenas = json_decode($respuesta);

    $opcion = readline("Ingresa alguna opción para ver reseñas: v (todas), b (buenas), m (malas): ");

    foreach ($resenas as $resena) {
        $datos = explode(" | ", $resena);
        $calificacion = (int)$datos[3];
        $tipo = ($calificacion >= 5) ? "b" : "m";

        if ($opcion === "v" || $opcion === $tipo) {
            echo $resena . " → " . (($tipo === "b") ? "BUENA" : "MALA") . " RESEÑA\n";
        }
    }
}

// ===== PUT =====
if ($opcionMenu == "3") {
    
    $idResena  = readline("ID de la reseña a actualizar: ");
    $idCliente   = readline("Nuevo ID del cliente: ");
    $idProducto  = readline("Nuevo ID del producto: ");
    $calificacion= (int) readline("Nueva calificación (1-10): ");
    $comentario  = readline("Nuevo comentario: ");
    $fecha       = date("Y-m-d");

    $resenaActualizada = array(
        "idCliente"   => (int)$idCliente,
        "idProducto"  => (int)$idProducto,
        "calificacion"=> (int)$calificacion,
        "comentario"  => $comentario,
        "fecha"       => $fecha
    );
    $data_json = json_encode($resenaActualizada);

    $ch = curl_init("$url/$idResena");

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
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

    if ($http_code === 200) {
        echo "Reseña actualizada exitosamente\n";
    } else {
        echo "Error al actualizar reseña. Código HTTP: $http_code\n";
    }

}

// ===== DELETE =====
if ($opcionMenu == "4") {
    $idResena = readline("ID de la reseña a eliminar: ");

    $ch = curl_init("$url/$idResena");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

    $respuesta = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        die("Error en la petición: " . curl_error($ch) . "\n");
    }
    curl_close($ch);

    if ($http_code === 200 || $http_code === 204) {
        echo "Reseña eliminada exitosamente\n";
    } else {
        echo "Error al eliminar reseña. Código HTTP: $http_code\n";
    }
}
?>

