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
echo "4. Agregar un nuevo cupon\n";

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

//POST

if ($opcion === "4") {
    $codigo = readline("Ingrese codigo: ");
    $descuento = readline("Ingrese descuento: ");
    $fecha = readline("Ingrese fecha de expiración (YYYY-MM-DD): ");

    $datos = array(
        "codigo" => $codigo,
        "descuento" => (float)$descuento,
        "fecha_Expiracion" => $fecha
    );

    $data_json = json_encode($datos);

    $proceso = curl_init($url);

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
        echo "Usuario guardado correctamente. Respuesta (200)\n";
    } else {
        echo "Error en el servidor. Respuesta $http_code\n";
        echo "Respuesta del servidor: $respuestapet\n";
    }
}

?>
