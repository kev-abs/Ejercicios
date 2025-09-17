<?php

//consumo de la tabla de proveedores
$url = "http://localhost:8080/proveedor";

$consumo = file_get_contents($url);

if ($consumo === FALSE) {
    die("Error al consumir el servicio.");
}

$productos = json_decode($consumo, true); //array

echo "Elige una opción:\n";
echo "1. Mostrar todos los proveedores\n";
echo "2. Mostrar solo contacto y teléfono de los proveedores\n";

$opcion = (int) readline("Opción: ");

echo "=== RESULTADO ===\n";

foreach ($productos as $pr) {
    // se separan por |
    $partes = preg_split('/\s+/', $pr);

$id            = trim($partes[0] ?? "");
$nombreEmpresa = trim($partes[1] ?? "");
$contacto = trim(($partes[2] ?? "") . " " . ($partes[3] ?? ""));
$telefono = trim($partes[4] ?? "");
$correo   = trim($partes[5] ?? "");
$direccion = trim(implode(" ", array_slice($partes, 6)));
  
    if ($opcion == 1) {
        echo "ID: $id | Nombre Empresa: $nombreEmpresa | Contacto: $contacto | Teléfono: $telefono | Correo: $correo | Dirección: $direccion\n";
    } elseif ($opcion == 2) {
        echo "Contacto: $contacto | Teléfono: $telefono\n";
    }
}
    //agregar proveedor
    $respuesta = readline("¿Desea agregar un nuevo proveedor? Coloca s para (SI) n para no (NO): ");    
    if ($respuesta === "s") {
        $nombreEmpresa = readline("Ingrese nombre de la empresa: ");
        $contacto = readline("Ingrese contacto: ");
        $telefono = readline("Ingrese teléfono: ");
        $correo = readline("Ingrese correo: ");
        $direccion = readline("Ingrese dirección: ");

        $datos = array(
            "nombre_Empresa" => $nombreEmpresa,
            "contacto" => $contacto,
            "telefono" => $telefono,
            "correo" => $correo,
            "direccion" => $direccion
        );

        $data_json = json_encode($datos);

        $proceso = curl_init($url);

        curl_setopt($proceso, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($proceso, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($proceso, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($proceso, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_json))
        );

        $respuestapet = curl_exec($proceso);

        $http_code = curl_getinfo($proceso, CURLINFO_HTTP_CODE);
        if ($http_code === 200) {
            echo "Proveedor agregado exitosamente.\n";
        } else {
            echo "Error al agregar el proveedor. Código HTTP: $http_code\n";
    }
}

?>